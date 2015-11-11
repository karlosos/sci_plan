<?php
/*
require 'rb.php';
error_reporting(E_ALL);

R::setup('mysql:host=localhost;dbname=plan', 'root', 'osiem');
ini_set('max_execution_time', 1800); //300 seconds = 5 minutes
*/
// Aktualizacja danych

error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'ScheduleManager.php';
$backend = new ScheduleManager();
$backend->updateAllData();


error_reporting(E_ALL);

$plan = R::getAll( 'SELECT * FROM plan WHERE klasa = 1' );

echo '<table>';
foreach ($plan as $lekcja) {
    if($lekcja['dzien'] == '1') {
        echo '<tr>';
    }
    
    echo '<td>';
        echo $lekcja['przedmiot'];
    echo '</td>';
    
    if($lekcja['dzien'] == '5') {
        echo '</tr>';
    }
}
echo '</table>';