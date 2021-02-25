<?php

    global $conn;

    // Get new cases added today
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $stmt = $conn->prepare(
        "EXEC sp_count_today
            :year,
            :month,
            :day"
    );
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':month', $month);
    $stmt->bindParam(':day', $day);
    $stmt->execute();

    $data = $stmt->fetch();
    $today = $data->count;
?>