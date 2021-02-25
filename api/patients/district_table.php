<?php

    global $conn;

    // Variable for table rows array
    $tableData = array();

    // Loop through every barangay - produces 3 rows, one for each status
    for($i = 1; $i <= 142; $i++){
        $sql = $conn->prepare("EXEC sp_district_table :id");
        $sql->bindParam(':id', $i);
        $temp = $sql->execute();
        $temp = $sql->fetchAll();
        
        // Temporary variable for row
        $tempData = [
            'barangay' => '',
            'district' => '',
            'infected' => $temp[0]->count,
            'recovered'=> $temp[1]->count,
            'deaths'   => $temp[2]->count
        ];

        // Make sure that barangay name is given even if there is no count
        if($temp[0]->barangay !== null)
            $tempData['barangay'] = $temp[0]->barangay;
        else if($temp[1]->barangay !== null)
            $tempData['barangay'] = $temp[1]->barangay;
        else if($temp[2]->barangay !== null)
            $tempData['barangay'] = $temp[2]->barangay;

        // Make sure that district name is given even if there is no count
        if($temp[0]->district !== null)
            $tempData['district'] = $temp[0]->district;
        else if($temp[1]->district !== null)
            $tempData['district'] = $temp[1]->district;
        else if($temp[2]->district !== null)
            $tempData['district'] = $temp[2]->district;

        // Add row to table data array
        $tableData[] = $tempData;
    }

?>