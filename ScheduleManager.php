<?php
require 'simple_html_dom.php';
require_once 'db_connect.php';

/**
 * Class for updating schedule database
 */
Class ScheduleManager {
    
    /**
     * Member to store list of hours
     * @var array
     */
    private $hours_list = array();
    
    /**
     * Member to store list of schedules
     * @var array
     */
    private $schedules_list = array();
      
    /**
     * Main function for updating data
     */
    public function updateAllData() {
        R::wipe('plan');
        
        $this->updateTeachersList($this->downloadSchedulesList(1));
        $this->updateRoomList($this->downloadSchedulesList(2));
        $klasy = $this->downloadSchedulesList(0);
        foreach ($this->downloadSchedulesList(0) as $class) {
            $this->updateClassSchedule($class[0]);
        }
    }
    
    /**
     * Download list such as classes, classrooms and teachers
     * 
     * Classes type 0
     * Teachers typ 1
     * Classrooms type 2
     * 
     * @param int $i list type
     * @return list of specified type
     */
    private function downloadSchedulesList($i) {
        $html = file_get_html('http://www.sci.edu.pl/plan/lista.html');

        $list = array();
        $ul = $html->find('ul', $i);
        foreach ($ul->find('li') as $li) {
            $row = array();
            $a = $li->find('a', 0);
            $row[] = "http://www.sci.edu.pl/plan/" . $a->href;
            $row[] = $a->plaintext;

            $list[] = $row;
        }
        $this->schedules_list = $list;
        return $list;
    }
    
    /**
     * Update teachers with teachers list
     * @param array $array Teachers list
     */
    private function updateTeachersList($array) {
        R::wipe('nauczyciele');
        
        foreach($array as $teacher_el) {
            R::useWriterCache(true);
        
            $teacher = R::dispense('nauczyciele');
            
            $legacy_id = $teacher_el[0];
            $legacy_id = substr($legacy_id, 0, strlen($legacy_id) - 5);
            $legacy_id = substr($legacy_id, 34);
            
            $teacher->legacy_id = (string) $legacy_id;
            $teacher->skrot = (string) $teacher_el[1];
            $teacher->pelna_nazwa = (string) '';
            
            $id = R::store($teacher);
        }
    }
    
    /**
     * Scraping and updating hours list from link
     * @param string $hours_list_link
     */
    private function updateHoursList($hours_list_link) {
        $html = file_get_html($hours_list_link);
        $schedule = $html->find('.tabela', 0);
        $hours_array = array();
          for ($i = 1; $i <= 11; $i++) {
            $td = $schedule->find('tr', $i)->find('td', 1);
            $hours_from_td = $td->plaintext;

            if(strpos($hours_from_td, " ")) {
                if(strpos($hours_from_td, " ") > strpos($hours_from_td, "-")) {
                $start = substr($hours_from_td, 0, strpos($hours_from_td, "-"));
                $stop = substr($hours_from_td, strpos($hours_from_td, " ")+1);
            } else {
                $start = substr($hours_from_td, 0, strpos($hours_from_td, " "));
                $stop = substr($hours_from_td, strpos($hours_from_td, "-")+1);                    

            }
            } else {
                $start = substr($hours_from_td, 0, strpos($hours_from_td, "-"));
                $stop = substr($hours_from_td, strpos($hours_from_td, "-")+1);             
            }

            $start = trim($start);
            $stop = trim($stop);

            $hour_single_pair = array();
            $hour_single_pair[0] = $start;
            $hour_single_pair[1] = $stop;

            $hours_array[] = $hour_single_pair;
          }
                
       R::wipe('godziny');
       foreach($hours_array as $i) {
            R::useWriterCache(true);
        
            $hour_single_pair = R::dispense('godziny');
            
            $hour_single_pair->start = (string) $i[0];
            $hour_single_pair->stop = (string) $i[1];
            
            $id = R::store($hour_single_pair);
       }
       $this->setHoursList($hours_array);
    }
    
    /**
     * Updating class rooms from list
     * @param array $array Class rooms list
     */
    private function updateRoomList($array) {
        R::wipe('sale');
        
        //print_r($array);
        foreach($array as $salax) {
            //print_r($salax);
            R::useWriterCache(true);
        
            $classroom = R::dispense('sale');
            
            $legacy_id = $salax[0];
            $legacy_id = substr($legacy_id, 0, strlen($legacy_id) - 5);
            $legacy_id = substr($legacy_id, 34);
            
            $test = 'test';
            $classroom->legacy_id = (string) $legacy_id;
            $classroom->skrot = (string) $salax[1];
            $classroom->pelna_nazwa = (string) '';
            
            $id = R::store($classroom);
        }
    }
    
    /**
     * Inserting single schedule row to database 
     * @param string $klasa
     * @param string $dzien
     * @param string $godzina
     * @param string $nauczyciel
     * @param string $przedmiot
     * @param string $sala
     */
    private function putScheduleRow($klasa, $dzien, $godzina, $nauczyciel, $przedmiot, $sala) {
        R::useWriterCache(true);
        
        $wiersz = R::dispense('plan');
        $wiersz->klasa = (string) $klasa;
        $wiersz->dzien = (string) $dzien;
        $wiersz->godzina = (string) $godzina;
        if(!$nauczyciel=='') {
            $wiersz->przedmiot = (string) $przedmiot;
            $wiersz->nauczyciel = (string) $nauczyciel;
            $wiersz->sala = (string) $sala;
        } 
        $id = R::store($wiersz);
    }
    
    /**
     * Scraping and updating specified class schedule
     * @param string $link
     */
    private function updateClassSchedule($link) {
        $html = file_get_html($link);
        $schedule = $html->find('.tabela', 0);
        $class = $link;
        $class = substr($class, 34);
        $class = substr($class, 0, strlen($class) - 5);
        $array = array();
        for ($hour_index = 1; $hour_index <= 11; $hour_index++) {
            for ($day_index = 2; $day_index < 7; $day_index++) {
                $row = array();
                if($schedule->find('tr', $hour_index)) {
                    $td = $schedule->find('tr', $hour_index)->find('td', $day_index);
                    $day = $day_index - 1;
                    $hour = $hour_index - 1;
                    if ($td->find('span', 0)) {
                        
                        // If span in span (two lessons in one hour)
                        if($td->find('span', 0)->find('span', 0)) {
                            $subject = $td->find('span', 0)->find('span', 0)->plaintext;
                            $teacher = $td->find('span', 0)->find('a', 0)->href;
                            if($td->find('span', 0)->find('a', 1))
                                $sala = $td->find('a', 1)->href;
                            $teacher = substr($teacher, 0, strlen($teacher) - 5);
                            $sala = substr($sala, 0, strlen($sala) - 5);
                            $teacher = substr($teacher, 1);
                            $sala = substr($sala, 1);
                            $this->putScheduleRow($class, $day, $hour, $teacher, $subject, $sala);
                            
                            $test_second_lesson = $td->find('span', 1)->find('span', 0)->plaintext;
                            $test_anchor = 1;
                            if($td->find('span', 1)->find('span', 0)) {
                                $subject = $td->find('span', 1)->find('span', 0)->plaintext;
                                $teacher = $td->find('span', 1)->find('a', 0)->href;
                                if($td->find('span', 1)->find('a', 1))
                                    $sala = $td->find('a', 1)->href;
                                $teacher = substr($teacher, 0, strlen($teacher) - 5);
                                $sala = substr($sala, 0, strlen($sala) - 5);
                                $teacher = substr($teacher, 1);
                                $sala = substr($sala, 1);
                                $this->putScheduleRow($class, $day, $hour, $teacher, $subject, $sala);
                            }
                        } else {                          
                            $subject = $td->find('span', 0)->plaintext;
                            $teacher = $td->find('a', 0)->href;
                            if($td->find('a', 1))
                                $sala = $td->find('a', 1)->href;
                            $teacher = substr($teacher, 0, strlen($teacher) - 5);
                            $sala = substr($sala, 0, strlen($sala) - 5);
                            $teacher = substr($teacher, 1);
                            $sala = substr($sala, 1);
                            $this->putScheduleRow($class, $day, $hour, $teacher, $subject, $sala);
                        }
                    } else {
                        $this->putScheduleRow($class, $day, $hour, '', '', '');
                    }
                    
                    if($hour_index == 11 && empty($this->hours_list)) {
                        $this->updateHoursList($link);
                    }
            }
            }
        }
    }
    
    /**
     * Set Hours List
     * @param array $hours
     */
    private function setHoursList($hours) {
        $this->hours_list = $hours;
    }
}