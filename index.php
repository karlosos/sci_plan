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
$schedule_controller->getClassSchedule(12);



