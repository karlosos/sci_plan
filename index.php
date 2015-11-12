<?php
require_once 'db_connect.php';

// Aktualizacja danych
/*
require 'ScheduleManager.php';
$backend = new ScheduleManager();
$backend->updateAllData();
*/

// Przykladowe wyswietlanie planu dla klasy 4C
$plan = R::getAll( 'SELECT * FROM plan WHERE klasa = 12' );

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