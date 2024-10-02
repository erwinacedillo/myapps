<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="update_profile.php" method="post">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="middleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middleName" name="middleName" value="<?php echo htmlspecialchars($middleName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Photo Modal -->
<div class="modal fade" id="updatePhotoModal" tabindex="-1" aria-labelledby="updatePhotoModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePhotoModalLabel">Update Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="update_photo.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="photo" class="form-label">Choose Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <img id="preview" src="#" alt="Preview" style="max-width: 100%; max-height: 200px;">
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Photo</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Registration Modal -->
	<div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="registrationModalLabel">Sign Up</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body row">
					<div class="col-md-6">
						<form action="register.php" method="post" enctype="multipart/form-data">
							<div class="mb-3">
								<label for="firstName" class="form-label">First Name</label>
								<input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter your first name" required>
							</div>
							<div class="mb-3">
								<label for="middleName" class="form-label">Middle Name</label>
								<input type="text" class="form-control" id="middleName" name="middleName" placeholder="Enter your middle name">
							</div>
							<div class="mb-3">
								<label for="lastName" class="form-label">Last Name</label>
								<input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter your last name" required>
							</div>
							<div class="mb-3">
								<label for="email" class="form-label">Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
							</div>
							<div class="mb-3">
								<label for="password" class="form-label">Password</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
							</div>
					</div>
					<div class="col-md-6 text-center">
						<div class="mb-3">
							<label for="profilePicture" class="form-label">Profile Picture</label>
							<img id="preview" src="#" alt="Profile Picture Preview" class="img-thumbnail" style="max-width: 100%; max-height: 200px; display: none;">
						</div>
						<div class="mb-3">
							<input type="file" class="form-control" id="profilePicture" name="profilePicture" onchange="previewImage(event)" accept="image/*">
						</div>
					</div>
				</div>
				<div class="d-grid gap-2">
					<button type="submit" class="btn btn-primary">Register</button>
				</div>
				</form>
			</div>
		</div>
	</div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Log In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="login.php" method="post">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="loginEmail" name="loginEmail"
                                placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="loginPassword" name="loginPassword"
                                placeholder="Enter your password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Log In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Apply Seller Modal -->
    <div class="modal fade" id="applySellerModal" tabindex="-1" aria-labelledby="applySellerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applySellerModalLabel">Apply as a Seller</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="apply_seller.php" method="post">
                        <div class="mb-3">
                            <label for="businessName" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="businessName" name="businessName" required>
                        </div>
                        <div class="mb-3">
                            <label for="businessEmail" class="form-label">Business Email</label>
                            <input type="email" class="form-control" id="businessEmail" name="businessEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="businessAddress" class="form-label">Business Address</label>
                            <textarea class="form-control" id="businessAddress" name="businessAddress" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="businessPhone" class="form-label">Business Phone</label>
                            <input type="text" class="form-control" id="businessPhone" name="businessPhone" required>
                        </div>
                        <div class="mb-3">
                            <label for="businessDescription" class="form-label">Business Description</label>
                            <textarea class="form-control" id="businessDescription" name="businessDescription" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Apply Seller Modal -->
    <div class="modal fade" id="applySellerModal" tabindex="-1" aria-labelledby="applySellerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applySellerModalLabel">Apply as a Seller</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="apply_seller.php" method="post">
                        <div class="mb-3">
                            <label for="businessName" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="businessName" name="businessName" required>
                        </div>
                        <div class="mb-3">
                            <label for="businessEmail" class="form-label">Business Email</label>
                            <input type="email" class="form-control" id="businessEmail" name="businessEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="businessAddress" class="form-label">Business Address</label>
                            <textarea class="form-control" id="businessAddress" name="businessAddress" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="businessPhone" class="form-label">Business Phone</label>
                            <input type="text" class="form-control" id="businessPhone" name="businessPhone" required>
                        </div>
                        <div class="mb-3">
                            <label for="businessDescription" class="form-label">Business Description</label>
                            <textarea class="form-control" id="businessDescription" name="businessDescription" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>