<?php
// backend/app/Models/Event.php

class Event {
    private $db;
    
    public $id;
    public $title;
    public $description;
    public $date;
    public $time;
    public $location;
    public $required_skills;
    public $volunteer_count;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM events ORDER BY date DESC, created_at DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO events (title, description, date, time, location, required_skills, volunteer_count) 
                VALUES (:title, :description, :date, :time, :location, :required_skills, :volunteer_count)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':date' => $data['date'],
            ':time' => $data['time'],
            ':location' => $data['location'],
            ':required_skills' => $data['required_skills'],
            ':volunteer_count' => $data['volunteer_count'] ?? 0
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE events 
                SET title = :title, description = :description, date = :date, 
                    time = :time, location = :location, required_skills = :required_skills, 
                    volunteer_count = :volunteer_count, updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':date' => $data['date'],
            ':time' => $data['time'],
            ':location' => $data['location'],
            ':required_skills' => $data['required_skills'],
            ':volunteer_count' => $data['volunteer_count'] ?? 0
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getUpcoming() {
        $stmt = $this->db->query("SELECT * FROM events WHERE date >= CURDATE() ORDER BY date ASC");
        return $stmt->fetchAll();
    }
}
?>