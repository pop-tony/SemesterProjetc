<?php
    include ("DbConnect.php");
    session_start();
    $how_far_message = "";
    $display_howfar = false;
    $isAvailable = false;

    $sql = "SELECT * FROM products";
    $query_run = "";

    try{
        $query_run = mysqli_query($conn, $sql);

        if(mysqli_num_rows($query_run) > 0){
            $isAvailable = true;
        }
        else{
            $how_far_message = "No record found";
            $display_howfar = true;
            //echo $how_far_message;
            // echo "<html> <a href='../MyProducts.html'> Back to Library </a>";
        }
    }
    catch(mysqli_sql_exception){
        $how_far_message = "Could not get data";
        //echo "<html> <a href='../MyProducts.html'> Back to Library </a>";
    }
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
    <a class = 'back-home' href='../MyProducts.html'> Back to Store </a> <br>

    <div class="message">
        <?php echo $_SERVER["REQUEST_METHOD"] == "GET" && $display_howfar ? $how_far_message : "Welcome 🤩"; ?>
    </div>

    <table class="product_table">
        <tr>
            <td class='t-head'>Product Id</th>
            <td class='t-head'>Product Name</th>
            <td class='t-head'>Product Type</th>
            <td class='t-head'>Product Price</th>
            <td class='t-head'>Product Brand</th>
            <td class='t-head'>Product Description</th>
            <td class='t-head'>Product Image</th>
            <td class='t-head'>Product Quantity</th>
            <td class='t-head'>Entry Date</th>
            <td class='t-head'>Updated Date</th>
            <td class='t-head'>Expire Date</th>
        </tr>

        <?php
            if($isAvailable){
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
                        <td class='t-data'>$row[product_quantity]</td>
                        <td class='t-data'>$row[created_at]</td>
                        <td class='t-data'>$row[updated_at]</td>
                        <td class='t-data'>$row[expire_at]</td>
                        <td class='t-data'>
                            <form action='UpdateProduct.php' method='GET'> 
                                <input type='hidden' name='product_id' value='$row[product_id]'>
                                <p style='display:none' $_SESSION[upd_id] = $row[product_id];></p>
                                <input type='submit' id='update' name='update' value='Update'>
                            </form>
                        </td>
                    </tr>"; 
                };

            }
        ?>
    </table>
</body>
</html>

<?php
    mysqli_close($conn);
?>