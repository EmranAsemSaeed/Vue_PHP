<?php
// backend/public/index.php

// require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../config.php';
// require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Database.php';
// require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Router.php';
// require_once __DIR__ . '/../app/Core/Response.php';
require_once __DIR__ . '/../app/Core/Response.php';
require_once __DIR__ . '/../app/Core/Controller.php';//-------
// require_once __DIR__ . '/../app/Controllers/Controller.php';//errorrrrrrr
require_once __DIR__ . '/../app/Controllers/AuthController.php';
// require_once __DIR__ . '/../app/Controllers/VolunteerController.php';
// require_once __DIR__ . '../app/Controllers/VolunteerController.php';//*******
require_once __DIR__ . '/../app/Controllers/VolunteerController.php';
// require_once __DIR__ . '/../app/Controllers/EventController.php';
require_once __DIR__ . '/../app/Controllers/EventController.php';
// require_once __DIR__ . '/../app/Controllers/MatchController.php';
require_once __DIR__ . '/../app/Controllers/MatchController.php';
// require_once __DIR__ . '/../app/Models/Volunteer.php';
require_once __DIR__ . '/../app/Models/Volunteer.php';
// require_once __DIR__ . '/../app/Models/Event.php';
require_once __DIR__ . '/../app/Models/Event.php';
// require_once __DIR__ . '/../app/Repositories/VolunteerRepository.php';
require_once __DIR__ . '/../app/Repositories/VolunteerRepository.php';
// require_once __DIR__ . '/../app/Repositories/EventRepository.php';
require_once __DIR__ . '/../app/Repositories/EventRepository.php';

require_once __DIR__ . '/../app/Controllers/UserController.php';
// Setting CORS headers
$headers = Config::getCorsHeaders();
foreach ($headers as $header) {
    header($header);
}

// Handling OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$router = new Router();
$volunteerController = new VolunteerController();
$eventController = new EventController();
$matchController = new MatchController();
$userController = new UserController();

$router->addRoute('GET', '/api/users', [$userController, 'index']);
$router->addRoute('GET', '/api/users/{id}', [$userController, 'show']);
$router->addRoute('POST', '/api/users', [$userController, 'store']);
$router->addRoute('PUT', '/api/users/{id}', [$userController, 'update']);
$router->addRoute('DELETE', '/api/users/{id}', [$userController, 'delete']);
$router->addRoute('GET', '/api/users/stats', [$userController, 'stats']);

$router->addRoute('GET', '/api/volunteers', [$volunteerController, 'index']);
$router->addRoute('GET', '/api/volunteers/{id}', [$volunteerController, 'show']);
$router->addRoute('POST', '/api/volunteers', [$volunteerController, 'store']);
$router->addRoute('PUT', '/api/volunteers/{id}', [$volunteerController, 'update']);
$router->addRoute('DELETE', '/api/volunteers/{id}', [$volunteerController, 'delete']);
$router->addRoute('GET', '/api/volunteers/skills/{skills}', [$volunteerController, 'findBySkills']);
$router->addRoute('GET', '/api/volunteers/stats', [$volunteerController, 'getStats']);

$router->addRoute('GET', '/api/events', [$eventController, 'index']);
$router->addRoute('GET', '/api/events/{id}', [$eventController, 'show']);
$router->addRoute('POST', '/api/events', [$eventController, 'store']);
$router->addRoute('PUT', '/api/events/{id}', [$eventController, 'update']);
$router->addRoute('DELETE', '/api/events/{id}', [$eventController, 'delete']);
$router->addRoute('GET', '/api/events/upcoming', [$eventController, 'upcoming']);
$router->addRoute('GET', '/api/events/need-volunteers', [$eventController, 'needVolunteers']);
$router->addRoute('GET', '/api/events/stats', [$eventController, 'getStats']);

$router->addRoute('GET', '/api/matches', [$matchController, 'index']);
$router->addRoute('POST', '/api/matches/generate', [$matchController, 'generate']);
$router->addRoute('PUT', '/api/matches/{id}', [$matchController, 'updateStatus']);
$router->addRoute('DELETE', '/api/matches/{id}', [$matchController, 'delete']);

$router->run();
?>
