<?php
error_reporting(E_ALL); // Enable error reporting for debugging
// Set the path for the error log

include "Database.php"; // Include the database connection class

// Define the URI for the SOAP server
$uri = 'http://localhost:8085/soap/'; // Update this if your location changes

// Set options for the SOAP server
$options = array('uri' => $uri);

// Create a new SoapServer instance
$server = new SoapServer(NULL, $options);

// Set the class to handle requests
$server->setClass('Database');

// Use output buffering to suppress any unwanted output
ob_start();

// Handle the SOAP requests
$server->handle();

// Clean the output buffer
ob_end_clean();
