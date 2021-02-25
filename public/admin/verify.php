<?php

    include('../../api/_connect.php');

    if(!isset($_GET['token']) || !isset($_GET['mail']))
        header('location: '.getenv('APP_BASE'));

    $verify = $_GET['token'];
    $email = $_GET['mail'];

    // Check if verify token exists
    $sql = $conn->prepare(
        "EXEC sp_get_verify_token :verify"
    );

    $sql->bindParam(':verify', $verify);
    $sql->execute();

    $data = $sql->fetch();

    // If email exists and is not yet verified
    if($data && $data->email_verified_at === null){
        if(!strcmp(md5($data->email), $email)){
            $sql = $conn->prepare("EXEC sp_verify_user :email");
            $sql->bindParam(':email', $data->email);
            $sql->execute();
            $verified = true;
        }else{
            $verified = false;
        }
    }else
        $verified = null;

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCCC Admin | Verify</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Squada+One&display=swap" rel="stylesheet">
    <style>
        *{
            font-family: 'Squada One';
        }
        html{
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row d-flex align-items-center" style="height: 100vh; width=100vw">
            <div class="jumbotron col">
                <center>
                    <img src="../assets/logo.png" height="200" alt="">
                    <div class="mt-3 h2">
                    <?php if($verified === true):?>
                        Your email is now verified. Please log in at <a href="<?=getenv('APP_BASE')?>">homepage</a>
                    <?php elseif($verified === false): ?>
                        The verification token is not for your email or the email is already verified. Redirecting you to <a href="<?=getenv('APP_BASE')?>">homepage</a>.
                    <?php else:?>
                    Unkown verification token. Redirecting you to <a href="<?=getenv('APP_BASE')?>">homepage</a>.
                    <?php endif;?>
                    </div>
                </center>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = '<?=getenv('APP_BASE')?>'
        }, 5000);
    </script>
</body>
</html>