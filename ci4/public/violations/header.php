<?php
ob_start();
global $servername;
global $username;
global $password;
global $database;

// $servername = 'localhost';
// $username = 'videoenf_videoenforcement';
// $password = 'Classified123#@!';
// $database = 'videoenf_videoenforcement';
// 'hostname' => 'localhost',
// 'username' => 'root',
// 'password' => '',
// 'database' => 'sfkapp_cmsnew',
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'sfkapp_cms';

include_once('includes/database.php');