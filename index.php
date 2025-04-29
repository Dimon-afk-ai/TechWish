<?php
session_start();
require_once __DIR__.'/boot.php';

include ('models/header.php');

// Передаем аватар в HTML
$GLOBALS['current_avatar'] = $avatar;




include 'views/index.html';
?>