<?php
include("DbConnect.php");

$product_name_err = $price_err = $publish_date_err = "";
$valid = true;
$imageNewDir = "";
$how_far_message = "";

function test_input($data) {
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create_product'])) {

        if ($_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
            $uploadOk = 0;
        } else {
            $target_dir = "C:/xampp/htdocs/EndOfSemProject/images/"; // folder to store uploaded images
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
        if (empty($_POST["publish_date"])) {
            $publish_date_err = "Date Required!";
            $valid = false;
        }

        try {
            $query = "SELECT product_name FROM products WHERE product_name = ?";
            $stmt = $conn->prepare($query);
            $product_name = test_input($_POST["product_name"]);
            $stmt->bind_param("s", $product_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if (mysqli_num_rows($result) > 0) {
                $how_far_message = "Product Already Exist!";
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
            $entry_date = test_input($_POST["publish_date"]);
            $expire_date = test_input($_POST["expire_date"]);

            $sql = "INSERT INTO products (product_name, product_type, pdescription, brand, product_image, price, created_at, expire_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            try {
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssssdss", $product_name, $product_type, $product_description, $brand, $imageNewName, $price, $entry_date, $expire_date);
                mysqli_stmt_execute($stmt);
                echo "<html> <a href='ReadProducts.php'> View Products </a> <br>";
                $how_far_message = "Product Created!";
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
    <title>Create Products</title>
</head>
<body>
    <a href='../MyProducts.html'> Back to Store </a> <br>

    <div class="message">
        <?php
            echo $how_far_message;
        ?>
    </div>

<div class="signup" id="signupp">
        <form action="CreateProduct.php" method="post" enctype="multipart/form-data">
            <label for="sproduct_name" id="slproduct_name">Name of Product</label>
            <input id="sproduct_name" name="product_name" value="<?php if($_SERVER["REQUEST_METHOD"]=="POST"){echo $_POST["product_name"];} else{echo "";} ?>">
            <span class="error" id="erro1">* <?php echo $product_name_err;?></span>
            <br>
            <br>
            <label for="stype">Type</label>
            <input type="text" id="stype" name="ptype" value="<?php if(isset($_POST['create_product'])) { echo $_POST["ptype"];} ?>">
            <br>
            <br>
            <label for="sdiscription">Description</label>
            <input type="text" id="sdescription" name="pdescription" value="<?php if(isset($_POST['create_product'])) { echo $_POST["pdescription"];} ?>">
            <br>
            <br>
            <label for="sprice">Price</label>
            <input type="number" min="0" step="0.01" id="sprice" name="price" value="<?php if(isset($_POST['create_product'])) { echo $_POST["price"];} ?>">
            <span class="error" id="error2">* <?php echo $price_err; ?></span>
            <br>
            <label for="sbrand">Brand</label>
            <input type="text" id="sbrand" name="brand" value="<?php if(isset($_POST['create_product'])) { echo $_POST["brand"];} ?>">
            <br>
            <br>
            <label for="spublish_date" id="date">Date of Entry</label>
            <input type="date" id="spublish_date" name="publish_date">
            <span class="error" id="erro1">* <?php echo $publish_date_err;?></span>
            <br>
            <br>
            <label for="expire_date" id="xdate">Date of Expiry</label>
            <input type="date" id="expire_date" name="expire_date">
            <br>
            <br>
            <label for="image" id="pimage">Image</label>
            <input type="file" name="image" id="image" accept="image/*">
            <input type="submit" name="create_product" value="Create" class="btn" id="sbtn1">
        </form>
        <br>
    </div>
</body>
</html>

<?php
    mysqli_close($conn);
?>