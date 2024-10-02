<?php
// Database connection
include('session.php');
include('config.php');

// Fetch products from the database
$sql = "SELECT id, name, price, description, category_id, image_url FROM products";
$result = $conn->query($sql);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Store</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">

    <style>
        .product-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 16px;
            transition: box-shadow 0.3s ease-in-out;
        }
        .product-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            width: 100%;
            height: auto;
        }
        .product-details {
            padding: 16px;
            text-align: center;
        }
        .product-details h2 {
            font-size: 1.25rem;
            margin-bottom: 8px;
        }
        .product-details p {
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php 
    // Dummy userType variable for demonstration, replace with actual session or authentication logic
    $userType = 'user'; // Example: 'user' or 'seller'

    if ($userType == 'user') {
        include("includes/nav_user.php");
    } elseif ($userType == 'seller') {
        include("includes/nav_seller.php");
    }
    ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Products</h1>

        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<div class='col-md-3'>";
                    echo "<div class='product-card'>";
                    if (!empty($row["image_url"])) {
                        echo "<img src='uploads/" . htmlspecialchars($row["image_url"]) . "' alt='" . htmlspecialchars($row["name"]) . "'>";
                    } else {
                        echo "<img src='uploads/default.jpg' alt='Default Image'>";
                    }
                    echo "<div class='product-details'>";
                    echo "<h2>" . htmlspecialchars($row["name"]) . "</h2>";
                    echo "<p>Price: $" . htmlspecialchars($row["price"]) . "</p>";
                    echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($row["category_id"]) . "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='col-12'><p class='text-center'>0 results</p></div>";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
