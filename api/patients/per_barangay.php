<?php

    global $conn;


    // If user is superuser, view all data, otherwise filter by barangay
    if(!strcmp($_SESSION['user']->superuser, '1')){
        $status = $_GET['status'];
        $sql = $conn->prepare(
            "EXEC sp_superuser_per_barangay
            :status"
        );
        
        $sql->bindParam(':status', $status);
        $data = $sql->execute();
        $data = $sql->fetchAll();
    }else{
        $barangay_id = $_SESSION['user']->barangay_id;
        $status = $_GET['status'];
        $sql = $conn->prepare("EXEC sp_per_barangay :barangay_id, :status");
        
        $sql->bindParam(':barangay_id', $barangay_id);
        $sql->bindParam(':status', $status);
        $data = $sql->execute();
        $data = $sql->fetchAll();
    }

?>