
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();


    include('../api/_connect.php');
    if(isset($_POST['submit'])){
        $sql = $conn->prepare(
            "SELECT * FROM users WHERE email = :email"
        );
        $sql->bindParam(':email', $_POST['email']);
        $sql->execute();
        $data = $sql->fetch();

        // Check if email exists
        if(!$data){
            $_SESSION['form_message'] = 'Email unknown.';
            header('location: '.getenv('APP_BASE').'forgot_password');
            die();
        }else{
            
            // Create a token for verification of email
            $token = substr(hash('sha512', $_POST['email'] . uniqid()), 0, 6);

            // Insert token to database
            $sql =$conn->prepare(
                "EXEC sp_set_forgot_token
                    :token,
                    :email"
            );

            $sql->bindParam(':token', $token);
            $sql->bindParam(':email', $data->email);
            $sql->execute();

            // Alert for the email
            $_SESSION['form_success'] = 'An email has been sent.';

            require('PHPMailer/PHPMailer.php');
            require('PHPMailer/SMTP.php');
            require('PHPMailer/Exception.php');

            $mail = new PHPMailer();

            // Send Email
            try {
                //Server settings
                $mail->SMTPDebug = 0;                                //Enable verbose debug output
                $mail->isSMTP();                                     //Send using SMTP
                $mail->Host       = getenv('SMTP_HOST');              //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                            //Enable SMTP authentication
                $mail->Username   = getenv('SMTP_USER');                //SMTP username
                $mail->Password   = getenv('SMTP_PASSWORD');                //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  //Enable TLS encryption;
                $mail->Port       = getenv('SMTP_PORT');                  
            
                //Recipients
                $mail->setFrom('admin@qccc.gov', 'QCCC Admin');
                $mail->addAddress($_POST['email']);     //Add a recipient
            
            
                $body = file_get_contents('templates/email_template.php');

                $body = str_replace(
                    ':link', 
                    "http://localhost/qccc/public/verify?mail=" . md5($_POST['email']), 
                    $body
                );
                $body = str_replace(':code', $token, $body);

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'QCCC - Forgot Password';
                $mail->Body    = $body;
                $mail->AltBody = "Please enter the following code in the this link http://localhost/qccc/public/verify?mail=" . md5($_POST['email']. "\nCode: $token");
            
                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
    
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCCC | Forgot Password</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="shortcut icon" href="assets/logo.png" type="image/x-icon">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar">
        <a class="navbar-brand" href="<?=getenv('APP_BASE')?>">
            <img src="./assets/brand.png" height="50" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav nav-pills ml-auto mr-3">
            <li class="nav-item">
                <a class="nav-link" href="<?=getenv('APP_BASE')?>">Home</a>
            </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-4 offset-lg-4 col-sm-6 offset-sm-3">
                <div class="card">
                    <div class="card-header h3">Forgot Password</div>
                    <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                        <div class="card-body">
                            <?php
                                if(isset($_SESSION['form_message'])){
                                    echo "<div class='text-center alert alert-danger show mb-2' role='alert'>". $_SESSION['form_message'] ."</div>";
                                    unset($_SESSION['form_message']);
                                }
                            ?>
                            <?php
                                if(isset($_SESSION['form_success'])){
                                    echo "<div class='text-center alert alert-success show mb-2' role='alert'>". $_SESSION['form_success'] ."</div>";
                                    unset($_SESSION['form_success']);
                                }
                            ?>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="d-flex justify-content-end mt-5">
                                <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>