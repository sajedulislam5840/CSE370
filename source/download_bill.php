<?php
require 'db.php';

// Ensure only logged-in customers can view their bills
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Customer') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Error: No Bill ID provided.");
}

$bill_id = $_GET['id'];
$cust_id = $_SESSION['user_id'];

// Securely fetch the bill, ensuring it belongs to the logged-in customer
$stmt = $conn->prepare("
    SELECT b.*, m.Model_name, c.Name, c.Address, c.Phone, c.Customer_type 
    FROM bill b 
    JOIN meter m ON b.Meter_no = m.Meter_no 
    JOIN customer c ON m.Customer_id = c.Customer_id 
    WHERE b.Bill_id = ? AND c.Customer_id = ?
");
$stmt->bind_param("ii", $bill_id, $cust_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Bill not found or access denied.");
}

$bill = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice #<?php echo $bill['Bill_id']; ?> - SparkEnergies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* This hides the "Print" button when the PDF is actually generated */
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-light p-4">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9 card shadow-lg p-5">
            
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary mb-0">⚡ SparkEnergies</h2>
                    <p class="text-muted mb-0">Reliable Power, Brighter Future.</p>
                </div>
                <div class="text-end">
                    <h1 class="text-uppercase text-muted" style="letter-spacing: 2px;">Invoice</h1>
                    <p class="mb-0 fw-bold">Bill ID: #<?php echo str_pad($bill['Bill_id'], 5, "0", STR_PAD_LEFT); ?></p>
                    <p class="mb-0">Date: <?php echo date("F j, Y", strtotime($bill['Date'])); ?></p>
                </div>
            </div>
            
            <hr>

            <!-- Customer & Meter Details -->
            <div class="row mb-4 mt-4">
                <div class="col-sm-6">
                    <h6 class="text-muted text-uppercase fw-bold">Billed To:</h6>
                    <h5 class="fw-bold mb-1"><?php echo $bill['Name']; ?></h5>
                    <p class="mb-0"><?php echo $bill['Address']; ?></p>
                    <p class="mb-0">Phone: <?php echo $bill['Phone']; ?></p>
                    <p class="mb-0">Type: <span class="badge bg-info text-dark"><?php echo $bill['Customer_type']; ?></span></p>
                </div>
                <div class="col-sm-6 text-end">
                    <h6 class="text-muted text-uppercase fw-bold">Connection Details:</h6>
                    <p class="mb-0"><strong>Meter No:</strong> <?php echo $bill['Meter_no']; ?></p>
                    <p class="mb-0"><strong>Model:</strong> <?php echo $bill['Model_name']; ?></p>
                    <p class="mb-0"><strong>Status:</strong> 
                        <?php if($bill['Paid_status'] == 'Paid'): ?>
                            <span class="text-success fw-bold">PAID</span>
                        <?php else: ?>
                            <span class="text-danger fw-bold">UNPAID</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- Billing Breakdown Table -->
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th>Description</th>
                        <th class="text-end">Reading / Units</th>
                        <th class="text-end">Rate (BDT)</th>
                        <th class="text-end">Amount (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Previous Meter Reading</td>
                        <td class="text-end"><?php echo $bill['Prev_reading']; ?></td>
                        <td class="text-end">-</td>
                        <td class="text-end">-</td>
                    </tr>
                    <tr>
                        <td>Current Meter Reading</td>
                        <td class="text-end"><?php echo $bill['Current_reading']; ?></td>
                        <td class="text-end">-</td>
                        <td class="text-end">-</td>
                    </tr>
                    <tr>
                        <td><strong>Total Units Consumed</strong></td>
                        <td class="text-end"><strong><?php echo $bill['Consumed_unit']; ?></strong></td>
                        <td class="text-end"><?php echo $bill['Unit_cost']; ?></td>
                        <td class="text-end"><?php echo number_format($bill['Consumed_unit'] * $bill['Unit_cost'], 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end">Demand Charge</td>
                        <td class="text-end"><?php echo $bill['Demand_charge']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end">VAT Amount</td>
                        <td class="text-end"><?php echo $bill['VAT_amount']; ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="table-dark text-white">
                        <td colspan="3" class="text-end fw-bold">TOTAL AMOUNT PAYABLE</td>
                        <td class="text-end fw-bold">BDT <?php echo number_format($bill['Total_amount'], 2); ?></td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-center text-muted small mt-5">
                <p>If you have any questions about this invoice, please contact SparkEnergies Support.</p>
                <p class="fw-bold">Thank you for your business!</p>
            </div>

            <!-- Action Buttons (Hidden when printing) -->
            <div class="text-center mt-4 no-print">
                <button onclick="window.print()" class="btn btn-primary btn-lg fw-bold px-5">🖨️ Save as PDF / Print</button>
                <a href="customer_dashboard.php" class="btn btn-secondary btn-lg px-4 ms-2">Back to Dashboard</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>