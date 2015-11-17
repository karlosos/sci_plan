<?php
require_once 'db_connect.php';

// Aktualizacja danych

require 'ScheduleManager.php';
/*
$backend = new ScheduleManager();
$backend->updateAllData();
*/

// Przykladowe wyswietlanie planu dla klasy 4C
$plan = R::getAll( 'SELECT * FROM plan WHERE klasa = 4 ORDER BY godzina ASC, dzien ASC' );
$godziny = R::getAll( 'SELECT * FROM godziny' );
$nauczyciele = R::getAll( 'SELECT * FROM nauczyciele' );
$sale = R::getAll( 'SELECT * FROM sale' );

echo '<table>';
echo "<thead>";
echo "<tr>";
echo "<th>godzina</th>";
echo "<th>Poniedziałek</th>";
echo "<th>Wtorek</th>";
echo "<th>Środa</th>";
echo "<th>Czwartek</th>";
echo "<th>Piątek</th>";
echo "</tr>";
echo "</thead>";

$hour_index = 0;   
foreach ($plan as $lekcja) {

    if($lekcja['dzien'] == '1') {
        echo '<tr>';
        echo '<td class="hour">';
        $hour = $godziny[$hour_index];
        echo $hour['start']."-".$hour['stop'];
        echo '</td>';
        $hour_index += 1;
    }
    
    echo '<td ckass="lesson">';
        echo $lekcja['przedmiot'];
        
        $nauczyciel_id = $lekcja['nauczyciel'];
        if($nauczyciel_id != "") {
            $nauczyciel = R::getAll( "SELECT * FROM nauczyciele WHERE legacy_id = $nauczyciel_id" );
            echo '<a class="nauczyciel">'.$nauczyciel[0]['skrot'].'</a>';
        }
        
        $sala_id = $lekcja['sala'];
        if($sala_id != "") {
            $sala = R::getAll( "SELECT * FROM sale WHERE legacy_id = $sala_id" );
            $sala_skrot =  $sala[0]['skrot'];
            
            $sala_skrot = substr($sala_skrot, 0, 2);
            echo '<a class="sala">'.$sala_skrot."</a>";
        }
        //$lekcja['nauczyciel'];
        //echo $nauczyciele[(int)$lekcja['nauczyciel']]['skrot'];
    echo '</td>';
    
    if($lekcja['dzien'] == '5') {
        echo '</tr>';
    }
}

// Przykladowe wyswietlanie planu dla nauczyciela 19
$godziny = R::getAll( 'SELECT * FROM godziny' );
$nauczyciele = R::getAll( 'SELECT * FROM nauczyciele' );
$sale = R::getAll( 'SELECT * FROM sale' );

echo '<table>';
echo "<thead>";
echo "<tr>";
echo "<th>godzina</th>";
echo "<th>Poniedziałek</th>";
echo "<th>Wtorek</th>";
echo "<th>Środa</th>";
echo "<th>Czwartek</th>";
echo "<th>Piątek</th>";
echo "</tr>";
echo "</thead>";

$hour_index = 0;  
for ($j = 0; $j<10; $j++) {
    echo '<tr>';
    echo '<td class="hour">';
    $hour = $godziny[$j];
    echo $hour['start']."-".$hour['stop'];
    echo '</td>';
    for ($i = 1; $i < 6; $i++) {
        //echo "SELECT * FROM plan WHERE dzien = $i AND godzina = $j AND nauczyciel = 17";
        $lekcja = R::getAll( "SELECT * FROM plan WHERE dzien = $i AND godzina = $j AND nauczyciel = 17" );
        echo '<td ckass="lesson">';
        
                $klasa_id = $lekcja[0]['klasa'];
                echo '<a class="klasa">'.$klasa_id.'</a>';
                
                echo $lekcja[0]['przedmiot'];

                

                $sala_id = $lekcja[0]['sala'];
                if($sala_id != "") {
                    $sala = R::getAll( "SELECT * FROM sale WHERE legacy_id = $sala_id" );
                    $sala_skrot =  $sala[0]['skrot'];

                    $sala_skrot = substr($sala_skrot, 0, 2);
                    echo '<a class="sala">'.$sala_skrot."</a>";
                }
                //$lekcja['nauczyciel'];
                //echo $nauczyciele[(int)$lekcja['nauczyciel']]['skrot'];
        echo '</td>';
    }
    echo '</tr>';
}
echo '</table>';


// Przykladowe wyswietlanie planu dla sali 3
$godziny = R::getAll( 'SELECT * FROM godziny' );
$nauczyciele = R::getAll( 'SELECT * FROM nauczyciele' );
$sale = R::getAll( 'SELECT * FROM sale' );

echo '<table>';
echo "<thead>";
echo "<tr>";
echo "<th>godzina</th>";
echo "<th>Poniedziałek</th>";
echo "<th>Wtorek</th>";
echo "<th>Środa</th>";
echo "<th>Czwartek</th>";
echo "<th>Piątek</th>";
echo "</tr>";
echo "</thead>";

$hour_index = 0;  
for ($j = 0; $j<10; $j++) {
    echo '<tr>';
    echo '<td class="hour">';
    $hour = $godziny[$j];
    echo $hour['start']."-".$hour['stop'];
    echo '</td>';
    for ($i = 1; $i < 6; $i++) {
        //echo "SELECT * FROM plan WHERE dzien = $i AND godzina = $j AND sala = 3";
        $lekcja = R::getAll( "SELECT * FROM plan WHERE dzien = $i AND godzina = $j AND sala = 3" );
        echo '<td ckass="lesson">';
        
                $klasa_id = $lekcja[0]['klasa'];
                echo '<a class="klasa">'.$klasa_id.'</a>';
                
                echo $lekcja[0]['przedmiot'];

                echo '<a class="nauczyciel">'.$lekcja[0]['nauczyciel'].'</a>';;

                $sala_id = $lekcja[0]['sala'];
                if($sala_id != "") {
                    $sala = R::getAll( "SELECT * FROM sale WHERE legacy_id = $sala_id" );
                    $sala_skrot =  $sala[0]['skrot'];

                    $sala_skrot = substr($sala_skrot, 0, 2);
                    echo '<a class="sala">'.$sala_skrot."</a>";
                }
                //$lekcja['nauczyciel'];
                //echo $nauczyciele[(int)$lekcja['nauczyciel']]['skrot'];
        echo '</td>';
    }
    echo '</tr>';
}
echo '</table>';