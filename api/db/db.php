<?php

function db_connect() {
    static $conn = null;
    if ($conn === null) {
        $config = require __DIR__ . '/../config/config.php';
        $conn = pg_connect($config['DB_URL']);
        if (!$conn) {
            die("Database connection failed.");
        }
    }
    return $conn;
}

function db_query($sql) {
    return pg_query(db_connect(), $sql);
}

function db_fetch_all($sql) {
    $result = db_query($sql);
    $rows = [];
    while ($row = pg_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}