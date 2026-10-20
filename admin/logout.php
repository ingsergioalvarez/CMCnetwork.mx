<?php
require __DIR__ . '/_inc.php';
$_SESSION = [];
session_destroy();
redirigir('/admin/login.php');
