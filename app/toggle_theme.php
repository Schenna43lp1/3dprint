<?php require 'auth.php';
$_SESSION['theme']=($_SESSION['theme'] ?? 'light')==='light' ? 'dark':'light';
header('Location:' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;