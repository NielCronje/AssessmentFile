<?php


// basic options for PDO 
$dboptions = array(
    PDO::ATTR_PERSISTENT => FALSE,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

//connect with the server
try {
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="cognitionholdingsassessment";
    $DB = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);  
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $DB;
} catch (Exception $ex) {
    echo errorMessage($ex->getMessage());
    die;
}



