<?php
// Database connection
include('session.php');
include('config.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT id, name, price, description, category_id, image_url FROM products";
$result = $conn->query($sql);
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
    text-align: center; /* Center align text and elements within the card */
}
.product-card img {
    max-width: 100%; /* Ensure the image fits within the card */
    height: auto;
    margin: 0 auto; /* Center the image horizontally */
}
.add-to-cart-btn {
    display: inline-block;
    margin-top: 16px;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
}
.add-to-cart-btn:hover {
    background-color: #0056b3;
}

        
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php 
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
                while($row = $result->fetch_assoc()) {
                    echo "<div class='col-md-3'>";
                    echo "<div class='product-card'>";
                    if (!empty($row['image_url']) && file_exists($row['image_url'])) {
                        echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='Product Image'>";
                    } else {
                        echo "No Image";
                    }
                    echo "<div class='product-details'>";
                    echo "<h2>" . htmlspecialchars($row["name"]) . "</h2>";
                    echo "<p>Price: P " . htmlspecialchars($row["price"]) . "</p>";
                    echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($row["category_id"]) . "</p>";
                    echo "<a href='#' class='add-to-cart-btn'>Add to Cart</a>"; // Add to Cart button
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
