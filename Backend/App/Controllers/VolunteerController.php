<?php
// backend/app/Controllers/VolunteerController.php

class VolunteerController extends Controller {
    private $volunteerModel;
    private $volunteerRepo;

    public function __construct() {
        parent::__construct();
        $this->volunteerModel = new Volunteer();
        $this->volunteerRepo = new VolunteerRepository();
    }

    public function index() {
        $volunteers = $this->volunteerModel->getAll();
        $this->response->json($volunteers);
    }

    public function show($id) {
        $volunteer = $this->volunteerModel->getById($id);
        
        if ($volunteer) {
            $this->response->json($volunteer);
        } else {
            $this->response->json(['error' => 'Volunteer not found'], 404);
        }
    }

    public function store() {
        $data = $this->getRequestData();
        
        if (!$this->validateRequiredFields($data, ['name', 'email'])) {
            return;
        }

        $id = $this->volunteerModel->create($data);
        $this->response->json(['id' => $id, 'message' => 'Volunteer created successfully'], 201);
    }

    public function update($id) {
        $data = $this->getRequestData();
        
        $result = $this->volunteerModel->update($id, $data);
        
        if ($result) {
            $this->response->json(['message' => 'Volunteer updated successfully']);
        } else {
            $this->response->json(['error' => 'Volunteer not found'], 404);
        }
    }

    public function delete($id) {
        $result = $this->volunteerModel->delete($id);
        
        if ($result) {
            $this->response->json(['message' => 'Volunteer deleted successfully']);
        } else {
            $this->response->json(['error' => 'Volunteer not found'], 404);
        }
    }
    
    public function findBySkills($skills) {
        $volunteers = $this->volunteerRepo->findWithSkills(explode(',', $skills));
        $this->response->json($volunteers);
    }
    
    public function getStats() {
        $total = $this->volunteerRepo->countAll();
        $this->response->json(['total_volunteers' => $total]);
    }
}
?>