<?php

/*** mysql hostname ***/
$hostname = 'localhost';

/*** mysql username ***/
//$username = 'ultratec_nav';
$username = 'root';

/*** mysql password ***/
// $password = 'Admin@$123#';
$password = '';

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=ultratec_navratri2", $username, $password)or die(print_r($db->errorInfo(), true));
    /*** echo a message saying we have connected ***/
    //echo 'Connected to database';
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }

//print_r($dbh->errorInfo());
