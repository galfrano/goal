<?php

$pdo = new \PDO('mysql:host=localhost;dbname=stock', 'stock', 'stock');

$stmt = $pdo->prepare('select * from products');
$stmt->execute();
echo json_encode($stmt->fetchAll(), JSON_PRETTY_PRINT);
