<?php

    // Database Connection

    $host = getenv('DB_HOST');
    $dbname = getenv('DB_DATABASE');
    $user = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');

    $conn = new PDO("sqlsrv:server=$host;database=$dbname", $user, $password, 
        array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    )

?>