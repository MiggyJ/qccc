
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();

    
    include('../api/_connect.php');
    if(isset($_POST['submit']) && isset($_GET['mail'])){
        $sql = $conn->prepare(
            "EXEC sp_user_list"
        );
        $sql->execute();
        $data = $sql->fetchAll();

        // Check if email in url exists
        foreach ($data as $row) {
            if(!strcmp(md5($row->email), $_GET['mail'])){
                $user = $row;
                break;
            }
        }

        // If not exists, return unkown, otherwise, generate password and send to email
        if(!isset($user)){
            $_SESSION['form_message'] = 'Email unknown. Please check the link sent to your email.';
        }else{

            $token = $_POST['code'];

            if(!strcmp($token, $user->forget_token)){
                // Generate password
                $temp_pass = substr(hash('md5', uniqid()), 0, 8);
    
                // Hash password
                $password = password_hash($temp_pass, PASSWORD_DEFAULT);
    
                // Update password in database
                $sql =$conn->prepare(
                    "EXEC sp_set_new_password
                        :password,
                        :email"
                );
    
                $sql->bindParam(':password', $password);
                $sql->bindParam(':email', $user->email);
                $sql->execute();
    
                // Alert for email
                $_SESSION['form_success'] = 'An email has been sent.';
    
                require('PHPMailer/PHPMailer.php');
                require('PHPMailer/SMTP.php');
                require('PHPMailer/Exception.php');
    
                // Send email
                $mail = new PHPMailer();
    
                try {
                    //Server settings
                    $mail->SMTPDebug  = 0;                               //Enable verbose debug output
                    $mail->isSMTP();                                     //Send using SMTP
                    $mail->Host       = getenv('SMTP_HOST');              //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                            //Enable SMTP authentication
                    $mail->Username   = getenv('SMTP_USER');                //SMTP username
                    $mail->Password   = getenv('SMTP_PASSWORD');                //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  //Enable TLS encryption;
                    $mail->Port       = getenv('SMTP_PORT');
                
                    //Recipients
                    $mail->setFrom('admin@qccc.gov', 'QCCC Admin');
                    $mail->addAddress($user->email);     //Add a recipient
                
                
                    $body = file_get_contents('templates/password_template.php');
    
                    $body = str_replace(':password', $temp_pass, $body);
    
                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'QCCC - New Password';
                    $mail->Body    = $body;
                    $mail->AltBody = "Here is your new password: $temp_pass.\nYou can change it after logging in.";
                
                    $mail->send();
                    header('location: '.getenv('APP_BASE').'verify');
                    die();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }else{
                $_SESSION['form_message'] = 'Invalid Code. Please check the code sent to your email.';
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
                    <div class="card-header h5">Verify Forget Password Code</div>
                    <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                        <div class="card-body">
                            <?php
                                if(isset($_SESSION['form_message'])){
                                    echo "<div class='text-center alert alert-danger show mb-2' role='alert'>". $_SESSION['form_message'] ."</div>";
                                    unset($_SESSION['form_message']);
                                }
                            ?>
                            <div class="form-group">
                                <label for="">Code</label>
                                <input type="text" name="code" class="form-control">
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
        
    <?php if(isset($_SESSION['form_success'])):?>
    <script>
        alert('<?=$_SESSION['form_success']?>')
        window.location.href = '<?=getenv('APP_BASE')?>'
    </script>
    <?php unset($_SESSION['form_success'])?>
    <?php endif?>
</body>
</html>