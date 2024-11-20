<?php
error_reporting(1);

include "Database.php";
// $abc = new Database;
// var_dump($abc->tampil_semua_data());

$uri = 'http://192.168.41.3:8085';
$option = array('uri'=>$uri);
$server = new SoapServer(NULL,$option);
$server->setClass('Database');
$server->handle();
?>