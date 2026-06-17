<?php
// config/database.php

define('DB_HOST',     'localhost');
define('DB_USER',     'root');
define('DB_PASS',     '');           // kosongkan jika XAMPP default
define('DB_NAME',     'ruanglab');
define('DB_CHARSET',  'utf8mb4');

function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset(DB_CHARSET);

    if ($conn->connect_error) {
        die(json_encode([
            'status'  => 'error',
            'message' => 'Koneksi database gagal: ' . $conn->connect_error
        ]));
    }

    return $conn;
}
