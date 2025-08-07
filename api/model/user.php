<?php
// user.php - Fixed version
require_once __DIR__ . '/../db/db.php';

function add_user($email, $password, $name) {
  
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO tu_user (email, password, display_name) VALUES ($1, $2, $3)";
    $result = db_query($sql, [$email, $hashed_password, $name]);

    if (!$result) {
        throw new Exception("Gagal menambahkan pengguna: " . pg_last_error(db_connect()));
    }
    return $result;
}

function validate_user($email, $password) {
    $sql = "SELECT * FROM tu_user WHERE email = $1";
    $result = db_fetch_all($sql, [$email]);

    // Jika tidak ditemukan user
    if (empty($result)) {
        return false;
    }

    $user = $result[0];

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Login sukses → bisa return data user atau true
        return $user;
    }

    // Password salah
    return false;
}

function get_user($token){
    $sql = "SELECT * FROM tu_user WHERE tu_users_id = $1";
    $result = db_fetch_all($sql, [$token]);

    $user = $result[0];
    return $user;
}