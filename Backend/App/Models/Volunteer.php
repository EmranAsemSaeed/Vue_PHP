<?php
// backend/app/Models/Volunteer.php

class Volunteer {
    private $db;
    
    public $id;
    public $name;
    public $email;
    public $skills;
    public $availability;
    public $interests;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM volunteers ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM volunteers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO volunteers (name, email, skills, availability, interests) 
                VALUES (:name, :email, :skills, :availability, :interests)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':skills' => $data['skills'],
            ':availability' => $data['availability'],
            ':interests' => $data['interests']
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE volunteers 
                SET name = :name, email = :email, skills = :skills, 
                    availability = :availability, interests = :interests, updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':skills' => $data['skills'],
            ':availability' => $data['availability'],
            ':interests' => $data['interests']
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM volunteers WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getBySkills($skills) {
        $skillsList = explode(',', $skills);
        $placeholders = implode(',', array_fill(0, count($skillsList), '?'));
        
        $stmt = $this->db->prepare("SELECT * FROM volunteers WHERE skills IN ($placeholders)");
        $stmt->execute($skillsList);
        return $stmt->fetchAll();
    }
}
?>