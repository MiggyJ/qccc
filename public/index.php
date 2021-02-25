<?php

    // Functions
    include('../api/_connect.php');
    include('../api/patients/district_table.php');
    include('../api/patients/monthly_chart.php');
    include('../api/patients/this_month.php');
    include('../api/patients/top_barangays.php');
    include('../api/patients/today.php');
    include('../api/patients/total.php');
    

    if(isset($_POST['loginBtn'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = $conn->prepare("EXEC sp_login_attempt :email");
        $sql->bindParam(':email', $email);
        $sql->execute();
        
        $data = $sql->fetch();
        // If user is not found, return error, otherwise check if password is correct
        if(!$data){
            $error = "The combination you entered is not recognized/verified.";
        }else{
            if(password_verify($password, $data->password)){
                session_start();
                $_SESSION['user'] = $data;
                header('location: '.getenv('APP_BASE').'admin');
            }else{
                $error = "The combination you entered is not recognized/verified.";
            }
        }


    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quezon City Covid Census | Home</title>
    <!-- Stylesheets, Fonts, and Logos -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="shortcut icon" href="./assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
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
        nav.navbar {
            width: inherit;
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
<body data-spy="scroll" data-target="#navbar" data-offset="50">
    <!-- Navbar -->
    <?php include('templates/navbar.php')?>
    <!-- Main Content -->
    <div id="home" style="padding-top: 70px;">
        <!-- Tally -->
        <div class="container mt-5 text-center">
            <div class="row no-gutters">
                <div class="col-lg-8">
                    <div class="row flex-column">
                        <div class="col">
                            <div class="card rounded-0">
                                <div class="card-body bg-light">
                                    <div class="card-title h1">New Cases</div>
                                    <div class="display-1 squada mt-3 text-primary"><?=$today?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row no-gutters">
                                <div class="col">
                                    <div class="card rounded-0">
                                        <div class="card-body bg-light" style="height: 224px;">
                                            <div class="card-title h4">Top Infected Barangays</div>
                                            <ol>
                                                <?php foreach($top_infected_b as $row):?>
                                                <li>
                                                    <div class="d-flex justify-content-between">
                                                        <div><?=$row->barangay?></div>
                                                        <div class="mr-5"><?=$row->count?></div>
                                                    </div>
                                                </li>
                                                <?php endforeach?>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card rounded-0">
                                        <div class="card-body bg-light" style="height: 224px;">
                                            <div class="card-title h4">Districts Tally</div>
                                            <ol>
                                            <?php foreach($top_infected_d as $row):?>
                                                <li>
                                                    <div class="d-flex justify-content-between">
                                                        <div><?=$row->district?></div>
                                                        <div class="mr-5"><?=$row->count?></div>
                                                    </div>
                                                </li>
                                                <?php endforeach?>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="row flex-column">
                        <div class="col">
                            <div class="card bg-light rounded-0">
                                <div class="card-body">
                                    <div class="card-title h4">Active Cases</div>
                                    <div class="display-4 squada text-primary"><?=$t_infected?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card bg-light rounded-0">
                                <div class="card-body">
                                    <div class="card-title h4">Total Recoveries</div>
                                    <div class="display-4 squada text-primary"><?=$t_recovered?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card bg-light rounded-0">
                                <div class="card-body">
                                    <div class="card-title h4">Total Deaths</div>
                                    <div class="display-4 squada text-primary"><?=$t_expired?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row mt-5 mb-5">
                <center class="display-3 squada">Monthly Count</center>
                <div class="col-lg-8">
                    <canvas id="monthly" height="200"></canvas>
                </div>
                <div class="col">
                    <div class="card rounded-0">
                        <div class="card-header h4">This Month</div>
                        <div class="card-body">
                            <div class="row flex-column d-none d-lg-block">
                                <div class="col">
                                    <div class="card rounded-0">
                                        <div class="card-body">
                                            <div class="card-title h3">New Cases</div>
                                            <div class="h4 text-primary"><?=$m_infected?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card rounded-0">
                                        <div class="card-body">
                                            <div class="card-title h3">Recoveries</div>
                                            <div class="h4 text-primary"><?=$m_recovered?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card rounded-0">
                                        <div class="card-body">
                                            <div class="card-title h3">Deaths</div>
                                            <div class="h4 text-primary"><?=$m_expired?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-lg-none">
                                <div class="col">
                                    <div class="card rounded-0">
                                        <div class="card-body">
                                            <div class="card-title h3">New Cases</div>
                                            <div class="h4 text-primary"><?=$m_infected?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card rounded-0">
                                        <div class="card-body">
                                            <div class="card-title h3">Recoveries</div>
                                            <div class="h4 text-primary"><?=$m_recovered?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card rounded-0">
                                        <div class="card-body">
                                            <div class="card-title h3">Deaths</div>
                                            <div class="h4 text-primary"><?=$m_expired?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <!-- Tally Table-->
        <div class="container">
            <center>
                <div class="display-4 squada">Tally per Barangay</div>
            </center>
            <table class="table table-bordered w-100">
                <thead>
                    <th>Barangay</th>
                    <th>District</th>
                    <th>Infected</th>
                    <th>Recovered</th>
                    <th>Deaths</th>
                </thead>
                <tbody>
                    <?php foreach($tableData as $row):?>
                    <tr>
                        <td><?=$row['barangay']?></td>
                        <td><?=$row['district']?></td>
                        <td><?=$row['infected']?></td>
                        <td><?=$row['recovered']?></td>
                        <td><?=$row['deaths']?></td>
                    </tr>
                    <?php endforeach?>
                </tbody>
            </table>
            <hr>
        </div>
        <!-- Fast Facts -->
        <div class="container">
            <div class="display-3 squada">CORONAVIRUS <span class="squada text-primary">(COVID-19)</span></div>
            <p class="lead">COVID-19, otherwise known as coronavirus disease 2019, is a new infectious disease caused by a previously unknown virus called SARS-CoV-2.</p>
            <div class="card">
                <div class="card-body">
                    <div class="card-title display-4 squada text-primary">Fast Facts:</div>
                    <ul>
                        <li class="quicksand h4">
                            COVID-19 is a new infectious disease caused by the SARS-CoV-2 virus.
                        </li>
                        <li class="quicksand h4">
                            The key symptoms of COVID-19 are a fever, dry cough, tiredness and loss of taste or smell.
                        </li>
                        <li class="quicksand h4">
                            COVID-19 affects different people in different ways. Some people do not have any symptoms and may not even know they have the virus, while others get seriously ill and need hospital care.
                        </li>
                        <li class="quicksand h4">
                            Physical distancing, wearing a face mask around other people and frequent handwashing are some of the best ways to prevent COVID-19.
                        </li>
                        <li class="quicksand h4">
                            If you think you have symptoms of COVID-19, stay at home and call your local health authority. They will tell you what to do next.
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
        </div>
        <!-- Infographics -->
        <div class="container">
            <center class="display-3 squada">Infographics</center>
            <div class="row justify-content-center">
                <div class="col mt-3 mb-3">
                    <center>
                    <img src="https://www.who.int/images/default-source/wpro/health-topic/covid-19/slide50802f06d77e5447b9901cef7be4d3f05.jpg" class="img-fluid" alt="infographic">
                    </center>
                </div>
                <div class="col mt-3 mb-3">
                    <center>
                    <img src="https://www.who.int/images/default-source/wpro/health-topic/covid-19/slide16.jpg" class="img-fluid" alt="infographic">
                    </center>
                </div>
            </div>
            <div class="row ">
                <div class="col-lg-4 mb-3">
                    <center>
                    <img src="https://www.who.int/images/default-source/wpro/health-topic/covid-19/slide291d0d15e55b5420db1f15df827bcd499.jpg" class="img-fluid" alt="infographic">
                    </center>
                </div>
                <div class="col-lg-8">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe width="837" height="471" src="https://www.youtube.com/embed/rAj38E7vrS8" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                </div>
            </div>
        </div>    
    </div>
    <!-- FAQs -->
    <div id="faq" style="padding-top: 110px;">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title h1">Frequently Asked Questions</div>
                                <div class="accordion" id="faqAccordion">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-left shadow-none" type="button" data-toggle="collapse" data-target="#faqOne" aria-expanded="true" aria-controls="faqOne">
                                                <div class="h4">What is COVID-19?</div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="faqOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqAccordion">
                                            <div class="card-body">
                                                <p>
                                                    COVID-19 is a new disease, caused by a novel (or new) coronavirus that has not previously been seen in humans. Because it is a new virus, scientists are learning more each day. Although most people who have COVID-19 have mild symptoms, COVID-19 can also cause severe illness and even death. Some groups, including older adults and people who have certain underlying medical conditions, are at increased risk of severe illness.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-left shadow-none" type="button" data-toggle="collapse" data-target="#faqTwo" aria-expanded="true" aria-controls="faqTwo">
                                                <div class="h4">Why is the disease called Coronavirus Disease 2019, COVID-19?</div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="faqTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                                            <div class="card-body">
                                                <p>
                                                    On February 11, 2020 the World Health Organization announced an official name for the disease that is causing the 2019 novel coronavirus outbreak, first identified in Wuhan China. The new name of this disease is coronavirus disease 2019, abbreviated as COVID-19. In COVID-19, “CO” stands for corona, “VI” for virus, and ”D” for disease. Formerly, this disease was referred to as “2019 novel coronavirus” or “2019-nCoV.”
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingThree">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-left shadow-none" type="button" data-toggle="collapse" data-target="#faqThree" aria-expanded="true" aria-controls="faqThree">
                                                <div class="h4">How does COVID-19 spread?</div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="faqThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordion">
                                            <div class="card-body">
                                                <p>
                                                    COVID-19 is thought to spread mainly through close contact from person to person, including between people who are physically near each other (within about 6 feet). People who are infected but do not show symptoms can also spread the virus to others. Cases of reinfection with COVID-19  have been reported but are rare. We are still learning about how the virus spreads and the severity of illness it causes.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headngFour">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-left shadow-none" type="button" data-toggle="collapse" data-target="#faqFour" aria-expanded="true" aria-controls="faqFour">
                                                <div class="h4">How can I protect myself?</div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="faqFour" class="collapse" aria-labelledby="headngFour" data-parent="#faqAccordion">
                                            <div class="card-body">
                                            <h4>Three Important Ways to Slow the Spread</h4>
                                                <ul>
                                                    <li>Wear a mask to protect yourself and others and stop the spread of COVID-19.</li>
                                                    <li>Stay at least 6 feet (about 2 arm lengths) from others who don’t live with you.</li>
                                                    <li>Avoid crowds. The more people you are in contact with, the more likely you are to be exposed to COVID-19.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingFive">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-left shadow-none" type="button" data-toggle="collapse" data-target="#faqFive" aria-expanded="true" aria-controls="faqFive">
                                                <div class="h4">What are the symptoms?</div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="faqFive" class="collapse" aria-labelledby="headingFive" data-parent="#faqAccordion">
                                            <div class="card-body">
                                                <h4>
                                                    People with COVID-19 have had a wide range of symptoms reported – ranging from mild symptoms to severe illness. Symptoms may appear 2-14 days after exposure to the virus. People with these symptoms may have COVID-19:
                                                </h4>
                                                <ul>
                                                    <li>Fever or chills</li>
                                                    <li>Cough</li>
                                                    <li>Shortness of breath or difficulty breathing</li>
                                                    <li>Fatigue</li>
                                                    <li>Muscle or body aches</li>
                                                    <li>Headache</li>
                                                    <li>New loss of taste or smell</li>
                                                    <li>Sore throat</li>
                                                    <li>Congestion or runny nose</li>
                                                    <li>Nausea or vomiting</li>
                                                    <li>Diarrhea</li>
                                                </ul>
                                                <p>
                                                    This list does not include all possible symptoms and will be updated as we learn more about COVID-19.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingSix">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-left shadow-none" type="button" data-toggle="collapse" data-target="#faqSix" aria-expanded="true" aria-controls="faqSix">
                                                <div class="h4">What is the difference between Quarantine and Isolation?</div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="faqSix" class="collapse" aria-labelledby="headingSix" data-parent="#faqAccordion">
                                            <div class="card-body">
                                                <p>
                                                    <strong>Quarantine</strong> keeps someone who might have been exposed to the virus away from others.
                                                </p>
                                                <p>
                                                    <strong>Isolation</strong> keeps someone who is infected with the virus away from others, even in their home.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingSeven">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-left shadow-none" type="button" data-toggle="collapse" data-target="#faqSeven" aria-expanded="true" aria-controls="faqSeven">
                                                <div class="h4">Who needs Quarantine?</div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="faqSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#faqAccordion">
                                            <div class="card-body">
                                                <p>
                                                    People who have been in close contact with someone who has COVID-19—excluding people who have had COVID-19 within the past 3 months.
                                                </p>
                                                <p>
                                                    People who have tested positive for COVID-19 within the past 3 months and recovered do not have to quarantine or get tested again as long as they do not develop new symptoms. People who develop symptoms again within 3 months of their first bout of COVID-19 may need to be tested again if there is no other cause identified for their symptoms.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingEight">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-left shadow-none" type="button" data-toggle="collapse" data-target="#faqEight" aria-expanded="true" aria-controls="faqEight">
                                                <div class="h4">What counts as "close contact"?</div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="faqEight" class="collapse" aria-labelledby="headingEight" data-parent="#faqAccordion">
                                            <div class="card-body">
                                               <ul>
                                                    <li>You were within 6 feet of someone who has COVID-19 for a total of 15 minutes or more.</li>
                                                    <li>You provided care at home to someone who is sick with COVID-19.</li>
                                                    <li>You had direct physical contact with the person (hugged or kissed them).</li>
                                                    <li>You shared eating or drinking utensils.</li>
                                                    <li>They sneezed, coughed, or somehow got respiratory droplets on you.</li>
                                               </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingNine">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-left shadow-none" type="button" data-toggle="collapse" data-target="#faqNine" aria-expanded="true" aria-controls="faqNine">
                                                <div class="h4">What to do after having "close contact"?</div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="faqNine" class="collapse" aria-labelledby="headingNine" data-parent="#faqAccordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li>Stay home and monitor your health.</li>
                                                    <li>Stay home for 14 days after your last contact with a person who has COVID-19.</li>
                                                    <li>Watch for fever (100.4?F), cough, shortness of breath, or other symptoms of COVID-19.</li>
                                                    <li>If possible, stay away from others, especially people who are at higher risk for getting very sick from COVID-19.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingTen">
                                            <h2 class="mb-0">
                                                <button class="btn btn-block text-left shadow-none" type="button" data-toggle="collapse" data-target="#faqTen" aria-expanded="true" aria-controls="faqTen">
                                                <div class="h4">Who are at risk?</div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="faqTen" class="collapse" aria-labelledby="headingTen" data-parent="#faqAccordion">
                                            <div class="card-body">
                                                <p>
                                                    Older people and person with medical conditions.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About -->
    <div id="about" style="padding-top: 110px;">
        <div class="container">
            <div class="jumbotron">
                <div class="display-2 squada">About</div>
                <p class="h5 quicksand">
                    Quezon City Covid-19 Census is created by researchers with the goal of making City statistics from local barangays available in a thought-provoking and time relevant format to a wide audience.
                </p>
                <hr>
                <div class="display-4 squada">How it works</div>
                <p class="h5 quicksand">
                    For the COVID-19 data, we collect data from local barangay reports, wherein the reports are manually inputted for the collection of patient's data. The collected data may or may not use for contact tracing. Timely updates are made possible thanks to the participation of users around local barangays. For the graph counters on the home page, we elaborate instead a real-time estimate through our proprietary algorithm which processes the latest data and projections provided.
                </p>
            </div>
        </div>
    </div>


    <!-- Templates -->
    <?php include('templates/login.php')?>

    <!-- Footer -->
    <?php include('templates/footer.php')?>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script>
    <?php if(isset($error)): ?>
        $('.modal').modal('show')
    <?php endif?>

        let data = <?=$chart?>

        let prev_chart = new Chart($('canvas#monthly'), {
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
                            max: 100
                        }
                    }]
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                }
            }
        })

        $('.table').DataTable({
            scrollY: 400,
            scrollX: true,
            autoWidth: true,
        })

        <?php if(isset($error)):?>
            $('body').scrollspy('refresh')
        <?php endif ?>
    </script>

</body>
</html>