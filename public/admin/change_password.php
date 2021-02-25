<?php

    session_start();

    include('../../api/_connect.php');
    
    if(!isset($_SESSION['user']))
        header('location: '.getenv('APP_BASE').'admin');

    
    if(isset($_POST['submit'])){
        // Offer to change password
        if($_POST['submit'] === 'No, Thanks'){
            $sql = $conn->prepare(
                "EXEC sp_set_forgot_token null, :email"
            );
            $email = $_SESSION['user']->email;
            $sql->bindParam(':email', $email);
            $sql->execute();
            $_SESSION['user']->forget_token = null;
            header('location: '.getenv('APP_BASE').'admin');
        }else{
            if(!strcmp($_POST['password'], $_POST['password2']) && strlen($_POST['password'] > 8)){
                $sql = $conn->prepare(
                    "EXEC sp_set_new_password
                        :password,
                        :email"
                );
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $email = $_SESSION['user']->email;
                $sql->bindParam(':password', $password);
                $sql->bindParam(':email', $email);
                if($sql->execute()){
                    $_SESSION['user']->forget_token = null;
                    $_SESSION['form_success'] = 'Password Saved';
                    header('location: '.getenv('APP_BASE').'admin/change_password');
                }else{
                    print_r($conn->errorInfo());
                }
            }else{
                $_SESSION['form_message'] = 'Passwords need to match and be at least 8 characters.';
                header('location: '.getenv('APP_BASE').'admin/change_password');
                die();
            }

        }
    }

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCCC | Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Squada+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <style>
        *{
            font-family: 'Quicksand';
        }
        html{
            scroll-behavior: smooth;
        }
        .col, #sidebar{
            transition: all 0.7s ease;
        }
        h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6{
            font-family: 'Squada One'
        }
        .squada{
            font-family: 'Squada One';
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div id="sidebar" class="col-lg-1 d-none d-lg-block position-fixed">
                <?php include('templates/sidebar.php')?>
            </div>
            <div class="col offset-lg-1" id="main">

                <?php include('templates/navbar.php')?>
                <div class="container">
                    <div class="row mt-5">
                    <div class="col-lg-4 offset-lg-4 col-sm-6 offset-sm-3">
                        <div class="card">
                            <div class="card-header h3">Change Password</div>
                            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                                <div class="card-body">
                                    <?php
                                        if(isset($_SESSION['form_message'])){
                                            echo "<div class='text-center alert alert-danger show mb-2' role='alert'>". $_SESSION['form_message'] ."</div>";
                                            unset($_SESSION['form_message']);
                                        }
                                        if(isset($_SESSION['form_success'])){
                                            echo "<div class='text-center alert alert-success show mb-2' role='alert'>". $_SESSION['form_success'] ."</div>";
                                            unset($_SESSION['form_success']);
                                        }
                                    ?>
                                    <div class="form-group">
                                        <label for="">New Password</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Confirm New Password</label>
                                        <input type="password" name="password2" class="form-control">
                                    </div>
                                    <div class="d-flex justify-content-end mt-5">
                                        <input type="submit" name="submit" class="btn btn-link mr-2" value="No, Thanks">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Save">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>