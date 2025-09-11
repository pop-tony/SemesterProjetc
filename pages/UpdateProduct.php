<?php
    include ("DbConnect.php");
    $how_far_message = "";
?>

<?php
    
    $upd_id = "";
    $product_id = "";
    $product_name = "";
    $price = "";
    $product_type = "";
    $product_description = "";
    $brand = "";
    $entry_date = "";
    $expire_date = "";
    $imageNewName = "";
    $display_howfar = false;

    $product_name_err = $price_err = $publish_date_err = "";
    $valid = true;

    function test_input($data) {
        $data = htmlspecialchars($data);
        return $data;
    }

    if($_SERVER["REQUEST_METHOD"] == "GET"){

        $upd_id = $_GET["product_id"];

        $sql1 = "SELECT *
                FROM products
                WHERE product_id = $upd_id";
        $query_run = "";

        try{
            $query_run = mysqli_query($conn, $sql1);

            if(mysqli_num_rows($query_run) > 0){
                $info = $query_run->fetch_assoc();
                $product_id = $info["product_id"];
                $product_name = $info["product_name"];
                $price = $info["price"];
                $product_type = $info["product_type"];
                $product_description = $info["pdescription"];
                $brand = $info["brand"];
                $quantity = $info["product_quantity"];
                $expire_date = $info["expire_at"];
                $imageNewName = $info["product_image"];
            }
            else{
                $how_far_message = "No record found";
                $display_howfar = true;
            }
        }
        catch(mysqli_sql_exception){
            $how_far_message = "Could not get data";
        }

    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['create_product'])) {

            $upd_id = $_POST["product_id"];

            if ($_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
                $uploadOk = 0;
            } else {
                $target_dir = "../images/"; // folder to store uploaded images
                $image_full_name = $_FILES["image"]["name"];
                $image_ext = explode(".", $image_full_name);
                $actual_image_ext = strtolower(end($image_ext));

                $imageNewName = uniqid("", true) . "." . $actual_image_ext;
                $imageNewDir = $target_dir . $imageNewName;

                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($image_full_name, PATHINFO_EXTENSION));

                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $how_far_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check !== false) {
                    $how_far_message = "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $how_far_message = "File is not an image.";
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["image"]["size"] > 5000000) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $how_far_message = "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $imageNewDir)) {
                        $how_far_message = "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
                    } else {
                        $how_far_message = "Sorry, there was an error uploading your file.";
                    }
                }
            }

            if (empty($_POST["product_name"])) {
                $product_name_err = "Product Name Required!";
                $valid = false;
            }
            if (empty($_POST["price"])) {
                $price_err = "Price Required!";
                $valid = false;
            }
            if (empty($_POST["quantity"])) {
                $publish_date_err = "Qantity Required!";
                $valid = false;
            }

            try {
                $query = "SELECT product_name FROM products WHERE product_name = ? AND product_id != ?";
                $stmt = $conn->prepare($query);
                $product_name = test_input($_POST["product_name"]);
                $stmt->bind_param("ss", $product_name, $upd_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if (mysqli_num_rows($result) > 0) {
                    $how_far_message = "Product Already Exist!, Update the Quantity instead!";
                    $valid = false;
                }
            } catch (mysqli_sql_exception $e) {
                $how_far_message = "An error occurred: " . $e->getMessage();
                $valid = false;
            }

            if ($valid) {
                $product_name = test_input($_POST["product_name"]);
                $price = test_input($_POST["price"]);
                $product_type = test_input($_POST["ptype"]);
                $product_description = test_input($_POST["pdescription"]);
                $brand = test_input($_POST["brand"]);
                $quantity= test_input($_POST["quantity"]);
                $expire_date = test_input($_POST["expire_date"]);

               $sql = "UPDATE products
                        SET product_name = ?, product_type = ?, pdescription = ?, brand = ?, product_image = ?, price = ?, product_quantity = ?, expire_at = ?
                        WHERE product_id = ?";

                try {
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "sssssdsss", $product_name, $product_type, $product_description, $brand, $imageNewName, $price, $quantity, $expire_date, $upd_id);
                    mysqli_stmt_execute($stmt);
                    $how_far_message = "Product Udated!";
                } catch (mysqli_sql_exception $e) {
                    $how_far_message = "An error occurred: " . $e->getMessage();
                    echo $how_far_message;
                }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Products</title>
    <link rel="stylesheet" href="../styles/datadesplay.css">
</head>
<body>
    <div class="leave">
        <a class = 'back-home' href='../MyProducts.html'> Back to Store </a> <br><br>
        <a class = 'view-p' href='ReadProducts.php'> View Products </a> <br>
    </div>

    <div class="signup" id="signupp">

        <div class="product-write">
            <div class="message">
                <?php echo $_SERVER["REQUEST_METHOD"] == "POST" ? $how_far_message : ""; ?>
            </div>

            <form action="UpdateProduct.php" method="post" enctype="multipart/form-data">
                <label for="sproduct_name" id="slproduct_name">Name of Product</label>
                <input id="sproduct_name" name="product_name" value="<?php echo !$valid && isset($_POST['create_product']) ? $_POST["product_name"] : $product_name; ?>">
                <span class="error" id="error1">* <?php echo $product_name_err;?></span>
                <br>
                <br>
                <label for="stype">Type</label>
                <input type="text" id="stype" name="ptype" value="<?php echo !$valid && isset($_POST['create_product']) ? $_POST["ptype"] : $product_type; ?>">
                <br>
                <br>
                <label for="sdiscription">Description</label>
                <input type="text" id="sdescription" name="pdescription" value="<?php echo !$valid && isset($_POST['create_product']) ? $_POST["pdescription"] : $product_description; ?>">
                <br>
                <br>
                <label for="sprice">Price</label>
                <input type="number" min="0" step="0.01" id="sprice" name="price" value="<?php echo !$valid && isset($_POST['create_product']) ? $_POST["price"] : $price; ?>">
                <span class="error" id="error2">* <?php echo $price_err; ?></span>
                <br>
                <label for="sbrand">Brand</label>
                <input type="text" id="sbrand" name="brand" value="<?php echo !$valid && isset($_POST['create_product']) ? $_POST["brand"] : $brand; ?>">
                <br>
                <br>
                <label for="quantity" id="date">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo !$valid && isset($_POST['create_product']) ? $_POST["quantity"] : $quantity; ?>">
                <span class="error" id="erro1">* <?php echo $publish_date_err;?></span>
                <br>
                <br>
                <label for="expire_date" id="xdate">Date of Expiry</label>
                <input type="date" id="expire_date" name="expire_date">
                <br>
                <br>
                <label for="image" id="pimage">Image</label>
                <input type="file" name="image" id="image" accept="image/*">
                <input type='hidden' name='product_id' value='<?php echo $product_id;?>'>
                <input type="submit" name="create_product" value="Update" class="btn" id="sbtn1">
            </form>
            <br>
        </div>
    </div>
</body>
</html>