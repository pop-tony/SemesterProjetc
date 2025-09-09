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
</head>
<body>
    <a href='../MyProducts.html'> Back to Store </a> <br>

    <div class="message">
        <?php
            echo $how_far_message;
        ?>
    </div>

    <table class="product_table">
        <tr>
            <th>Product Id</th>
            <th>Product Name</th>
            <th>Product Type</th>
            <th>Product Price</th>
            <th>Product Description</th>
            <th>Product Image</th>
            <th>Entry Date</th>
            <th>Updated Date</th>
            <th>Expire Date</th>
        </tr>

        <?php
        $sql = "SELECT * FROM products";
        $query_run = "";
        $how_far_message = "";

        try{
            $query_run = mysqli_query($conn, $sql);

            if(mysqli_num_rows($query_run) > 0){
                foreach($query_run as $row){
                    echo "
                    <tr>
                        <td>$row[product_id]</td>
                        <td>$row[product_name]</td>
                        <td>$row[product_type]</td>
                        <td>$row[price]</td>
                        <td>$row[pdescription]</td>
                        <td><img src='../images/$row[product_image]' alt='Product Image'></td>
                        <td>$row[created_at]</td>
                        <td>$row[updated_at]</td>
                        <td>$row[expire_at]</td>
                        <td>
                            <form action='UpdateProduct.php' method='GET'> 
                                <input type='hidden' name='product_id' value='$row[product_id]'>
                                
                                <input type='submit' id='update' name='update' value='Update'>
                            </form>
                        </td>
                    </tr>"; 
                };

            }
            else{
                $how_far_message = "No record found";
                echo $how_far_message;
               // echo "<html> <a href='../MyProducts.html'> Back to Library </a>";
            }
        }
        catch(mysqli_sql_exception){
            $how_far_message = "Could not get data";
            //echo "<html> <a href='../MyProducts.html'> Back to Library </a>";
        }
        
    ?>
    </table>
</body>
</html>

<?php
    mysqli_close($conn);
?>