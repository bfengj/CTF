<?php
try {
  $pdo = new PDO('mysql:host=db;dbname='.getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'), [PDO::MYSQL_ATTR_MULTI_STATEMENTS => false]);
} catch (PDOException $e) {
    die($e->getMessage());
}
