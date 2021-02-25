<?php

    global $conn;
    
    // Get total numbers
    $sql = $conn->query("EXEC sp_count_total");

    $data = $sql->fetchAll();

    foreach ($data as $row) {
        if($row->status === 'Infected')
            $t_infected = $row->count;
        else if($row->status === 'Expired')
            $t_expired = $row->count;
        else
            $t_recovered = $row->count;
    }
?>