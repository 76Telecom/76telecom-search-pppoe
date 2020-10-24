<?php

/*
 * Author: Caio Agiani
 * Website: 76Telecom
 * Description: pPPoE automatic generator
*/ 

// header('Content-Type: application/json');
date_default_timezone_set('America/Sao_Paulo');
set_time_limit(0);

// $get  = extract($_GET);
// $post = extract($_POST);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
}
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    extract($_GET);
}

require "app/view/api.php";

