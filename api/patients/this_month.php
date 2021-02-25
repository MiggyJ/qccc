<?php

    global $conn;

    // Get the counts for this month
    $sql = $conn->query("EXEC sp_this_month");

    $data = $sql->fetchAll();

    foreach ($data as $row) {
        if($row->status === 'Infected')
            $m_infected = $row->count;
        else if($row->status === 'Expired')
            $m_expired = $row->count;
        else
            $m_recovered = $row->count;
    }

?>