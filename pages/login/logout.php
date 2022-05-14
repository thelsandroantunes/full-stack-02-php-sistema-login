<?php
session_start();
session_unset();
session_destroy();

$path = '../../index.php';
header("location: $path");

?>