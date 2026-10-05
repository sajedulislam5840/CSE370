<?php
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Employee') {
    header("Location: index.php");
    exit();
}

$emp_id = $_SESSION['user_id'];

// Fetching employee info
$stmt = $conn->prepare("SELECT Name, Phone FROM employee WHERE Employee_id = ?");
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$employee = $stmt->get_result()->fetch_assoc();

// Generate Bill 
if (isset($_POST['generate_bill'])) {
    $meter_no = $_POST['meter_no'];
    $current_reading = intval($_POST['current_reading']);

    // fetch meter & customer info
    $info_query = $conn->query("SELECT m.Customer_id, c.Customer_type FROM meter m JOIN customer c ON m.Customer_id = c.Customer_id WHERE m.Meter_no = $meter_no AND m.Employee_id = $emp_id");
    
    if ($info_query->num_rows == 0) {
        $err = "Invalid meter selected or meter is not assigned to you.";
    } else {
        $info = $info_query->fetch_assoc();
        $cust_id = $info['Customer_id'];
        $cust_type = $info['Customer_type'];

        // Fetch prev reading and 28days lock
        $last_bill_query = $conn->query("SELECT Current_reading, Date FROM bill WHERE Meter_no = $meter_no ORDER BY Date DESC LIMIT 1");
        
        if ($last_bill_query->num_rows > 0) {
            $last_bill = $last_bill_query->fetch_assoc();
            $prev_reading = $last_bill['Current_reading'];
            $last_date = $last_bill['Date'];
        } else {
            $prev_reading = 0; // first bill
            $last_date = '2000-01-01'; // dummy dating
        }

        $days_diff = floor((strtotime(date('Y-m-d')) - strtotime($last_date)) / (60 * 60 * 24));

        //  checks of constraint
        if ($current_reading < $prev_reading) {
            $err = "Current reading ($current_reading) cannot be less than previous reading ($prev_reading).";
        } elseif ($days_diff < 28 && $last_date != '2000-01-01') {
            $err = "Safety Lock Active: Only $days_diff days have passed since the last bill. Minimum 28 days required.";
        } else {
            //  calculate bill
            $consumed_unit = $current_reading - $prev_reading;
            
            // price slab
            $tariff_query = $conn->query("SELECT * FROM tariff WHERE Customer_type = '$cust_type' AND Max_consumption >= $consumed_unit ORDER BY Max_consumption ASC LIMIT 1");
            $tariff = $tariff_query->fetch_assoc();

            $unit_cost = $tariff['Unit_cost'];
            $base_charge = ($consumed_unit * $unit_cost);
            $vat_amt = $base_charge * ($tariff['VAT'] / 100);
            $demand_charge = $tariff['Demand_Charge'];
            $meter_rent = $tariff['Meter_rent'];
            
            $total_amount = $base_charge + $vat_amt + $demand_charge + $meter_rent;

            // bill insert
            $insert_stmt = $conn->prepare("INSERT INTO bill (Meter_no, Employee_id, Date, Prev_reading, Current_reading, Consumed_unit, Unit_cost, VAT_amount, Demand_charge, Total_amount, Paid_status) VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, 'Unpaid')");
            $insert_stmt->bind_param("iiiiidddd", $meter_no, $emp_id, $prev_reading, $current_reading, $consumed_unit, $unit_cost, $vat_amt, $demand_charge, $total_amount);
            
            if ($insert_stmt->execute()) {
                $new_bill_id = $insert_stmt->insert_id;
                // notification
                $conn->query("INSERT INTO notification (Customer_id, Bill_id, Notification_type, Message) VALUES ($cust_id, $new_bill_id, 'New Bill Generated', 'A new bill of BDT " . number_format($total_amount, 2) . " has been generated for your meter.')");
                
                $msg = "Bill successfully generated for Meter $meter_no! Total: BDT " . number_format($total_amount, 2);
            } else {
                $err = "Database error while saving the bill.";
            }
        }
    }
}

