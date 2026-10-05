
<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $identifier = $_POST['identifier']; // Username, Phone, or Email
    $password = $_POST['password'];

    if ($role == 'Admin') {
        $stmt = $conn->prepare("SELECT Admin_id as uid FROM admin WHERE Username=? AND Password=?");
    } elseif ($role == 'Customer') {
        $stmt = $conn->prepare("SELECT Customer_id as uid FROM customer WHERE Email=? AND Password=?");
    } else {
        $stmt = $conn->prepare("SELECT Employee_id as uid FROM employee WHERE Phone=? AND Password=?");
    }

    $stmt->bind_param("ss", $identifier, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['role'] = $role;
        $_SESSION['user_id'] = $res->fetch_assoc()['uid'];
        header("Location: " . strtolower($role) . "_dashboard.php");
        exit();
    } else {
        $err = "Invalid credentials. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - SparkEnergies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center vh-100">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 card shadow-lg border-0 p-4 rounded-3">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">SparkEnergies</h2>
                <p class="text-muted">Secure System Login</p>
            </div>

            <?php if(isset($err)) echo "<div class='alert alert-danger'>$err</div>"; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Login As</label>
                    <select name="role" class="form-select">
                        <option value="Customer">Customer</option>
                        <option value="Employee">Employee</option>
                        <option value="Admin">Admin / Owner</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Identifier</label>
                    <input type="text" name="identifier" class="form-control" placeholder="Email (Cust) / Phone (Emp) / Username" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Access Portal</button>
            </form>
            <div class="text-center mt-3">
                <small class="text-muted">New customer? <a href="signup.php" class="text-decoration-none">Apply for connection</a>.</small>
            </div>
        </div>
    </div>
</div>
</body>
</html>