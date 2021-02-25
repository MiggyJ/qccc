<?php

    global $conn;

    // Get all barangays for selection
    $sql = $conn->query("EXEC sp_barangay_list");

    $barangays = $sql->fetchAll();

?>