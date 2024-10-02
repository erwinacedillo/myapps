<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><b>DAVAO ONLINE STORE</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="product.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo "Welcome, $firstName $lastName"; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#updateModal">Profile</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#updatePhotoModal">Update Photo</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#applySellerModal">Start Selling</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <!-- Display user's photo or avatar -->
                        <?php if (!empty($photo)) : ?>
                        <img src="<?php echo htmlspecialchars($photo); ?>" alt="User Photo"
                            class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px; object-fit: cover; overflow: hidden;">
                        <?php else : ?>
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px; overflow: hidden;">
                            <i class="bi bi-person-fill text-light fs-4"></i>
                        </div>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>