// assigned meter for this eomplyee
$assigned_meters = $conn->query("
    SELECT m.Meter_no, m.Model_name, c.Name as Customer_Name, c.Address, 
           COALESCE(MAX(b.Date), 'Never') as Last_Reading_Date 
    FROM meter m 
    JOIN customer c ON m.Customer_id = c.Customer_id 
    LEFT JOIN bill b ON m.Meter_no = b.Meter_no 
    WHERE m.Employee_id = $emp_id 
    GROUP BY m.Meter_no
");

// bills made by this employee
$generated_bills = $conn->query("SELECT * FROM bill WHERE Employee_id = $emp_id ORDER BY Date DESC LIMIT 15");


$meter_options = "";
$assigned_meters->data_seek(0);
while($m = $assigned_meters->fetch_assoc()) {
    $meter_options .= "<option value='{$m['Meter_no']}'>Meter {$m['Meter_no']} ({$m['Customer_Name']})</option>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee Portal - SparkEnergies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">⚡ SparkEnergies (Staff)</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">Agent: <?php echo $employee['Name']; ?></span>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <?php if(isset($msg)) echo "<div class='alert alert-success shadow-sm'>$msg</div>"; ?>
    <?php if(isset($err)) echo "<div class='alert alert-danger shadow-sm'>$err</div>"; ?>

    <div class="row">
        <!-- LEFT COLUMN: Profile & Generator -->
        <div class="col-md-4">
            
            <div class="card shadow-sm border-0 mb-4 bg-dark text-white p-3">
                <h5 class="text-muted mb-1">Employee Profile</h5>
                <h4 class="fw-bold mb-0"><?php echo $employee['Name']; ?></h4>
                <p class="mb-0 text-success fw-bold">📞 <?php echo $employee['Phone']; ?></p>
            </div>

            <!-- Bill Generation Form -->
            <div class="card shadow-sm border-0 mb-4 border-success border-top border-4">
                <div class="card-header bg-white fw-bold">Generate New Bill</div>
                <div class="card-body">
                    <?php if($assigned_meters->num_rows > 0): ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Select Assigned Meter</label>
                                <select name="meter_no" class="form-select" required>
                                    <option value="">Choose a meter...</option>
                                    <?php echo $meter_options; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Current Meter Reading (Units)</label>
                                <input type="number" name="current_reading" class="form-control" placeholder="e.g. 1542" required>
                                <small class="text-muted text-warning">System will auto-fetch previous reading.</small>
                            </div>
                            <button type="submit" name="generate_bill" class="btn btn-success w-100 fw-bold">Generate & Send Bill</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">No active meters assigned to your route.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Assigned Route & History -->
        <div class="col-md-8">
            
            <!-- Assigned Route -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">My Assigned Route (Meters)</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Meter No</th>
                                <th>Customer Name</th>
                                <th>Address</th>
                                <th>Last Reading Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $assigned_meters->data_seek(0);
                            while($m = $assigned_meters->fetch_assoc()): 
                                // new
                                $days_passed = "N/A";
                                $row_class = ""; 

                                if($m['Last_Reading_Date'] != 'Never') {
                                    $last_date_ts = strtotime($m['Last_Reading_Date']);
                                    $today_ts = time(); 
                                    $diff = $today_ts - $last_date_ts;
                                    $days_passed = floor($diff / (60 * 60 * 24));

                                    if ($days_passed >= 28) {
                                        $row_class = "table-danger"; 
                                    }
                                }
                            ?>
                            <tr class="<?php echo $row_class; ?>">
                                <td><strong><?php echo $m['Meter_no']; ?></strong><br><small class="text-muted"><?php echo $m['Model_name']; ?></small></td>
                                <td><?php echo $m['Customer_Name']; ?></td>
                                <td><?php echo $m['Address']; ?></td>
                                <td>
                                    <?php if($m['Last_Reading_Date'] == 'Never'): ?>
                                        <span class="badge bg-warning text-dark">Never Billed</span>
                                    <?php else: ?>
                                        <?php echo date("M j, Y", strtotime($m['Last_Reading_Date'])); ?>
                                        <br><small class="fw-bold text-danger">(<?php echo $days_passed; ?> days ago)</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if($assigned_meters->num_rows == 0) echo "<tr><td colspan='4' class='text-center py-3'>No meters assigned.</td></tr>"; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recently Generated Bills -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Recently Generated Bills (By You)</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Bill ID</th>
                                <th>Date</th>
                                <th>Meter</th>
                                <th>Units Consumed</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($b = $generated_bills->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $b['Bill_id']; ?></td>
                                <td><?php echo date("M j, Y", strtotime($b['Date'])); ?></td>
                                <td><?php echo $b['Meter_no']; ?></td>
                                <td><?php echo $b['Consumed_unit']; ?></td>
                                <td><strong>BDT <?php echo number_format($b['Total_amount'], 2); ?></strong></td>
                                <td>
                                    <?php if($b['Paid_status'] == 'Paid'): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Unpaid</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if($generated_bills->num_rows == 0) echo "<tr><td colspan='6' class='text-center py-3'>No bills generated yet.</td></tr>"; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>