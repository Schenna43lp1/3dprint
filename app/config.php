<?php
$pdo = new PDO(
    "pgsql:host=db;port=5432;dbname=printtracker",
    "printuser",
    "printpass123"
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>