<?php

    session_start();
    

    include('../../api/_connect.php');
    include('../../api/users/barangay_list.php');
    
    if(!isset($_SESSION['user']))
        header('location: '.getenv('APP_BASE'));

    if($_SESSION['user']->forget_token !== null){
        header('location: '.getenv('APP_BASE').'admin/change_password');
    }

    if(isset($_POST['submit'])){

        // Generate password
        $temp_pass = substr(hash('md5', uniqid()), 0, 8);
        // Hash Password
        $password = password_hash($temp_pass, PASSWORD_DEFAULT);
        $barangay = $_POST['barangay'];
        $email = $_POST['email'];
        $token = hash('sha3-256', uniqid());

        try{
            $sql = $conn->prepare(
                "EXEC sp_new_user
                    :barangay,
                    :email,
                    :password,
                    :token"
            );
    
            $sql->bindParam(':barangay', $barangay);
            $sql->bindParam(':email', $email);
            $sql->bindParam(':password', $password);
            $sql->bindParam(':token', $token);
    
            // Send Mail if successful
            $sql->execute();
            header("location: ".getenv('APP_BASE')."admin/mail?email=$email&auth=$temp_pass&token=$token");
        }catch(Exception $e){
            $_SESSION['form_message'] = 'The email you entered is already registered.';
            header('location: '.getenv('APP_BASE').'admin/new');
            die();
        }
        
    }
    


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCCC | New Admin</title>
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
        .select2-container--default .select2-selection--single, .select2-search__field{
            border: 1px solid #cde4da;
            height: calc(1.5em + .75rem + 2px);
        }
        .select2-selection:focus, .select2-search__field:focus{
            outline: none;
        }
        .select2-selection__rendered {
        line-height: 1.5 !important;
        padding: 0.375rem 0.75rem;
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
                    <div class="row mt-2">
                        <div class="col-sm-6 offset-sm-3">
                            <div class="card">
                                <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                        <div class="h2 mb-0">
                                            New Admin
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                            if(isset($_SESSION['form_message'])){
                                                echo "<div class='alert alert-danger mx-2 my-0 alert-dismissible fade show text-center' role='alert'>
                                                ". $_SESSION['form_message'] ."
                                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                                  <span aria-hidden='true'>&times;</span>
                                                </button>
                                              </div>";
                                              unset($_SESSION['form_message']);
                                            }
                                        ?>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" required class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Barangay</label>
                                            <select data-width="100%" required name="barangay" class="custom-select" id="">
                                                <option selected disabled value="">Select Barangay</option>
                                                <?php foreach($barangays as $barangay):?>
                                                    <option value="<?=$barangay->barangay_id?>">
                                                        <?=$barangay->name?>
                                                    </option>
                                                <?php endforeach?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <input type="submit" name="submit" value="Register" class="btn btn-block btn-primary">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(()=>{$('.custom-select').select2()})

        $('form').submit(()=>{
            $('.btn').addClass('disabled')
        })
    </script>
</body>
</html>