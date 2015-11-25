<?php

require_once 'db_connect.php';

/**
 * Class for generating schedule responses.
 */
class ScheduleController
{
    /**
     * Generating html output for Class Schedule.
     *
     * @param type $class_id
     */
    public function getClassSchedule($class_id)
    {
        // Przykladowe wyswietlanie planu dla klasy 4C
        $schedule = R::getAll("SELECT * FROM plan WHERE klasa = $class_id ORDER BY godzina ASC, dzien ASC");
        $hours = R::getAll('SELECT * FROM godziny');
        $teachers = R::getAll('SELECT * FROM nauczyciele');
        $classrooms = R::getAll('SELECT * FROM sale');

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>godzina</th>';
        echo '<th>Poniedziałek</th>';
        echo '<th>Wtorek</th>';
        echo '<th>Środa</th>';
        echo '<th>Czwartek</th>';
        echo '<th>Piątek</th>';
        echo '</tr>';
        echo '</thead>';

        $hour_index = 0;
        foreach ($schedule as $lesson) {
            if ($lesson['dzien'] == '1') {
                echo '<tr>';
                echo '<td class="hour">';
                $hour = $hours[$hour_index];
                echo $hour['start'].'-'.$hour['stop'];
                echo '</td>';
                $hour_index += 1;
            }

            echo '<td ckass="lesson">';
            echo $lesson['przedmiot'];

            $teacher_id = $lesson['nauczyciel'];
            if ($teacher_id != '') {
                $teacher = R::getAll("SELECT * FROM nauczyciele WHERE legacy_id = $teacher_id");
                echo '<a class="nauczyciel" href="?type=teacher&id='.$teacher_id.'">'.$teacher[0]['skrot'].'</a>';
            }

            $classroom_id = $lesson['sala'];
            if ($classroom_id != '') {
                $classroom = R::getAll("SELECT * FROM sale WHERE legacy_id = $classroom_id");
                $classroom_short = $classroom[0]['skrot'];

                $classroom_short = substr($classroom_short, 0, 2);
                echo '<a class="sala" href="?type=classroom&id='.$classroom_id.'">'.$classroom_short.'</a>';
            }
                //$lekcja['nauczyciel'];
                //echo $nauczyciele[(int)$lekcja['nauczyciel']]['skrot'];
            echo '</td>';

            if ($lesson['dzien'] == '5') {
                echo '</tr>';
            }
        }
    }

