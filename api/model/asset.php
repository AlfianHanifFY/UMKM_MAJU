<?php

require_once __DIR__ . '/../db/db.php';

/**
 * Ambil semua asset milik suatu cabang
 */
function get_branch_asset($branch_id){
    $sql = "SELECT * FROM tu_branch_asset WHERE branch_id = $1 ORDER BY createdAt DESC";
    $result = db_fetch_all($sql, [$branch_id]);
    return $result;
}

/**
 * Ambil satu asset berdasarkan ID-nya
 */
function get_branch_asset_by_id($asset_id){
    $sql = "SELECT * FROM tu_branch_asset WHERE tu_branch_asset_id = $1";
    $result = db_fetch_all($sql, [$asset_id]);
    return $result[0] ?? null;
}

/**
 * Tambah asset baru ke suatu cabang
 */
function add_branch_asset($branch_id, $name, $quantity, $buy_price, $sell_price, $fund_type, $description, $status, $photo){
    $sql = "INSERT INTO tu_branch_asset 
        (branch_id, name, quantity, buy_price, sell_price, fund_type, description, status, photo) 
        VALUES 
        ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
    
    $result = db_query($sql, [$branch_id, $name, $quantity, $buy_price, $sell_price, $fund_type, $description, $status, $photo]);

    if (!$result) {
        throw new Exception("Gagal menambahkan asset: " . pg_last_error(db_connect()));
    }
    return true;
}

/**
 * Perbarui data asset
 */
function update_branch_asset($asset_id, $name, $quantity, $buy_price, $sell_price, $fund_type, $description, $status, $photo){
    $sql = "UPDATE tu_branch_asset 
        SET name = $2, quantity = $3, buy_price = $4, sell_price = $5, 
            fund_type = $6, description = $7, status = $8, photo = $9, updateAt = NOW() 
        WHERE tu_branch_asset_id = $1";
    
    $result = db_query($sql, [$asset_id, $name, $quantity, $buy_price, $sell_price, $fund_type, $description, $status, $photo]);

    if (!$result) {
        throw new Exception("Gagal memperbarui asset: " . pg_last_error(db_connect()));
    }
    return true;
}

/**
 * Hapus asset
 */
function delete_branch_asset($asset_id){
    $sql = "DELETE FROM tu_branch_asset WHERE tu_branch_asset_id = $1";
    $result = db_query($sql, [$asset_id]);

    if (!$result) {
        throw new Exception("Gagal menghapus asset: " . pg_last_error(db_connect()));
    }
    return true;
}

/**
 * Ubah status aktif/nonaktif asset
 */
function toggle_branch_asset_status($asset_id, $new_status){
    $sql = "UPDATE tu_branch_asset SET status = $2, updateAt = NOW() WHERE tu_branch_asset_id = $1";
    $result = db_query($sql, [$asset_id, $new_status]);

    if (!$result) {
        throw new Exception("Gagal mengubah status asset: " . pg_last_error(db_connect()));
    }
    return true;
}

/**
 * Update hanya quantity asset
 */
function update_asset_quantity($asset_id, $new_quantity){
    $sql = "UPDATE tu_branch_asset SET quantity = $2, updateAt = NOW() WHERE tu_branch_asset_id = $1";
    $result = db_query($sql, [$asset_id, $new_quantity]);

    if (!$result) {
        throw new Exception("Gagal mengubah jumlah asset: " . pg_last_error(db_connect()));
    }
    return true;
}