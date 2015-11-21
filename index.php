<?php
require_once 'db_connect.php';

// Aktualizacja danych

require 'ScheduleManager.php';
require 'ScheduleController.php';
/*
$backend = new ScheduleManager();
$backend->updateAllData();
*/

$schedule_controller = new ScheduleController();
$schedule_controller->getTeacherList();
$schedule_controller->getClassList();
$schedule_controller->getClassroomList();

if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = $_GET['id'];
    
    if($type == "class") {
        $schedule_controller->getClassSchedule($id);
    } 
    else if($type == "classroom") {
        $schedule_controller->getClassroomSchedule($id);
    }
    else if($type == "teacher") {
        $schedule_controller->getTeacherSchedule($id);
    }
}

