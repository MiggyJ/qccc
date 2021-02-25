<?php

    session_start();

    if($_SESSION['user']->superuser === 0)
        header('location: '.getenv('APP_BASE').'admin');

    if(!isset($_SESSION['user']))
        header('location: '.getenv('APP_BASE'));

    if($_SESSION['user']->forget_token !== null){
        header('location: '.getenv('APP_BASE').'admin/change_password');
    }

    include('../../api/_connect.php');
    include('../../api/users/user_list.php');


    if(isset($_POST['submit'])){
        // Update user to be active or disabled
        if($_POST['submit'] === 'Disable')
            $active = 0;
        else
            $active = 1;

        $sql = $conn->prepare(
            "EXEC sp_update_users
                :active,
                :email"
        );
        $sql->bindParam(':active', $active);
        $sql->bindParam(':email', $_POST['user']);
        $sql->execute();

        header('location: '.getenv('APP_BASE').'admin/users');
        die();
    }

?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>QCCC | Users</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Squada+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
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
            .quicksand{
                font-family: 'Quicksand';
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
                    <div class="container mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1>
                                Users List
                            </h1>
                            <a href="<?=getenv('APP_BASE')?>admin/new" class="btn btn-primary">New User <i class="bi bi-plus" style="font-size: 1.5em;"></i></a>
                        </div>
                        <table class="table table-bordered text-center" style="vertical-align: middle; width: 100% !important;">
                            <thead>
                                <th>Email</th>
                                <th>Barangay</th>
                                <th>Verified</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php foreach($users as $row):?>
                                <tr>
                                    <td><?=$row->email?></td>
                                    <td><?=$row->barangay?></td>
                                    <td><?=$row->verified === null ? 'NO' : 'YES' ?></td>
                                    <td>
                                        <?php if(!strcmp($row->active, '1')):?>
                                        <form method="POST">
                                            <input type="hidden" name="user" value="<?=$row->email?>">
                                            <input type="submit" class="btn btn-danger" name="submit" value="Disable">
                                        </form>
                                        <?php else:?>
                                        <form method="POST">
                                            <input type="hidden" name="user" value="<?=$row->email?>">
                                            <input type="submit" class="btn btn-info" name="submit" value="Activate">
                                        </form>
                                        <?php endif?>
                                    </td>
                                </tr>
                                <?php endforeach?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $('.table').DataTable({
                scrollY: 360,
                scrollX: true,
                scrollCollapse: true
            })

            $('form').submit(()=>{
                return confirm('Activate/Disable this user?')
            })
        </script>
    </body>
</html>