<?php
    include ("DbConnect.php");
    $how_far_message = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="../styles/datadesplay.css">
</head>
<body>
    <?php echo "<html> <br/> <a class = 'back-home' href='../MyProducts.html'> Back to Store </a>";?>

    <div class="message">
        <?php
            echo $how_far_message;
        ?>
    </div>

    <table class="product_table">
        <tr>
            <th class="t-head">Product Id</th>
            <th class="t-head">Product Name</th>
            <th class="t-head">Product Type</th>
            <th class="t-head">Product Price</th>
            <th class="t-head">Product Brand</th>
            <th class="t-head">Product Description</th>
            <th class="t-head">Product Image</th>
            <th class="t-head">Entry Date</th>
            <th class="t-head">Updated Date</th>
            <th class="t-head">Expire Date</th>
        </tr>

        <?php
        $sql = "SELECT * FROM products";
        $query_run = "";

        try{
            $query_run = mysqli_query($conn, $sql);

            if(mysqli_num_rows($query_run) > 0){
                foreach($query_run as $row){
                    echo "
                    <tr>
                        <td class='t-data'>$row[product_id]</td>
                        <td class='t-data'>$row[product_name]</td>
                        <td class='t-data'>$row[product_type]</td>
                        <td class='t-data'>$row[price]</td>
                        <td class='t-data'>$row[brand]</td>
                        <td class='t-data'>$row[pdescription]</td>
                        <td class='t-data'><img src='../images/$row[product_image]' alt='Product Image'></td>
                        <td class='t-data'>$row[created_at]</td>
                        <td class='t-data'>$row[updated_at]</td>
                        <td class='t-data'>$row[expire_at]</td>
                    </tr>";
                };

                //echo "<html> <a class = 'back-home' href='../MyProducts.html'> Back to Store </a>";
            }
            else{
                $how_far_message = "No record found";
                //echo $how_far_message;
                
            }
        }
        catch(mysqli_sql_exception){
            $how_far_message = "Could not get data";
            //echo "<html> <a class = 'back-home' href='../MyProducts.html'> Back to Store </a>";
        }
        
    ?>
    </table>
</body>
</html>

<?php
    mysqli_close($conn);
?>