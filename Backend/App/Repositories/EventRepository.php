<?php
// backend/app/Repositories/EventRepository.php

class EventRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function findWithRequiredSkills($skills) {
        $query = "SELECT * FROM events WHERE ";
        $conditions = [];
        $params = [];
        
        foreach ($skills as $index => $skill) {
            $conditions[] = "required_skills LIKE ?";
            $params[] = "%$skill%";
        }
        
        $query .= implode(" OR ", $conditions);
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    public function findNeedVolunteers() {
        $stmt = $this->db->query("
            SELECT e.*, 
                   (SELECT COUNT(*) FROM matches m WHERE m.event_id = e.id) as assigned_volunteers,
                   e.volunteer_count - (SELECT COUNT(*) FROM matches m WHERE m.event_id = e.id) as needed_volunteers
            FROM events e
            HAVING needed_volunteers > 0
            ORDER BY e.date ASC
        ");
        
        return $stmt->fetchAll();
    }
    
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM events");
        return $stmt->fetch()['count'];
    }
}
?>