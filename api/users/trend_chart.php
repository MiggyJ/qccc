<?php

    global $conn;

    $labels = array();

    // Get array of dates for the past two weeks
    $period = new DatePeriod(
        new DateTime(date('Y/m/d', strtotime('-2 weeks'))),
        new DateInterval('P1D'),
        new DateTime(date('Y-m-d'))
    );

    // Labels for chart
    foreach($period as $value){
        $labels[] = $value->format('Y/m/d');
    }

    // Datasets for chart
    $datasets = [
        [
            'label' => 'Infected',
            'backgroundColor' => '#FF0000',
            'borderColor' => '#880000',
            'fill' => false,
            'data' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0]
        ],
        [
            'label' => 'Recovered',
            'backgroundColor' => '#00FF00',
            'borderColor' => '#008800',
            'fill' => false,
            'data' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0]
        ],
        [
            'label' => 'Death',
            'backgroundColor' => '#ccc',
            'borderColor' => '#333',
            'fill' => false,
            'data' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0]
        ]
    ];


    // If user is superuser, view all data, otherwise filter by barangay
    if(!strcmp($_SESSION['user']->superuser, '1')){
        $sql = $conn->prepare("EXEC sp_superuser_trend_chart");
        $sql->execute();
        $data = $sql->fetchAll();
    }else{
        $sql = $conn->prepare("EXEC sp_trend_chart :id");
        $sql->bindParam(':id', $_SESSION['user']->barangay_id);
        $sql->execute();
        $data = $sql->fetchAll();
    }

    for($i = 0; $i < count($data); $i++){
        foreach ($labels as $key => $value) {
            if(strcmp($data[$i]->date, $value) === 0){
                $datasets[$data[$i]->status_id - 1]['data'][$key] = $data[$i]->count;
            }
        }
    }

    $chart = json_encode([
        'labels' => $labels,
        'datasets' => $datasets
    ])

?>