<?php

require_once __DIR__ . '/../db/db.php';


function get_branch($token){
    $sql = "SELECT * FROM tu_user_branch WHERE user_id = $1";
    $result = db_fetch_all($sql,[$token]);
    return $result;
}

function get_branch_by_id($id){
    $sql = "SELECT * FROM tu_user_branch WHERE tu_user_branch_id = $1";
    $result = db_fetch_all($sql,[$id]);
    return $result[0];
}


function add_branch($token, $name, $address, $description, $status){
    $sql = "INSERT INTO tu_user_branch (user_id, branch_name, branch_address, branch_description, status) VALUES ($1, $2, $3, $4, $5)";
    $result = db_query($sql, [$token, $name, $address, $description, $status]);
    
    if (!$result) {
        throw new Exception("Gagal menambahkan pengguna: " . pg_last_error(db_connect()));
    }
    return $result;
}

function delete_branch($branch_id) {
    
    $sql = "DELETE FROM tu_user_branch WHERE tu_user_branch_id = $1";
    $result = db_query($sql, [$branch_id]);
    
    if (!$result) {
        throw new Exception("Gagal menghapus cabang: " . pg_last_error(db_connect()));
    }
    return true;
}

function update_branch($branch_id, $name, $address, $description, $status) {
    
    $sql = "UPDATE tu_user_branch SET branch_name = $2, branch_address = $3, branch_description = $4, status = $5 WHERE tu_user_branch_id = $1";
    $result = db_query($sql, [$branch_id, $name, $address, $description, $status]);
    
    if (!$result) {
        throw new Exception("Gagal memperbarui cabang: " . pg_last_error(db_connect()));
    }
    return true;
}