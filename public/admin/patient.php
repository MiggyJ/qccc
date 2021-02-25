<?php
    if(!in_array($_GET['status'], ['1','2','3']))
        header('location: '.getenv('APP_BASE').'admin');

    session_start();
    if(!isset($_SESSION['user']))
        header('location: '.getenv('APP_BASE'));

    if($_SESSION['user']->forget_token !== null){
        header('location: '.getenv('APP_BASE').'admin/change_password');
    }

    include('../../api/_connect.php');
    include('../../api/patients/per_barangay.php');

?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>QCCC | Patients</title>
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
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <h1>
                                <?php 
                                    if($_GET['status'] === '1')
                                        echo 'Infected';
                                    else if ($_GET['status'] === '2')
                                        echo 'Recovered';
                                    else
                                        echo 'Dead';
                                ?> Patients
                                </h1>
                                <table class="table table-bordered text-center w-100" style="vertical-align: middle;">
                                    <thead>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Address</th>
                                        <th>Barangay</th>
                                        <th>Contact #</th>
                                        <th>Last Updated</th>
                                        <?php if(strcmp($_SESSION['user']->superuser, '1')):?>
                                        <th>Action</th>
                                        <?php endif?>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data as $row):?>
                                        <?php 
                                        $added = new DateTime($row->added);
                                        $updated = new DateTime($row->updated);
                                        if($updated->format('Y-m-d') === date('Y-m-d'))
                                            $recent = true;
                                        else
                                            $recent = false;   
                                        ?>
                                        <tr>
                                            <td>
                                                <?=$row->name?>
                                                <?php if(!strcmp($added->format('Y-m-d'), date('Y-m-d')) || ($recent && !strcmp($row->updated_column, '1')))
                                                    echo '<span class="badge badge-pill badge-warning">New</span>';
                                                    if($recent && strcmp($row->updated_column, '1'))
                                                    echo '<span class="badge badge-pill badge-info">Updated</span>';
                                                ?>
                                            </td>
                                            <td><?=$row->age?></td>
                                            <td><?=$row->lot_address?></td>
                                            <td><?=$row->barangay_address?></td>
                                            <td><?=$row->contact_number?></td>
                                            <td><?=$row->updated?></td>
                                            <?php if(strcmp($_SESSION['user']->superuser, '1')):?>
                                            <td>
                                                <a href="<?=getenv('APP_BASE')?>/admin/form?id=<?=$row->id?>" class="btn btn-primary">Update</a>
                                            </td>
                                            <?php endif?>
                                        </tr>
                                        <?php endforeach?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                scrollY: 400,
                scrollX:true,
                autoWidth: true,
                scrollCollapse: true,
                order: [[5, 'desc']]
            })
        </script>
    </body>
</html>