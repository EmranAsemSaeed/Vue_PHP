<?php
// backend/app/Repositories/VolunteerRepository.php

class VolunteerRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function findWithSkills($skills) {
        $query = "SELECT * FROM volunteers WHERE ";
        $conditions = [];
        $params = [];
        
        foreach ($skills as $index => $skill) {
            $conditions[] = "skills LIKE ?";
            $params[] = "%$skill%";
        }
        
        $query .= implode(" OR ", $conditions);
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    public function findAvailableByDate($date) {
        $stmt = $this->db->prepare("
            SELECT v.* FROM volunteers v 
            WHERE v.id NOT IN (
                SELECT m.volunteer_id FROM matches m 
                JOIN events e ON m.event_id = e.id 
                WHERE e.date = ?
            )
        ");
        $stmt->execute([$date]);
        
        return $stmt->fetchAll();
    }
    
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM volunteers");
        return $stmt->fetch()['count'];
    }
}
?>