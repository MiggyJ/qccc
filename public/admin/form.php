<?php

    session_start();

    include('../../api/_connect.php');
    include('../../api/users/barangay_list.php');

    // Get patient to update if requested 
    if(isset($_GET['id'])){
        $sql = $conn->prepare(
            "EXEC sp_get_one_patient :id"
        );
        $sql->bindParam(':id', $_GET['id']);
        $sql->execute();
        $data = $sql->fetch();
    }
    
    if(!isset($_SESSION['user']))
        header('location: '.getenv('APP_BASE'));

    if(!strcmp($_SESSION['user']->superuser, '1'))
        header('location: '.getenv('APP_BASE').'admin');

    if($_SESSION['user']->forget_token !== null){
        header('location: '.getenv('APP_BASE').'admin/change_password');
    }

    if(isset($_POST['submit'])){
        
            
        if($_POST['submit'] === 'Update'){
            if(
                $data->firstName === $_POST['firstName'] &&
                $data->lastName === $_POST['lastName'] &&
                $data->birthDate === $_POST['birthDate'] &&
                (
                    ($data->address === null && $_POST['address'] === '') ||
                    $data->address === $_POST['address']
                ) &&
                (
                    ($data->contactNumber === null && $_POST['contactNumber'] === '') ||
                    $data->contactNumber === $_POST['contactNumber']
                ) &&
                $data->barangay_address_id === $_POST['barangay'] &&
                $data->status === $_POST['status'] &&
                $data->barangay_admitted_id === $_POST['admitted']
            ){
                echo 'Nothing Changed';
            }else{
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $admitted = $_POST['admitted'];
                $status = $_POST['status'];
                $birthDate = $_POST['birthDate'];
                if($_POST['address'] === '')
                    $address = null;
                else
                    $address = $_POST['address'];
                $_barangay = $_POST['barangay'];
                if($_POST['contactNumber'] === '')
                    $contactNumber = null;
                else
                    $contactNumber = $_POST['contactNumber'];
                $patient = $_POST['patient'];
                // Trigger update if admit location change
                if($data->barangay_admitted_id !== $_POST['admitted']){
                    $updated_column = 1;
                } else {
                    $updated_column = 0;
                }

                $sql = $conn->prepare(
                    "EXEC sp_update_patient
                    :firstName,
                    :lastName,
                    :admitted,
                    :status,
                    :birthDate,
                    :address,
                    :modified,
                    :barangay,
                    :contactNumber,
                    :updated_column,
                    :patient",
                    array(
                        PDO::ATTR_EMULATE_PREPARES => true
                    )
                );
                

                $sql->bindParam(':firstName', $firstName);
                $sql->bindParam(':lastName', $lastName);
                $sql->bindParam(':admitted', $admitted);
                $sql->bindParam(':status', $status);
                $sql->bindParam(':birthDate', $birthDate);
                $sql->bindParam(':address', $address);
                $sql->bindParam(':modified', $modified);
                $sql->bindParam(':barangay', $_barangay);
                $sql->bindParam(':contactNumber', $contactNumber);
                $sql->bindParam(':updated_column', $updated_column);
                $sql->bindParam(':patient', $patient);
                
            }
        }else{

            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $status = $_POST['status'];
            $birthDate = $_POST['birthDate'];
            if($_POST['address'] === '')
                $address = null;
            else
                $address = $_POST['address'];
            $_barangay = $_POST['barangay'];
            if($_POST['contactNumber'] === '')
                $contactNumber = null;
            else
                $contactNumber = $_POST['contactNumber'];
            $patient = $_POST['patient'];

            $sql = $conn->prepare(
                "EXEC sp_new_patient
                    :firstName,
                    :lastName,
                    :admitted,
                    :status,
                    :birthDate,
                    :address,
                    :barangay,
                    :contactNumber
                ",
                array(
                    PDO::ATTR_EMULATE_PREPARES => true
                )
            );
            $sql->bindParam(':firstName', $firstName);
            $sql->bindParam(':lastName', $lastName);
            $sql->bindParam(':admitted', $_SESSION['user']->barangay_id);
            $sql->bindParam(':status', $status);
            $sql->bindParam(':birthDate', $birthDate);
            $sql->bindParam(':address', $address);
            $sql->bindParam(':barangay', $_barangay);
            $sql->bindParam(':contactNumber', $contactNumber);
        }


        if(!$sql->execute())
            print_r($conn->errorInfo());
        else{
            if($_POST['submit'] !== 'Add Another')  
                header("location: ".getenv('APP_BASE')."admin/patient?status=" . $_POST['status']);
            else{
                $_SESSION['form_message'] = 'New Case Added Successfully!';
                header('location: '.getenv('APP_BASE').'admin/form');
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
                                <form action="<?= $_SERVER['REQUEST_URI']?>" method="POST">
                                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                        <div class="h2 mb-0">
                                            Patient Form
                                        </div>
                                        <?php
                                            if(isset($_SESSION['form_message'])){
                                                echo "<div class='alert alert-success my-0 alert-dismissible fade show' role='alert'>
                                                ". $_SESSION['form_message'] ."
                                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                                  <span aria-hidden='true'>&times;</span>
                                                </button>
                                              </div>";

                                              unset($_SESSION['form_message']);
                                            }
                                        ?>
                                    </div>
                                    <div class="card-body">
                                        <h4>Personal Information</h4>
                                        <div class="row mb-4">
                                            <input type="hidden" name="patient" value="<?= !empty($data) ? $data->patient_id : '' ?>">
                                            <div class="col">
                                                <input type="text" required name="firstName" class="form-control" placeholder="First Name" value="<?=!empty($data)?$data->firstName : '' ?>">
                                            </div>
                                            <div class="col">
                                                <input type="text" required name="lastName" class="form-control" placeholder="Last Name" value="<?=!empty($data)?$data->lastName : '' ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <input type="date" required name="birthDate" class="form-control" value="<?=!empty($data) ? $data->birthDate : '' ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <input type="text" name="address" placeholder="Address" class="form-control" value="<?=!empty($data) ?$data->address : '' ?>">
                                                <small class="ml-2 form-text text-muted">Number, Street, Block</small>
                                            </div>
                                            <div class="col-6">
                                                <select data-width="100%" required name="barangay" class="custom-select" id="">
                                                    <option selected disabled value="">Select Barangay</option>
                                                    <?php foreach($barangays as $barangay):?>
                                                        <option <?=!empty($data) &&  $data->barangay_address_id === $barangay->barangay_id ? 'selected' : '' ?> value="<?=$barangay->barangay_id?>">
                                                            <?=$barangay->name?>
                                                        </option>
                                                    <?php endforeach?>
                                                </select>
                                            </div>    
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">+63</div>
                                                    </div>
                                                    <input type="text" name="contactNumber" placeholder="Contact Number" class="form-control" value="<?=!empty($data) ? $data->contactNumber : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <h4>Status Information</h4>
                                        <div class="row mb-4">
                                            <div class="col">
                                                <select data-width="100%" required name="status" class="custom-select">
                                                    <option selected disabled>Status</option>
                                                    <option <?=!empty($data) && $data->status === '1' ? 'selected' : '' ?> value="1">Infected</option>
                                                    <option <?=!empty($data) && $data->status === '2' ? 'selected' : '' ?> value="2">Recovered</option>
                                                    <option <?=!empty($data) && $data->status === '3' ? 'selected' : '' ?> value="3">Dead</option>
                                                </select>
                                            </div>
                                            <?php if(!empty($data)):?>
                                            <div class="col">
                                                <select name="admitted" data-width="100%" class="custom-select">
                                                    <?php foreach($barangays as $barangay):?>
                                                        <option <?=!empty($data) &&  $data->barangay_admitted_id === $barangay->barangay_id ? 'selected' : '' ?> value="<?=$barangay->barangay_id?>">
                                                            <?=$barangay->name?>
                                                        </option>
                                                    <?php endforeach?>
                                                </select>
                                                <small class="ml-2 form-text text-muted">Admitted at</small>
                                            </div>
                                            <?php endif?>
                                        </div>
                                        <hr>
                                        <div class="row justify-content-end">
                                            <div class="col d-flex justify-content-end">
                                                <?php if(!isset($_GET['id'])):?>
                                                <div>
                                                <input type="submit" name="submit" value="Add Another" class="btn btn-outline-primary">
                                                <input type="submit" name="submit" value="Submit" class="ml-1 btn btn-primary">
                                                </div>
                                                <?php else:?>
                                                <div>
                                                <input type="submit" name="submit" value="Update" class="btn btn-primary">
                                                </div>
                                                <?php endif?>
                                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(()=>{$('.custom-select').select2()})

        $('form').submit(()=>{
            $('.btn').addClass('disabled')
        })
    </script>
</body>
</html>