<?php

$config = require __DIR__ . '/config/config.php';

$dbUrl = $config["DB_URL"];

$conn = pg_connect($dbUrl);

if (!$conn) {
    echo "An error occurred.\n";
    exit;
}

$result = pg_query($conn, "SELECT * FROM shuttle");
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
}
?>