    /**
     * Generate html output for teachers schedule.
     *
     * @param type $teacher_id
     */
    public function getTeacherSchedule($teacher_id)
    {
        // Przykladowe wyswietlanie planu dla nauczyciela 19
        $hours = R::getAll('SELECT * FROM godziny');
        $teachers = R::getAll('SELECT * FROM nauczyciele');
        $classrooms = R::getAll('SELECT * FROM sale');

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>godzina</th>';
        echo '<th>Poniedziałek</th>';
        echo '<th>Wtorek</th>';
        echo '<th>Środa</th>';
        echo '<th>Czwartek</th>';
        echo '<th>Piątek</th>';
        echo '</tr>';
        echo '</thead>';

        $hour_index = 0;
        for ($j = 0; $j < 10; ++$j) {
            echo '<tr>';
            echo '<td class="hour">';
            $hour = $hours[$j];
            echo $hour['start'].'-'.$hour['stop'];
            echo '</td>';
            for ($i = 1; $i < 6; ++$i) {
                //echo "SELECT * FROM plan WHERE dzien = $i AND godzina = $j AND nauczyciel = 17";
                $lesson = R::getAll("SELECT * FROM plan WHERE dzien = $i AND godzina = $j AND nauczyciel = $teacher_id");
                echo '<td ckass="lesson">';

                $klasa_id = $lesson[0]['klasa'];
                echo '<a class="klasa" href="?type=class&id='.$klasa_id.'">'.$klasa_id.'</a>';

                echo $lesson[0]['przedmiot'];

                $classroom_id = $lesson[0]['sala'];
                if ($classroom_id != '') {
                    $classroom = R::getAll("SELECT * FROM sale WHERE legacy_id = $classroom_id");
                    $clasroom_shortcut = $classroom[0]['skrot'];

                    $clasroom_shortcut = substr($clasroom_shortcut, 0, 2);
                    echo '<a class="sala" href="?type=classroom&id='.$classroom_id.'">'.$clasroom_shortcut.'</a>';
                }
                        //$lekcja['nauczyciel'];
                        //echo $nauczyciele[(int)$lekcja['nauczyciel']]['skrot'];
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    /**
     * Generate html output for classrom schedule.
     *
     * @param type $classroom_id
     */
    public function getClassroomSchedule($classroom_id)
    {
        // Przykladowe wyswietlanie planu dla sali 3
        $hours = R::getAll('SELECT * FROM godziny');
        $teachers = R::getAll('SELECT * FROM nauczyciele');
        $classrooms = R::getAll('SELECT * FROM sale');

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>godzina</th>';
        echo '<th>Poniedziałek</th>';
        echo '<th>Wtorek</th>';
        echo '<th>Środa</th>';
        echo '<th>Czwartek</th>';
        echo '<th>Piątek</th>';
        echo '</tr>';
        echo '</thead>';

        $hour_index = 0;
        for ($j = 0; $j < 10; ++$j) {
            echo '<tr>';
            echo '<td class="hour">';
            $hour = $hours[$j];
            echo $hour['start'].'-'.$hour['stop'];
            echo '</td>';
            for ($i = 1; $i < 6; ++$i) {
                //echo "SELECT * FROM plan WHERE dzien = $i AND godzina = $j AND sala = 3";
                $lesson = R::getAll("SELECT * FROM plan WHERE dzien = $i AND godzina = $j AND sala = $classroom_id");
                echo '<td ckass="lesson">';

                $class_id = $lesson[0]['klasa'];
                echo '<a class="klasa" href="?type=class&id='.$class_id.'">'.$class_id.'</a>';

                echo $lesson[0]['przedmiot'];

                echo '<a class="nauczyciel" href="?type=teacher&id='.$lesson[0]['nauczyciel'].'">'.$lesson[0]['nauczyciel'].'</a>';

                $clasroom_id = $lesson[0]['sala'];
                if ($clasroom_id != '') {
                    $classroom = R::getAll("SELECT * FROM sale WHERE legacy_id = $clasroom_id");
                    $classroom_shortcut = $classroom[0]['skrot'];

                    $classroom_shortcut = substr($classroom_shortcut, 0, 2);
                    echo '<a class="sala" href="?type=classroom&id='.$classroom[0]['legacy_id'].'">'.$classroom_shortcut.'</a>';
                }
                        //$lekcja['nauczyciel'];
                        //echo $nauczyciele[(int)$lekcja['nauczyciel']]['skrot'];
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    /**
     * Generate html list with links to teachers schedules.
     */
    public function getTeacherList()
    {
        $teachers = R::getAll('SELECT * FROM nauczyciele');
        foreach ($teachers as $teacher) {
            echo "<a href='?type=teacher&id=".$teacher['legacy_id']."'>".$teacher['skrot'].'</a><br>';
        }
    }

    /**
     * Generate html list with links to classes schedules.
     */
    public function getClassList()
    {
        $classes = R::getAll('SELECT * FROM klasy');
        foreach ($classes as $class) {
            echo "<a href='?type=class&id=".$class['id']."'>".$class['nazwa'].'</a><br>';
        }
    }

    /**
     * Generate html list with links to classrooms schedules.
     */
    public function getClassroomList()
    {
        $classrooms = R::getAll('SELECT * FROM sale');
        foreach ($classrooms as $classroom) {
            echo "<a href='?type=classroom&id=".$classroom['legacy_id']."'>".$classroom['skrot'].'</a><br>';
        }
    }
}
