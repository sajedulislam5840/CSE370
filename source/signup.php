
<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password']; // In a real app, use password_hash()
    $address = $_POST['address'];
    $type = $_POST['customer_type'];

    $stmt = $conn->prepare("INSERT INTO customer (Name, Phone, Email, Password, Address, Customer_type, Balance) VALUES (?, ?, ?, ?, ?, ?, 0.00)");
    $stmt->bind_param("ssssss", $name, $phone, $email, $password, $address, $type);

    try {
        $stmt->execute();
        $msg = "Account created successfully! You can now login.";
    } catch (Exception $e) {
        $err = "Registration failed. Email or Phone might already be in use.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up - SparkEnergies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 card shadow-lg border-0 p-4 rounded-3">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">SparkEnergies</h2>
                <p class="text-muted">Create your customer account</p>
            </div>
            
            <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg <br><a href='index.php'>Go to Login</a></div>"; ?>
            <?php if(isset($err)) echo "<div class='alert alert-danger'>$err</div>"; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Physical Address</label>
                    <input type="text" name="address" class="form-control" placeholder="House, Road, Area" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Customer Type (Permanent)</label>
                    <select name="customer_type" class="form-select" required>
                        <option value="">Select your category...</option>
                        <option value="Residential">Residential</option>
                        <option value="Corporate">Corporate</option>
                        <option value="Factory">Factory</option>
                        <option value="Construction">Construction</option>
                        <option value="Agriculture">Agriculture</option>
                        <option value="Educational_institute">Educational Institute</option>
                        <option value="Hospital">Hospital</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Create Account</button>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">Already have an account? <a href="index.php" class="text-decoration-none">Login here</a>.</small>
            </div>
        </div>
    </div>
</div>
</body>
</html>