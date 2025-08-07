<?php

require_once __DIR__ . '/../db/db.php';

/**
 * Insert a new transaction
 */
function insert_transaction($branch_id, $total, $status = 'pending') {
    $sql = "INSERT INTO tu_transaction (branch_id, total, status) VALUES ($1, $2, $3) RETURNING tu_transaction_id";
    $result = db_fetch_all($sql, [$branch_id, $total, $status]);
    return $result[0]['tu_transaction_id'] ?? null;
}

/**
 * Insert an asset into a transaction
 */
function insert_asset_transaction($transaction_id, $asset_id, $quantity, $total) {
    $sql = "INSERT INTO tu_asset_transaction (transaction_id, asset_id, quantity, total) 
            VALUES ($1, $2, $3, $4) 
            ON CONFLICT (transaction_id, asset_id) DO UPDATE 
            SET quantity = EXCLUDED.quantity, total = EXCLUDED.total, updateAt = NOW()";
    return db_query($sql, [$transaction_id, $asset_id, $quantity, $total]);
}

/**
 * Get all transactions for a branch
 */
function get_all_transactions($branch_id) {
    $sql = "SELECT * FROM tu_transaction WHERE branch_id = $1 ORDER BY createdAt DESC";
    return db_fetch_all($sql, [$branch_id]);
}

/**
 * Get transaction detail along with assets
 */
function get_transaction_detail($transaction_id) {
    $sql = "
        SELECT 
            t.tu_transaction_id, 
            t.branch_id,
            t.total AS transaction_total,
            t.status,
            t.createdAt,
            ta.asset_id,
            a.asset_name,
            ta.quantity,
            ta.total AS asset_total
        FROM tu_transaction t
        LEFT JOIN tu_asset_transaction ta ON t.tu_transaction_id = ta.transaction_id
        LEFT JOIN tu_branch_asset a ON ta.asset_id = a.tu_branch_asset_id
        WHERE t.tu_transaction_id = $1
    ";
    return db_fetch_all($sql, [$transaction_id]);
}


function create_transaction($branch_id, $total) {
    $query = "INSERT INTO tu_transaction (branch_id, total, status) VALUES ($1, $2, 'pending') RETURNING tu_transaction_id";
    $result = db_fetch_all($query, [$branch_id, $total]);
    return $result[0]['tu_transaction_id'];
}

function add_asset_to_transaction($transaction_id, $asset_id, $quantity, $total) {
    $query = "INSERT INTO tu_asset_transaction (transaction_id, asset_id, quantity, total) VALUES ($1, $2, $3, $4)";
    $trigger = "UPDATE tu_branch_asset SET quantity = quantity - $2 WHERE tu_branch_asset_id = $1 AND quantity >= $2;";
    db_query($trigger,[$asset_id, $quantity]);
    db_query($query, [$transaction_id, $asset_id, $quantity, $total]);
}