<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'test';

$link = mysqli_connect($host, $user, $password, $database) or die(mysqli_error($link));

mysqli_query($link, "SET NAMES 'utf8'");
