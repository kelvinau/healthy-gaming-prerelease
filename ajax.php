<?php
session_start();

if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    require_once(".login-info");

    $conn = new mysqli($SERVER, $USER, $PW, $DB);
    if ($conn->connect_errno) {
        echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
    }
    else {
        $TABLE = 'registration';

        $result = validateInput();
        
        if (count($result)) {
            echo json_encode(["status" => 0, "msg" => $result]);
        }
        else {
            $email = $_POST['email'];
            $name = $_POST['name'];
            $birth_year = $_POST['birth_year'];
            $gender = $_POST['gender'] !== 'others' ? $_POST['gender'] : $_POST['gender_others'];
            $country = $_POST['country'];
            $city = $_POST['city'];

            $stmt = $conn->prepare("SELECT email FROM {$TABLE} WHERE email=?");
            $stmt->bind_param("s", $email);
    
            $result = $stmt->execute();
            $stmt->store_result();    
            
            
            if ($stmt->num_rows > 0) {
                echo json_encode(["status" => 0, "msg" => "Email Existed"]);
            }
            else {
                do {
                    $hash = md5(time());
                    $sql = "SELECT email FROM {$TABLE} WHERE hash='{$hash}'";
                    $result = $conn->query($sql);
                } while($result->num_rows > 0);
    
                $stmt = $conn->prepare("
                INSERT INTO {$TABLE} (email, name, birth_year, gender, country, city, hash) 
                VALUES (?, ?, ?, ?, ?, ?, '{$hash}')
                ");
                $stmt->bind_param("ssisss", $email, $name, $birth_year, $gender, $country, $city);
                
                if ($result = $stmt->execute()) {
                    echo json_encode(["status" => 1, "msg" => "Information submitted"]);
                    unset($_SESSION['csrf_token']);
    
                    $email_msg = "
                        Dear {$name},<br>
                        Thank you for registering on HealthyGaming.info. 
                        If you go to the link here 
                        <a href='https://healthygaming.info/verify.php?hash={$hash}'>https://healthygaming.info/verify.php?hash={$hash}</a> 
                        and verify your email, you will be eligible for a 14-day free trial of premium membership.<br>
                        Warm Regards,<br>
                        Healty Gaming
                    ";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: <contact@healthygaming.info>' . "\r\n";

                    // Send email
                    mail($email, "Thank you for your registration on HealthyGaming.info", $email_msg, $headers);
                }  
                else {
                    //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    echo json_encode(["status" => 0, "msg" => "Error Occurred"]);
                }
            }
    
            $stmt->close();
            $conn->close();    
        }
        
    }
}

function validateInput() {
    $msg = [];
 
    if (!isset($_POST['name']) || !strlen($_POST['name'])) {
        array_push($msg, 'Error on Name');
    }
    if (!isset($_POST['birth_year']) || !filter_var($_POST['birth_year'], FILTER_VALIDATE_INT)) {
        array_push($msg, 'Error on Birth Year');
    }
    if (!isset($_POST['gender']) || !in_array($_POST['gender'], ['male', 'female', 'others'])) {
        array_push($msg, 'Error on Gender');
    }
    if (!isset($_POST['country']) || !strlen($_POST['country'])) {
        array_push($msg, 'Error on Country');
    }
    if (!isset($_POST['city']) || !strlen($_POST['city'])) {
        array_push($msg, 'Error on City');
    }
    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        array_push($msg, 'Error on Email');
    }
    
    return $msg;
}