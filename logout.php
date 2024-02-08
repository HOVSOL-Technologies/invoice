<?php
session_start();
require_once 'lib/LoginClass.php';

$user = new LoginClass();
$user->logout();
