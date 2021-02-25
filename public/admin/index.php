<?php

    session_start();
    if(!isset($_SESSION['user']))
        header('location: '.getenv('APP_BASE'));

    // If new user, offer to change password
    if($_SESSION['user']->forget_token !== null){
        header('location: '.getenv('APP_BASE').'admin/change_password');
    }

    // Functions
    include('../../api/_connect.php');
    include('../../api/users/barangay_count.php');
    include('../../api/users/trend_chart.php');
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCCC | Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Squada+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <style>
        *{
            font-family: 'Quicksand';
        }
        html{
            scroll-behavior: smooth;
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
        #sidebar, .col{
            transition: all 0.7s ease;
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
                        <div class="col">
                            <div class="h1">Dashboard - <?=strcmp($_SESSION['user']->superuser, '1') ? $_SESSION['user']->barangay_name : 'Admin'?></div>
                        </div>
                    </div>
                    <!-- Tally -->
                    <div class="row">
                        <div class="col">
                            <div class="card shadow rounded-pill">
                                <div class="card-body text-center">
                                    <div class="card-title h3">Total Cases</div>
                                    <div class="h1 quicksand"><?=$total?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <a href="<?=getenv('APP_BASE')?>admin/patient?status=1" class="text-decoration-none">
                                <div class="card shadow rounded-pill">
                                    <div class="card-body text-center">
                                        <div class="card-title h3">Infected</div>
                                        <div class="h1 quicksand"><?=$infected?></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="<?=getenv('APP_BASE')?>admin/patient?status=2" class="text-decoration-none">
                                <div class="card shadow rounded-pill">
                                    <div class="card-body text-center">
                                        <div class="card-title h3">Recovered</div>
                                        <div class="h1 quicksand"><?=$recovered?></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="<?=getenv('APP_BASE')?>admin/patient?status=3" class="text-decoration-none">
                                <div class="card shadow rounded-pill">
                                    <div class="card-body text-center">
                                        <div class="card-title h3">Dead</div>
                                        <div class="h1 quicksand"><?=$dead?></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- Chart -->
                    <div class="row mt-5">
                        <div class="col-sm-10 offset-sm-1 p-3">
                            <canvas height="200" id="trendchart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>

        let data = <?=$chart?>

        let trend_chart = new Chart($('canvas#trendchart'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    data.datasets[0],
                    data.datasets[1],
                    data.datasets[2]
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 20
                        },
                        scaleLabel:{
                            display: true,
                            labelString: 'Count'
                        }
                    }]
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                title:{
                    display: true,
                    text: 'Cases for the Past 2 Weeks'
                }
            }
        })
    </script>
</body>
</html>