<?php
    include ("DbConnect.php");
    $how_far_message = "";
?>

<?php
    $how_far_message = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $del_id = $_POST["product_id"];

        $sql2 = "DELETE FROM products
                 WHERE product_id = $del_id";
        $query_run = "";

        try{
            $query_run = mysqli_query($conn, $sql2);
            $how_far_message = "Product Deleted!";
        }
        catch(mysqli_sql_exception){
            $how_far_message = "Could not delete product";
            $how_far_message = "<html> <a href='../MyProducts.html'> Back to Library </a>";
        }
    }
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
                            <form action='DeleteProduct.php' method='POST'> 
                                <input type='hidden' name='product_id' value='$row[product_id]'>
                                
                                <input type='submit' id='delete' name='delete' value='Delete'>
                            </form>
                        </td>
                    </tr>"; 
                };

                echo "<html> <a href='../MyProducts.html'> Back to Store </a>";
            }
            else{
                $how_far_message = "No record found";
                echo $how_far_message;
                echo "<html> <br/> <a href='../MyProducts.html'> Back to Store </a>";
            }
        }
        catch(mysqli_sql_exception){
            $how_far_message = "Could not get data";
            echo "<html> <br/> <a href='../MyProducts.html'> Back to Store </a>";
        }
        
    ?>
    </table>
</body>
</html>

<?php
    mysqli_close($conn);
?>