<?php
$host = 'localhost';
$username = 'root';
$password = "";
$dataBase = 'ecommerce';

$conn = mysqli_connect($host, $username, $password, $dataBase);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if database exists
if (!mysqli_select_db($conn, $dataBase)) {
    die("Database '$dataBase' does not exist");
}

// Check if admin_users table exists
$check_table = "SHOW TABLES LIKE 'admin_users'";
$table_result = mysqli_query($conn, $check_table);

if (mysqli_num_rows($table_result) == 0) {
    // Create admin_users table if it doesn't exist
    $create_table = "CREATE TABLE IF NOT EXISTS `admin_users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `image` varchar(255) DEFAULT 'default.jpg',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if (mysqli_query($conn, $create_table)) {
        echo "<!-- Admin users table created successfully -->";
    } else {
        echo "<!-- Error creating table: " . mysqli_error($conn) . " -->";
    }
}
?>