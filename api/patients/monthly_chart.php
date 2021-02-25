<?php

    global $conn;

    // Get monthly count separated by combination of year and month number
    $sql = $conn->query("EXEC sp_monthly_chart");

    $data = $sql->fetchAll();

    // Labels for each tick in the chart
    $labels = array();

    // Datasets for each status
    $datasets = [
        [
            'label' => 'Infected',
            'backgroundColor' => '#FF0000',
            'borderColor' => '#880000',
            'fill' => false,
            'data' => []
        ],
        [
            'label' => 'Recovered',
            'backgroundColor' => '#00FF00',
            'borderColor' => '#008800',
            'fill' => false,
            'data' => []
        ],
        [
            'label' => 'Death',
            'backgroundColor' => '#ccc',
            'borderColor' => '#333',
            'fill' => false,
            'data' => []
        ]
    ];

    // Loop through the fetched data
    // One month+year combination has 3 rows, one for each status
    for($i = 1; $i < count($data); $i+=3){
        $labels[] = $data[$i]->month . ' ' . $data[$i]->year;
        $datasets[0]['data'][] = $data[$i]->count;
        $datasets[1]['data'][] = $data[$i+1]->count;
        $datasets[2]['data'][] = $data[$i-1]->count;
    }

    $chart = json_encode(['labels'=>$labels, 'datasets' => $datasets]);

?>