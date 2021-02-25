<?php

    global $conn;

    // If user is superuser, view all data, otherwise filter by barangay
    if(!strcmp($_SESSION['user']->superuser, '1')){
        $sql = $conn->prepare("EXEC sp_superuser_barangay_count");
        $sql->execute();
        $data = $sql->fetchAll();
    }else{
        $sql = $conn->prepare("EXEC sp_barangay_count :id");

        $sql->bindParam(':id', $_SESSION['user']->barangay_id);
        $sql->execute();
        $data = $sql->fetchAll();
    }

    $infected = $data[0]->count;
    $recovered = $data[1]->count;
    $dead = $data[2]->count;
    $total = $infected + $recovered + $dead;

?>