<?php
    include ("DbConnect.php");
    $isAvailable = false;

    $sql = "SELECT * FROM products";
    $query_run = "";

    try{
        $query_run = mysqli_query($conn, $sql);

        if(mysqli_num_rows($query_run) > 0){
            $isAvailable = true;
        }
    }
    catch(mysqli_sql_exception){
        echo "An error occured";
    }

    $names = [];
    if($isAvailable){
        foreach($query_run as $row){
            $names[] = ($row['product_name']);
        };
    }
    echo json_encode($names);
?>