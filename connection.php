<?php
$hostname = "127.0.0.1";
$username = "coglione";
$password = "1234";
$db = "fantagita";

$conn = new mysqli($hostname, $username, $password, $db);

if ($conn->connect_error)
    die("DB failure ".$conn->connect_error);