<?php
require_once __DIR__ . '/../db/db.php';

function get_all_shuttle() {
    $sql = "SELECT * FROM shuttle";
    return db_fetch_all($sql);
}