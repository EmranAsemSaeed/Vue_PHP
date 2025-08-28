<?php
// backend/app/Controllers/EventController.php

class EventController extends Controller {
    private $eventModel;
    private $eventRepo;

    public function __construct() {
        parent::__construct();
        $this->eventModel = new Event();
        $this->eventRepo = new EventRepository();
    }

    public function index() {
        $events = $this->eventModel->getAll();
        $this->response->json($events);
    }

    public function show($id) {
        $event = $this->eventModel->getById($id);
        
        if ($event) {
            $this->response->json($event);
        } else {
            $this->response->json(['error' => 'Event not found'], 404);
        }
    }

    public function store() {
        $data = $this->getRequestData();
        
        if (!$this->validateRequiredFields($data, ['title', 'date'])) {
            return;
        }

        $id = $this->eventModel->create($data);
        $this->response->json(['id' => $id, 'message' => 'Event created successfully'], 201);
    }

    public function update($id) {
        $data = $this->getRequestData();
        
        $result = $this->eventModel->update($id, $data);
        
        if ($result) {
            $this->response->json(['message' => 'Event updated successfully']);
        } else {
            $this->response->json(['error' => 'Event not found'], 404);
        }
    }

    public function delete($id) {
        $result = $this->eventModel->delete($id);
        
        if ($result) {
            $this->response->json(['message' => 'Event deleted successfully']);
        } else {
            $this->response->json(['error' => 'Event not found'], 404);
        }
    }
    
    public function upcoming() {
        $events = $this->eventModel->getUpcoming();
        $this->response->json($events);
    }
    
    public function needVolunteers() {
        $events = $this->eventRepo->findNeedVolunteers();
        $this->response->json($events);
    }
    
    public function getStats() {
        $total = $this->eventRepo->countAll();
        $upcoming = count($this->eventModel->getUpcoming());
        $needVolunteers = count($this->eventRepo->findNeedVolunteers());
        
        $this->response->json([
            'total_events' => $total,
            'upcoming_events' => $upcoming,
            'events_need_volunteers' => $needVolunteers
        ]);
    }
}
?>