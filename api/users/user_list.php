<?php


    global $conn;

    // Get every user
    $sql = $conn->query("EXEC sp_user_list");

    $users = $sql->fetchAll();

?>