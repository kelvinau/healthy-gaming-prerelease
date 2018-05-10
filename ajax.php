<?php
session_start();

if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    require_once("../../login_info/pr.php");

    $conn = new mysqli($SERVER, $USER, $PW, $DB);
    if ($conn->connect_errno) {
        //echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
        echo "Failed to connect to the database";
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
            $gender = $_POST['gender'];
            $country = $_POST['country'];

            $stmt = $conn->prepare("SELECT email FROM {$TABLE} WHERE email=?");
            $stmt->bind_param("s", $email);
    
            $result = $stmt->execute();
            $stmt->store_result();    
                  
            if ($stmt->num_rows > 0) {
                echo json_encode(["status" => 0, "msg" => "This email already exists."]);
            }
            else {
                do {
                    $hash = md5(time());
                    $sql = "SELECT email FROM {$TABLE} WHERE hash='{$hash}'";
                    $result = $conn->query($sql);
                } while($result->num_rows > 0);
    
                $stmt = $conn->prepare("
                INSERT INTO {$TABLE} (email, name, birth_year, gender, country, hash) 
                VALUES (?, ?, ?, ?, ?, '{$hash}')
                ");
                $stmt->bind_param("ssiss", $email, $name, $birth_year, $gender, $country);
                
                if ($result = $stmt->execute()) {
                    echo json_encode(["status" => 1, "msg" => 
                    "Thank you for registering your interest. To complete the registration, please check your inbox and verify your email address."]);
                    unset($_SESSION['csrf_token']);
    
                    $email_msg = "
                        <div style='text-align:center;'>
                            <img src='https://healthygaming.info/image/logo.png'>
                        </div>
                        Dear {$name},<br><br>

                        Thank you for registering on HealthyGaming.info. Please <a href='https://healthygaming.info/?hash={$hash}'>click here</a> 
                        to verify your email address.<br><br>
                        
                        Once your email has been verified, you will become eligible for a 14-day free trial of Premium Membership upon release.<br><br>

                        Warm Regards,<br>
                        Healthygaming<br><br><br><br>

                        <div style='text-align: center;'>
                            <div>Healthygaming<sup>TM</sup></div>
                            <div>Registered in Sweden.</div>
                            <div>Registration No. 2018/02709.</div>
                        </div>
                    ";
                    
                    //$headers = "From: Healthy Gaming <contact@healthygaming.info> \r\n";
                    $headers = "Reply-to: contact@healthygaming.info\r\n";
                    $headers .= "Bcc: contact@healthygaming.info\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                    // Send email
                    mail($email, "Verification", $email_msg, $headers);
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
    if (!isset($_POST['gender']) || !in_array($_POST['gender'], ['male', 'female', 'private'])) {
        array_push($msg, 'Error on Gender');
    }
    if (!isset($_POST['country']) || !strlen($_POST['country'])) {
        array_push($msg, 'Error on Country');
    }
    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        array_push($msg, 'Error on Email');
    }
    
    return $msg;
}