<?php
error_reporting(1); // error ditampilkan
include "database.php";

$uri = 'http://192.168.41.3:8085';

// set uri server
$options = array('uri' => $uri);

// buat objek baru dari class Soap Server
$server = new SoapServer(NULL, $options);

// masukkan class Database ke objek SOAP Server
$server->setClass('Database');

// jalankan menggunakan SOAP requests handler
$server->handle();

// hapus variable dari memory
unset($uri, $options, $server);
?>