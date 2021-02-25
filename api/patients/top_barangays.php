<?php

    global $conn;

    // Get top 5 barangays tally
    $sql = $conn->query("EXEC sp_top_barangays");

    $data = $sql->fetchAll();

    $top_infected_b = $data;

    // District tally
    $sql = $conn->query("EXEC sp_tally_districts");

    $data = $sql->fetchAll();

    $top_infected_d = $data;
?>