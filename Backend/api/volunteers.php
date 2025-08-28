<?php
// header('Content-Type: application/json');
// header('Access-Control-Allow-Origin: *');
// include 'config.php';

// $stmt = $pdo->query("SELECT * FROM volunteers");
// $volunteers = $stmt->fetchAll();

// // تحويل الـ JSON من قاعدة البيانات إلى مصفوفة في PHP
// foreach ($volunteers as &$vol) {
//     $vol['skills'] = json_decode($vol['skills'], true);
//     $vol['availability'] = json_decode($vol['availability'], true);
// }

// echo json_encode($volunteers);


// backend-php/api/volunteers.php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

include_once("../config/config.php");

// استعلام لجلب البيانات
$sql = "SELECT id, name, email, location, skills, availability FROM volunteers";
$result = $mysqli->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    // skills و availability نحولها لمصفوفة لو مخزنة كنصوص مفصولة بفواصل
    $row['skills'] = !empty($row['skills']) ? explode(",", $row['skills']) : [];
    $row['availability'] = !empty($row['availability']) ? explode(",", $row['availability']) : [];
    $data[] = $row;
}

echo json_encode($data);
