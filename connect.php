<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marathon";

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed! " . $conn->connect_error);
}

// Tạo cơ sở dữ liệu chính
$sql = "CREATE DATABASE IF NOT EXISTS marathon";
if ($conn->query($sql) === TRUE) {
    echo "Primary database created successfully!\n";
} else {
    echo "Error creating main database! " . $conn->error . "\n";
}

// Chọn cơ sở dữ liệu chính
$conn->select_db("marathon");

//------------------------------------------
// Tạo cơ sở dữ liệu con (ví dụ: user)
$sql = "CREATE DATABASE IF NOT EXISTS user";
if ($conn->query($sql) === TRUE) {
    echo "Created child database (user) successfully\n";
} else {
    echo "Error creating child database! " . $conn->error . "\n";
}

// Chọn cơ sở dữ liệu con
$conn->select_db("user");

// Tạo bảng participants trong cơ sở dữ liệu con
$sql = "CREATE TABLE IF NOT EXISTS participants (
    user_id INT NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    nationality VARCHAR(50) NOT NULL,
    passport_no INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    age INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone_number INT NOT NULL,
    address TEXT NOT NULL,
    competition VARCHAR(100) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Successfully created participants table\n";
} else {
    echo "Error when creating participants table!" . $conn->error . "\n";
}

//------------------------------------------
// Tạo cơ sở dữ liệu con (ví dụ: event)
$sql = "CREATE DATABASE IF NOT EXISTS event";
if ($conn->query($sql) === TRUE) {
    echo "Created child database (event) successfully\n";
} else {
    echo "Error creating child database! " . $conn->error . "\n";
}

// Chọn cơ sở dữ liệu con
$conn->select_db("event");

// Tạo bảng participants trong cơ sở dữ liệu con
$sql = "CREATE TABLE IF NOT EXISTS participants (
    marathon_ID INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    distance INT NOT NULL,
    registration_deadline DATE NOT NULL,
    competition_day DATE NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Successfully created participants table\n";
} else {
    echo "Error creating participants table: " . $conn->error . "\n";}

//------------------------------------------
// Tạo cơ sở dữ liệu con (ví dụ: achievements)
$sql = "CREATE DATABASE IF NOT EXISTS achievements";
if ($conn->query($sql) === TRUE) {
    echo "Created child database (achievements) successfully\n";
} else {
    echo "Error creating child database! " . $conn->error . "\n";
}

// Chọn cơ sở dữ liệu con
$conn->select_db("achievements");

// Tạo bảng participants trong cơ sở dữ liệu con
$sql = "CREATE TABLE IF NOT EXISTS participants (
    marathon_ID INT NOT NULL,
    user_id INT NOT NULL,
    time_record VARCHAR(255) NOT NULL,
    standings INT NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Successfully created participants table\n";
} else {
    echo "Error creating participants table: " . $conn->error . "\n";}

// Đóng kết nối
$conn->close();
?>