<?php

    session_start();
    if (!isset($_SESSION['user'])) {
        header('location: '.getenv('APP_BASE'));
    }
    session_unset();
    session_destroy();
    
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCCC Admin | Log Out</title>
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
                        You have been logged out. Redirecting you to <a href="<?=getenv('APP_BASE')?>">homepage</a>
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