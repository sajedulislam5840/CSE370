<?php
require 'db.php';

// Kick out anyone who isn't logged in as an Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: index.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Fetch Admin Info
$stmt = $conn->prepare("SELECT Username FROM admin WHERE Admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

// --- ACTION LOGIC: Add Balance ---
if (isset($_POST['add_balance'])) {
    $cust_id = $_POST['cust_id'];
    $amount = floatval($_POST['amount']);

    $conn->query("UPDATE customer SET Balance = Balance + $amount WHERE Customer_id = $cust_id");
    if ($conn->affected_rows > 0) {
        $conn->query("INSERT INTO notification (Customer_id, Notification_type, Message) VALUES ($cust_id, 'Balance Recharge', 'Your account has been recharged with BDT " . number_format($amount, 2) . " by Admin.')");
        $msg = "Balance of BDT $amount added successfully to Customer ID: $cust_id.";
    } else {
        $err = "Customer ID not found.";
    }
}

// --- ACTION LOGIC: Add Employee ---
if (isset($_POST['add_employee'])) {
    $name = $_POST['emp_name'];
    $phone = $_POST['emp_phone'];
    $pass = $_POST['emp_pass'];

    $stmt = $conn->prepare("INSERT INTO employee (Name, Phone, Password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $phone, $pass);
    try {
        $stmt->execute();
        $msg = "New employee '$name' added successfully!";
    } catch (Exception $e) {
        $err = "Failed to add employee. Phone number might already exist.";
    }
}

// --- ACTION LOGIC: Add Meter to Inventory ---
if (isset($_POST['add_meter'])) {
    $meter_no = $_POST['meter_no'];
    $model_name = $_POST['model_name'];

    $stmt = $conn->prepare("INSERT INTO meter (Meter_no, Model_name, Meter_status) VALUES (?, ?, 'Available')");
    $stmt->bind_param("is", $meter_no, $model_name);
    try {
        $stmt->execute();
        $msg = "Meter #$meter_no added to inventory successfully!";
    } catch (Exception $e) {
        $err = "Failed to add meter. Meter Number might already exist.";
    }
}

// --- DATA FETCHING FOR TABS ---
$customers = $conn->query("SELECT * FROM customer ORDER BY Customer_id DESC");
$employees = $conn->query("SELECT * FROM employee ORDER BY Employee_id DESC");
$inventory = $conn->query("SELECT m.*, mm.Required_Customertype, mm.Phasetype FROM meter m JOIN meter_model mm ON m.Model_name = mm.Model_name ORDER BY m.Meter_status ASC, m.Meter_no DESC");
$pending_bills = $conn->query("SELECT b.Bill_id, b.Date, b.Total_amount, c.Name, c.Customer_id, m.Meter_no FROM bill b JOIN meter m ON b.Meter_no = m.Meter_no JOIN customer c ON m.Customer_id = c.Customer_id WHERE b.Paid_status = 'Unpaid' ORDER BY b.Date ASC");
$meter_models = $conn->query("SELECT Model_name FROM meter_model");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Portal - SparkEnergies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">⚡ SparkEnergies (HQ)</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text text-white me-3">System Admin: <span class="fw-bold text-warning"><?php echo $admin['Username']; ?></span></span>
            <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <?php if(isset($msg)) echo "<div class='alert alert-success shadow-sm'>$msg</div>"; ?>
    <?php if(isset($err)) echo "<div class='alert alert-danger shadow-sm'>$err</div>"; ?>

    <!-- Dashboard Navigation Tabs -->
    <ul class="nav nav-pills mb-4 shadow-sm bg-white p-2 rounded" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#actions" type="button">🛠️ Quick Actions</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#customers" type="button">👥 Customers & Bills</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#inventory" type="button">📦 Meter Inventory</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#employees" type="button">👷 Employee Directory</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="adminTabsContent">

        <!-- TAB 1: QUICK ACTIONS (Forms) -->
        <div class="tab-pane fade show active" id="actions" role="tabpanel">
            <div class="row">
                <!-- Recharge Card -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-primary border-top border-4 mb-4">
                        <div class="card-header bg-white fw-bold">Recharge Customer Account</div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Customer ID</label>
                                    <input type="number" name="cust_id" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Amount (BDT)</label>
                                    <input type="number" step="0.01" name="amount" class="form-control" required>
                                </div>
                                <button type="submit" name="add_balance" class="btn btn-primary w-100 fw-bold">Process Recharge</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Add Employee Card -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-success border-top border-4 mb-4">
                        <div class="card-header bg-white fw-bold">Register New Employee</div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Full Name</label>
                                    <input type="text" name="emp_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Phone Number (Login ID)</label>
                                    <input type="text" name="emp_phone" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Temporary Password</label>
                                    <input type="password" name="emp_pass" class="form-control" required>
                                </div>
                                <button type="submit" name="add_employee" class="btn btn-success w-100 fw-bold">Create Account</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Add Meter Card -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-warning border-top border-4 mb-4">
                        <div class="card-header bg-white fw-bold">Add Meter to Inventory</div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">New Meter Number</label>
                                    <input type="number" name="meter_no" class="form-control" placeholder="e.g. 1005" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Meter Model</label>
                                    <select name="model_name" class="form-select" required>
                                        <option value="">Select Blueprint...</option>
                                        <?php while($model = $meter_models->fetch_assoc()): ?>
                                            <option value="<?php echo $model['Model_name']; ?>"><?php echo $model['Model_name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <button type="submit" name="add_meter" class="btn btn-warning text-dark w-100 fw-bold">Stock Meter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: CUSTOMERS & PENDING BILLS -->
        <div class="tab-pane fade" id="customers" role="tabpanel">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-danger text-white fw-bold">Unpaid Bills Queue</div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0 align-middle small">
                                <thead class="table-light">
                                    <tr><th>Bill ID</th><th>Date</th><th>Customer</th><th>Meter No</th><th>Amount Due</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    <?php while($pb = $pending_bills->fetch_assoc()): ?>
                                    <tr>
                                        <td>#<?php echo $pb['Bill_id']; ?></td>
                                        <td><?php echo date("M j, Y", strtotime($pb['Date'])); ?></td>
                                        <td><?php echo $pb['Name']; ?> (ID: <?php echo $pb['Customer_id']; ?>)</td>
                                        <td><?php echo $pb['Meter_no']; ?></td>
                                        <td class="fw-bold text-danger">BDT <?php echo number_format($pb['Total_amount'], 2); ?></td>
                                        <td><span class="badge bg-danger">Pending Payment</span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php if($pending_bills->num_rows == 0) echo "<tr><td colspan='6' class='text-center py-3'>No pending bills! All customers are fully paid.</td></tr>"; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-bold">Registered Customers Directory</div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0 align-middle small">
                                <thead class="table-light">
                                    <tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Type</th><th>Wallet Balance</th></tr>
                                </thead>
                                <tbody>
                                    <?php while($c = $customers->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $c['Customer_id']; ?></td>
                                        <td class="fw-bold"><?php echo $c['Name']; ?></td>
                                        <td><?php echo $c['Phone']; ?></td>
                                        <td><?php echo $c['Email']; ?></td>
                                        <td><span class="badge bg-info text-dark"><?php echo $c['Customer_type']; ?></span></td>
                                        <td class="fw-bold text-success">BDT <?php echo number_format($c['Balance'], 2); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: METER INVENTORY -->
        <div class="tab-pane fade" id="inventory" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Global Meter Database</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle small">
                        <thead class="table-light">
                            <tr><th>Meter No</th><th>Model Blueprint</th><th>Phase</th><th>Target Customer</th><th>Assigned Customer ID</th><th>Assigned Employee ID</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php while($inv = $inventory->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold"><?php echo $inv['Meter_no']; ?></td>
                                <td><?php echo $inv['Model_name']; ?></td>
                                <td><?php echo $inv['Phasetype']; ?> Phase</td>
                                <td><?php echo $inv['Required_Customertype']; ?></td>
                                <td><?php echo $inv['Customer_id'] ? $inv['Customer_id'] : '-'; ?></td>
                                <td><?php echo $inv['Employee_id'] ? $inv['Employee_id'] : '-'; ?></td>
                                <td>
                                    <?php if($inv['Meter_status'] == 'Available'): ?>
                                        <span class="badge bg-success">In Stock (Available)</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Deployed (Assigned)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 4: EMPLOYEE DIRECTORY -->
        <div class="tab-pane fade" id="employees" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Active Staff Members</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 align-middle small">
                        <thead class="table-light">
                            <tr><th>Employee ID</th><th>Full Name</th><th>Phone (Login ID)</th></tr>
                        </thead>
                        <tbody>
                            <?php while($emp = $employees->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold text-primary">EMP-<?php echo str_pad($emp['Employee_id'], 3, "0", STR_PAD_LEFT); ?></td>
                                <td class="fw-bold"><?php echo $emp['Name']; ?></td>
                                <td><?php echo $emp['Phone']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>