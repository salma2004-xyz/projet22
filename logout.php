<?php
session_start();
require_once 'inc/db.php';

logoutUser();
header('Location: index.php');
exit;
?>
