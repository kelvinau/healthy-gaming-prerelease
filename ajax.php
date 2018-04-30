<?php
session_start();

if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    // after email is verified
    require_once(".login-info");

    $conn = new mysqli($SERVER, $USER, $PW, $DB);
    if ($conn->connect_errno) {
        echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
    }
    else {
        $email = 'test4@hotmail.com';
        $name = 'test user';
        $birth_year = 1990;
        $gender = 'Male';
        $country = 'Taiwan';
        $city = 'Taipei';
    
        $stmt = $conn->prepare("INSERT INTO {$TABLE} VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $email, $name, $birth_year, $gender, $country, $city);
        ;
        if ($result = $stmt->execute()) {
            echo 'inserted';
            unset($_SESSION['csrf_token']);
        }  
        else {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->close();
        $conn->close();    
    }
}