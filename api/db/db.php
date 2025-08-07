<?php
function db_connect() {
    static $conn = null;
    if ($conn === null) {
        try {
            $config = require __DIR__ . '/../config/config.php';
            $conn = pg_connect($config['DB_URL']);
            if (!$conn) {
                throw new Exception("Database connection failed: " . pg_last_error());
            }
        } catch (Exception $e) {
            error_log("DB Connection Error: " . $e->getMessage());
            die("Database connection failed.");
        }
    }
    return $conn;
}

function db_query($sql, $param = []) {
    try {
        $conn = db_connect();
        if (empty($param)) {
            $result = pg_query($conn, $sql);
        } else {
            $result = pg_query_params($conn, $sql, $param);
        }
        
        if (!$result) {
            throw new Exception("Query failed: " . pg_last_error($conn));
        }
        
        return $result;
    } catch (Exception $e) {
        error_log("DB Query Error: " . $e->getMessage());
        throw $e;
    }
}

function db_fetch_all($sql, $param = []) {
    $result = db_query($sql, $param);
    $rows = [];
    while ($row = pg_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}