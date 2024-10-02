<?php
include("session.php"); // Ensure user is authenticated and session is active
include("config.php");

// Fetch user details from session
$sellerId = $_SESSION['userId'] ?? ''; // Assuming 'userId' is set in session
$userType = $_SESSION['userType'] ?? '';

// Redirect if user is not a seller
if ($userType !== 'seller') {
    header("Location: index.php");
    exit();
}

// Handle product addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $productName = htmlspecialchars($_POST['product_name'] ?? '');
    $productPrice = htmlspecialchars($_POST['product_price'] ?? '');
    $productDescription = htmlspecialchars($_POST['product_description'] ?? '');
    $categoryId = htmlspecialchars($_POST['category_id'] ?? '');

    // Handle image upload
    $imageUrl = '';
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $imageFile = $_FILES['image_file'];
        $uploadDir = 'uploads/';
        $imageUrl = $uploadDir . basename($imageFile['name']);

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($imageFile['tmp_name'], $imageUrl)) {
            $message = "Image uploaded successfully.";
        } else {
            $message = "Failed to upload image.";
        }
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO products (seller_id, name, price, description, category_id, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdsis", $sellerId, $productName, $productPrice, $productDescription, $categoryId, $imageUrl);

    // Execute and handle errors
    if ($stmt->execute()) {
        $message = "Product added successfully.";
    } else {
        $message = "Error adding product: " . $stmt->error;
    }
    $stmt->close();
}

// Handle product editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {
    $productId = htmlspecialchars($_POST['product_id'] ?? '');
    $productName = htmlspecialchars($_POST['edit_product_name'] ?? '');
    $productPrice = htmlspecialchars($_POST['edit_product_price'] ?? '');
    $productDescription = htmlspecialchars($_POST['edit_product_description'] ?? '');
    $categoryId = htmlspecialchars($_POST['edit_category_id'] ?? '');

    // Handle image upload
    $imageUrl = $_POST['existing_image_url'];
    if (isset($_FILES['edit_image_file']) && $_FILES['edit_image_file']['error'] == 0) {
        $imageFile = $_FILES['edit_image_file'];
        $uploadDir = 'uploads/';
        $imageUrl = $uploadDir . basename($imageFile['name']);

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($imageFile['tmp_name'], $imageUrl)) {
            $message = "Image uploaded successfully.";
        } else {
            $message = "Failed to upload image.";
        }
    }

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, category_id = ?, image_url = ? WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("sdsisi", $productName, $productPrice, $productDescription, $categoryId, $imageUrl, $productId, $sellerId);

    // Execute and handle errors
    if ($stmt->execute()) {
        $message = "Product updated successfully.";
    } else {
        $message = "Error updating product: " . $stmt->error;
    }
    $stmt->close();
}

// Handle category addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {
    $categoryName = htmlspecialchars($_POST['category_name'] ?? '');

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $categoryName);

    // Execute and handle errors
    if ($stmt->execute()) {
        $message = "Category added successfully.";
    } else {
        $message = "Error adding category: " . $stmt->error;
    }
    $stmt->close();
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $productId, $sellerId);

    // Execute and handle errors
    if ($stmt->execute()) {
        $message = "Product deleted successfully.";
    } else {
        $message = "Error deleting product: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch products for the seller
$sql = "SELECT p.*, c.name AS category_name FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.seller_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sellerId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch categories
$categoriesQuery = "SELECT id, name FROM categories";
$categoriesResult = $conn->query($categoriesQuery);

if (!$categoriesResult) {
    die("Error fetching categories: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Navbar -->
    <?php include("includes/nav_seller.php"); ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <h4 class="mt-3"><strong>Dashboard</strong></h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link primary-link" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                Add Product
                            </a>
                        </li>
                        <li class="nav-item mt-2">
                            <a href="#" class="nav-link secondary-link" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                Add Category
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4 main-content">
                <h1>Manage Products</h1>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <h2 class="mt-5">Products</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['price']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                <td>
                                    <?php if (!empty($row['image_url']) && file_exists($row['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Product Image" style="max-width: 100px;">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $row['id']; ?>">Edit</a>
                                    <a href="manage_products.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>

                            <!-- Edit Product Modal -->
                            <div class="modal fade" id="editProductModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editProductModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProductModalLabel<?php echo $row['id']; ?>">Edit Product</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="" enctype="multipart/form-data">
                                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                                <div class="form-group">
                                                    <label for="edit_product_name">Product Name</label>
                                                    <input type="text" class="form-control" id="edit_product_name" name="edit_product_name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_product_price">Product Price</label>
                                                    <input type="number" class="form-control" id="edit_product_price" name="edit_product_price" value="<?php echo htmlspecialchars($row['price']); ?>" step="0.01" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_product_description">Product Description</label>
                                                    <textarea class="form-control" id="edit_product_description" name="edit_product_description" rows="3" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_category_id">Category</label>
                                                    <select class="form-control" id="edit_category_id" name="edit_category_id" required>
                                                        <option value="">Select a category</option>
                                                        <?php
                                                        $categoriesResult->data_seek(0);
                                                        while ($category = $categoriesResult->fetch_assoc()) :
                                                        ?>
                                                            <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php echo $category['id'] == $row['category_id'] ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($category['name']); ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_image_file">Image File</label>
                                                    <input type="file" class="form-control" id="edit_image_file" name="edit_image_file" accept="image/*">
                                                    <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($row['image_url']); ?>">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" name="edit_product" class="btn btn-primary">Update Product</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="product_name">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="form-group">
                            <label for="product_price">Product Price</label>
                            <input type="number" class="form-control" id="product_price" name="product_price" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="product_description">Product Description</label>
                            <textarea class="form-control" id="product_description" name="product_description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                <?php
                                $categoriesResult->data_seek(0);
                                while ($category = $categoriesResult->fetch_assoc()) :
                                ?>
                                    <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image_file">Image File</label>
                            <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="category_name">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>

<?php
$conn->close();
?>
