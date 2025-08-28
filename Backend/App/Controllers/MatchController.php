<?php
// backend/app/Controllers/MatchController.php

class MatchController extends Controller {
    private $db;
    private $volunteerModel;
    private $eventModel;

    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
        $this->volunteerModel = new Volunteer();
        $this->eventModel = new Event();
    }

    public function index() {
        $stmt = $this->db->query("
            SELECT m.*, v.name as volunteer_name, e.title as event_title 
            FROM matches m 
            JOIN volunteers v ON m.volunteer_id = v.id 
            JOIN events e ON m.event_id = e.id 
            ORDER BY m.created_at DESC
        ");
        
        $matches = $stmt->fetchAll();
        $this->response->json($matches);
    }

    public function generate() {
        // الحصول على الفعاليات التي تحتاج إلى متطوعين
        $eventsNeedingVolunteers = $this->getEventsNeedingVolunteers();
        
        $matches = [];
        
        foreach ($eventsNeedingVolunteers as $event) {
            // إيجاد متطوعين مناسبين لهذه الفعالية
            $suitableVolunteers = $this->findSuitableVolunteers($event);
            
            foreach ($suitableVolunteers as $volunteer) {
                // حساب درجة المطابقة
                $matchScore = $this->calculateMatchScore($volunteer, $event);
                
                if ($matchScore > 50) { // إذا كانت درجة المطابقة مقبولة
                    $this->createMatch($volunteer['id'], $event['id'], $matchScore);
                    $matches[] = [
                        'volunteer' => $volunteer['name'],
                        'event' => $event['title'],
                        'score' => $matchScore
                    ];
                }
            }
        }
        
        $this->response->json([
            'message' => 'Matching process completed',
            'matches_created' => count($matches),
            'matches' => $matches
        ]);
    }
    
    private function getEventsNeedingVolunteers() {
        $stmt = $this->db->query("
            SELECT e.*, 
                   (SELECT COUNT(*) FROM matches m WHERE m.event_id = e.id) as assigned_count,
                   e.volunteer_count - (SELECT COUNT(*) FROM matches m WHERE m.event_id = e.id) as needed_count
            FROM events e
            HAVING needed_count > 0 AND e.date >= CURDATE()
        ");
        
        return $stmt->fetchAll();
    }
    
    private function findSuitableVolunteers($event) {
        $requiredSkills = explode(',', $event['required_skills']);
        $placeholders = implode(',', array_fill(0, count($requiredSkills), '?'));
        
        $stmt = $this->db->prepare("
            SELECT v.* FROM volunteers v 
            WHERE v.id NOT IN (
                SELECT m.volunteer_id FROM matches m 
                JOIN events e ON m.event_id = e.id 
                WHERE e.date = ?
            )
            AND (v.skills LIKE CONCAT('%', ?, '%') OR v.skills LIKE CONCAT('%', ?, '%'))
        ");
        
        // إضافة المعلمات للاستعلام
        $params = [$event['date']];
        foreach ($requiredSkills as $skill) {
            $params[] = trim($skill);
            $params[] = trim($skill);
        }
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function calculateMatchScore($volunteer, $event) {
        $score = 0;
        
        // مطابقة المهارات
        $volunteerSkills = explode(',', $volunteer['skills']);
        $requiredSkills = explode(',', $event['required_skills']);
        
        $matchedSkills = array_intersect(
            array_map('trim', $volunteerSkills),
            array_map('trim', $requiredSkills)
        );
        
        if (!empty($requiredSkills)) {
            $skillMatchPercent = (count($matchedSkills) / count($requiredSkills)) * 100;
            $score += $skillMatchPercent * 0.7; // 70% من النتيجة تعتمد على المهارات
        }
        
        // مطابقة الاهتمامات (يمكن تطويرها أكثر)
        if (!empty($volunteer['interests']) && stripos($volunteer['interests'], $event['title']) !== false) {
            $score += 30; // إضافة 30% إذا كان العنوان موجود في اهتمامات المتطوع
        }
        
        return min(100, $score); //确保不超过100%
    }
    
    private function createMatch($volunteerId, $eventId, $score) {
        $stmt = $this->db->prepare("
            INSERT INTO matches (volunteer_id, event_id, match_score, status) 
            VALUES (?, ?, ?, 'pending')
            ON DUPLICATE KEY UPDATE match_score = VALUES(match_score)
        ");
        
        return $stmt->execute([$volunteerId, $eventId, $score]);
    }
    
    public function updateStatus($matchId) {
        $data = $this->getRequestData();
        
        if (!isset($data['status']) || !in_array($data['status'], ['confirmed', 'rejected'])) {
            $this->response->json(['error' => 'Invalid status'], 400);
            return;
        }
        
        $stmt = $this->db->prepare("UPDATE matches SET status = ? WHERE id = ?");
        $result = $stmt->execute([$data['status'], $matchId]);
        
        if ($result) {
            $this->response->json(['message' => 'Match status updated successfully']);
        } else {
            $this->response->json(['error' => 'Match not found'], 404);
        }
    }
    
    public function delete($matchId) {
        $stmt = $this->db->prepare("DELETE FROM matches WHERE id = ?");
        $result = $stmt->execute([$matchId]);
        
        if ($result) {
            $this->response->json(['message' => 'Match deleted successfully']);
        } else {
            $this->response->json(['error' => 'Match not found'], 404);
        }
    }
}
?>