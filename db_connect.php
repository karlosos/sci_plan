<?php

// TODO: tworzenie tabel przy pierwszym wlaczeniu
require 'rb.php';
R::setup('mysql:host=localhost;dbname=plan', 'root', 'osiem');
ini_set('max_execution_time', 1800); //300 seconds = 5 minutes
