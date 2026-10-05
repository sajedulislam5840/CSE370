<?php
require 'db.php';

// Kick out anyone who isn't logged in as a Customer
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Customer') {
    header("Location: index.php");
    exit();
}

$cust_id = $_SESSION['user_id'];

// Fetch basic customer info
$stmt = $conn->prepare("SELECT Name, Customer_type, Balance, Address, Phone FROM customer WHERE Customer_id = ?");
$stmt->bind_param("i", $cust_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();
$cust_type = $customer['Customer_type'];

// --- ACTION LOGIC: Apply for Meter ---
// --- ACTION LOGIC: Apply for Meter ---
if (isset($_POST['apply_meter'])) {
    // Find an available meter that matches their customer type
    $find_meter = $conn->prepare("
        SELECT m.Meter_no, mm.Subscription_charge 
        FROM meter m 
        JOIN meter_model mm ON m.Model_name = mm.Model_name 
        WHERE m.Meter_status = 'Available' AND mm.Required_Customertype = ? 
        LIMIT 1
    ");
    $find_meter->bind_param("s", $cust_type);
    $find_meter->execute();
    $avail_meter = $find_meter->get_result()->fetch_assoc();

    if (!$avail_meter) {
        $err = "Sorry, there are no available meters for the '$cust_type' category in stock right now.";
    } elseif ($customer['Balance'] < $avail_meter['Subscription_charge']) {
        $err = "Insufficient balance. A '$cust_type' meter requires a subscription charge of BDT " . $avail_meter['Subscription_charge'];
    } else {
        // Fetch a random employee from the database
        $random_emp_query = $conn->query("SELECT Employee_id FROM employee ORDER BY RAND() LIMIT 1");
        
        if ($random_emp_query->num_rows > 0) {
            $random_emp = $random_emp_query->fetch_assoc();
            $emp_id = $random_emp['Employee_id'];
            
            // Assign the meter and deduct balance
            $meter_no = $avail_meter['Meter_no'];
            $charge = $avail_meter['Subscription_charge'];
            
            // Update meter with BOTH Customer_id and Employee_id
            $conn->query("UPDATE meter SET Customer_id = $cust_id, Employee_id = $emp_id, Meter_status = 'Assigned' WHERE Meter_no = $meter_no");
            $conn->query("UPDATE customer SET Balance = Balance - $charge WHERE Customer_id = $cust_id");
            $conn->query("INSERT INTO notification (Customer_id, Notification_type, Message) VALUES ($cust_id, 'Meter Assigned', 'Meter No: $meter_no has been assigned to your account.')");
            
            $msg = "Success! Meter No: $meter_no was assigned to you. BDT $charge was deducted.";
            $customer['Balance'] -= $charge; // Update local variable for UI
        } else {
            $err = "System Error: Cannot assign meter. No employees exist in the system.";
        }
    }
}

// --- ACTION LOGIC: Pay Bill ---
if (isset($_POST['pay_bill'])) {
    $bill_id = $_POST['bill_id'];
    $amount = $_POST['amount'];

    if ($customer['Balance'] >= $amount) {
        $conn->query("UPDATE customer SET Balance = Balance - $amount WHERE Customer_id = $cust_id");
        $conn->query("UPDATE bill SET Paid_status = 'Paid' WHERE Bill_id = $bill_id");
        $conn->query("INSERT INTO payment (Bill_id, Date, Status, Amount) VALUES ($bill_id, CURDATE(), 'Success', $amount)");
        $conn->query("INSERT INTO notification (Customer_id, Bill_id, Notification_type, Message) VALUES ($cust_id, $bill_id, 'Payment Success', 'Bill #$bill_id paid successfully.')");
        $msg = "Bill paid successfully! BDT $amount was deducted from your balance.";
        $customer['Balance'] -= $amount; // Update local variable for UI
    } else {
        $err = "Insufficient balance to pay this bill.";
    }
}

// Fetch assigned meter info (if any)
$meter_query = $conn->query("SELECT m.Meter_no, m.Model_name FROM meter m WHERE m.Customer_id = $cust_id");
$assigned_meter = $meter_query->fetch_assoc();

// Fetch their specific Tariffs
$tariffs = $conn->query("SELECT * FROM tariff WHERE Customer_type = '$cust_type' ORDER BY Max_consumption ASC");

// Fetch Bills
$bills = $conn->query("SELECT * FROM bill b JOIN meter m ON b.Meter_no = m.Meter_no WHERE m.Customer_id = $cust_id ORDER BY b.Date DESC");

// Fetch Notifications
$notifs = $conn->query("SELECT * FROM notification WHERE Customer_id = $cust_id ORDER BY Notification_id DESC LIMIT 5");

// Fetch Graph Data (Last 6 Months)
$graph_query = $conn->prepare("
    SELECT DATE_FORMAT(b.Date, '%b %Y') AS Month, SUM(b.Consumed_unit) AS Total_Units 
    FROM bill b 
    JOIN meter m ON b.Meter_no = m.Meter_no 
    WHERE m.Customer_id = ? 
    GROUP BY Month 
    ORDER BY b.Date ASC 
    LIMIT 6
");
$graph_query->bind_param("i", $cust_id);
$graph_query->execute();
$graph_result = $graph_query->get_result();

$months = [];
$units = [];
while($row = $graph_result->fetch_assoc()) {
    $months[] = $row['Month'];
    $units[] = $row['Total_Units'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer Portal - SparkEnergies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">⚡ SparkEnergies</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">Welcome, <?php echo $customer['Name']; ?></span>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <?php if(isset($msg)) echo "<div class='alert alert-success shadow-sm'>$msg</div>"; ?>
    <?php if(isset($err)) echo "<div class='alert alert-danger shadow-sm'>$err</div>"; ?>

    <div class="row">
        <!-- LEFT COLUMN: Profile & Meter -->
        <div class="col-md-4">
            
            <!-- Balance Card -->
            <div class="card shadow-sm border-0 mb-4 bg-dark text-white text-center p-3">
                <h5 class="text-muted">Account Balance</h5>
                <h2 class="text-success fw-bold">BDT <?php echo number_format($customer['Balance'], 2); ?></h2>
            </div>

            <!-- Profile & Meter Info -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">My Profile & Connection</div>
                <div class="card-body">
                    <p><strong>Customer Type:</strong> <span class="badge bg-info text-dark"><?php echo $cust_type; ?></span></p>
                    <p><strong>Phone:</strong> <?php echo $customer['Phone']; ?></p>
                    <p><strong>Address:</strong> <?php echo $customer['Address']; ?></p>
                    <hr>
                    <?php if ($assigned_meter): ?>
                        <div class="alert alert-success mb-0">
                            <strong>Meter No:</strong> <?php echo $assigned_meter['Meter_no']; ?><br>
                            <strong>Model:</strong> <?php echo $assigned_meter['Model_name']; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">You do not have a meter assigned yet.</div>
                        <form method="POST">
                            <button type="submit" name="apply_meter" class="btn btn-primary w-100 fw-bold">Apply for <?php echo $cust_type; ?> Meter</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notifications -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Recent Notifications</div>
                <ul class="list-group list-group-flush">
                    <?php while($n = $notifs->fetch_assoc()): ?>
                        <li class="list-group-item small text-muted">
                            <strong><?php echo $n['Notification_type']; ?>:</strong> <?php echo $n['Message']; ?>
                        </li>
                    <?php endwhile; ?>
                    <?php if($notifs->num_rows == 0) echo "<li class='list-group-item small text-muted'>No new notifications.</li>"; ?>
                </ul>
            </div>
        </div>

        <!-- RIGHT COLUMN: Tariffs & Billing -->
        <div class="col-md-8">
            
            <!-- Usage Statistics Graph -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">Usage Statistics (Last 6 Months)</div>
                <div class="card-body">
                    <?php if(empty($units)): ?>
                        <p class="text-muted text-center mb-0 py-4">No billing data available to generate graph.</p>
                    <?php else: ?>
                        <canvas id="usageChart" height="250"></canvas>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tariff Rates -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">My Assigned Tariff Rates (<?php echo $cust_type; ?>)</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Slab (Max Units)</th>
                                <th>Unit Cost</th>
                                <th>Demand Charge</th>
                                <th>VAT %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($t = $tariffs->fetch_assoc()): ?>
                            <tr>
                                <td>Up to <?php echo $t['Max_consumption'] == 9999 ? 'Unlimited' : $t['Max_consumption']; ?></td>
                                <td>BDT <?php echo $t['Unit_cost']; ?></td>
                                <td>BDT <?php echo $t['Demand_Charge']; ?></td>
                                <td><?php echo $t['VAT']; ?>%</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Billing History -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Billing History & Payments</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Units</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($b = $bills->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $b['Date']; ?></td>
                                <td><?php echo $b['Consumed_unit']; ?></td>
                                <td><strong>BDT <?php echo number_format($b['Total_amount'], 2); ?></strong></td>
                                <td>
                                    <?php if($b['Paid_status'] == 'Paid'): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($b['Paid_status'] == 'Unpaid'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="bill_id" value="<?php echo $b['Bill_id']; ?>">
                                        <input type="hidden" name="amount" value="<?php echo $b['Total_amount']; ?>">
                                        <button type="submit" name="pay_bill" class="btn btn-sm btn-success fw-bold">Pay Now</button>
                                    </form>
                                    <?php endif; ?>
                                    <!-- PDF Download Button -->
                                    <a href="download_bill.php?id=<?php echo $b['Bill_id']; ?>" target="_blank" class="btn btn-sm btn-outline-primary fw-bold ms-1">PDF</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if($bills->num_rows == 0) echo "<tr><td colspan='5' class='text-center text-muted py-3'>No bills generated yet.</td></tr>"; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Chart.js Library and Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    <?php if(!empty($units)): ?>
    const ctx = document.getElementById('usageChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Units Consumed',
                data: <?php echo json_encode($units); ?>,
                borderColor: '#0d6efd', 
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#0d6efd',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    title: { display: true, text: 'Consumed Units' }
                }
            }
        }
    });
    <?php endif; ?>
</script>
</body>
</html>