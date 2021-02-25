<?php

    session_start();

    if(!isset($_SESSION['user']))
        header('location: '.getenv('APP_BASE'));

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require('../PHPMailer/PHPMailer.php');
    require('../PHPMailer/SMTP.php');
    require('../PHPMailer/Exception.php');

    $mail = new PHPMailer();

    // Send Mail
    try {
        //Server settings
        $mail->SMTPDebug  = 0;                                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = getenv('SMTP_HOST');                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = getenv('SMTP_USER');                       //SMTP username
        $mail->Password   = getenv('SMTP_PASSWORD');                       //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption;
        $mail->Port       = getenv('SMTP_PORT');    
    
        //Recipients
        $mail->setFrom('admin@qccc.gov', 'QCCC Admin');
        $mail->addAddress($_GET['email']);     //Add a recipient
    
    
        $body = file_get_contents('templates/email_template.php');

        $body = str_replace(
            ':link', 
            getenv('APP_BASE')."admin/verify?token=".$_GET['token']."&mail=" . md5($_GET['email']), 
            $body
        );
        $body = str_replace(':password', $_GET['auth'], $body);

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Verify Admin Email';
        $mail->Body    = $body;
        $mail->AltBody = "This email has been registered as admin at Quezon City Covid-19 Census website. Verify this email by visiting ".getenv('APP_BASE')."admin/verify?token=".$_GET['token']."&mail=" . md5($_GET['email']);
    
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    header('location: '.getenv('APP_BASE').'admin/users');


?>