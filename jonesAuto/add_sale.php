<?php
include 'db_connect.php';
include 'functions.php';

$error   = "";
$success = "";
# Get customer_id from URL if available to pre-select in the form
$customer_id_from_url = intval($_GET['customer_id'] ?? 0);

# If customer_id is provided, fetch the customer details for display
$customer_from_url = null;
if ($customer_id_from_url > 0) {
    $stmt = $conn->prepare("SELECT First_Name, Last_Name FROM Customer WHERE Customer_ID = ?");
    $stmt->bind_param("i", $customer_id_from_url);
    $stmt->execute();
    $customer_from_url = $stmt->get_result()->fetch_assoc();
}

# Fetch options for dropdowns
$customer_options = fetch_options($conn,
    "SELECT Customer_ID, CONCAT('#', Customer_ID, ' — ', Last_Name, ', ', First_Name) AS label
     FROM Customer ORDER BY Last_Name",
    'Customer_ID', 'label'
);

# Fetch employee options for the dropdown to assign who made the sale
$employee_options = fetch_options($conn,
    "SELECT Employee_ID, CONCAT('#', Employee_ID, ' — ', Last_Name, ', ', First_Name) AS name FROM Employee ORDER BY Last_Name",
    'Employee_ID', 'name'
);

# Fetch car options for the dropdown, excluding cars already sold
$car_options = fetch_options($conn,
    "SELECT Car_ID, CONCAT('#', Car_ID, ' — ', Year, ' ', Make, ' ', Model) AS label
     FROM Car WHERE Car_ID NOT IN (SELECT Car_ID FROM Sale) ORDER BY Car_ID ASC",
    'Car_ID', 'label'
);

#run if submited with post method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    # Get form data and validate as needed, using prepared statements for security
    $customer_id = intval($_POST['manual_customer_id']) ?: intval($_POST['customer_id_hidden']);
    $employee_id = intval($_POST['employee_id']);
    $car_id      = intval($_POST['car_id']);
    $sale_date   = $_POST['sale_date'];
    $sale_price  = $_POST['sale_price']      !== '' ? $_POST['sale_price']      : null;
    $financed    = $_POST['financed_amount'] !== '' ? $_POST['financed_amount'] : null;
    $downpayment = $_POST['downpayment']     !== '' ? $_POST['downpayment']     : null;
    $commission  = $_POST['commission']      !== '' ? $_POST['commission']      : null;
    
    # Validate required fields and handle errors
    if ($customer_id === 0)  $error = "A valid Customer ID is required.";
    elseif ($employee_id === 0) $error = "Please select an employee.";
    elseif ($car_id === 0)   $error = "Please select a car.";
    else {
        #
        $stmt = $conn->prepare("INSERT INTO Sale
            (Customer_ID, Employee_ID, Car_ID, Sale_Date, Sale_Price, Financed_Amount, downpayment, commission)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisdddd",
            $customer_id, $employee_id, $car_id,
            $sale_date, $sale_price, $financed, $downpayment, $commission
        );

        # Execute the statement and handle success or error
        if ($stmt->execute()) {
        $sale_id = $conn->insert_id;
        # Redirect to add_warranty.php with the new sale_id and employee_id for context
        header("Location: warranties.php?sale_id=" . $sale_id . "&employee_id=" . $employee_id);
        exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Sale</title></head>
<body>
<h2>Add New Sale</h2>
<?php show_messages($success, $error); ?>

<?php if ($success): ?>
    <a href="add_sale.php">Add another sale</a> |
    <a href="index.php">Back to Home</a>
<?php else: ?>

<form method="POST" action="">
    <!-- If customer was pre-selected from URL, show that info and use hidden input to pass it along -->
    <?php if ($customer_from_url): ?>
        <p><strong>Customer from previous step:</strong>
        <?= htmlspecialchars($customer_from_url['First_Name'] . ' ' . $customer_from_url['Last_Name']) ?>
        (ID: <?= $customer_id_from_url ?>)</p>
        <input type="hidden" name="customer_id_hidden" value="<?= $customer_id_from_url ?>">
    <?php else: ?>
        <input type="hidden" name="customer_id_hidden" value="0">
    <?php endif; ?>

    <?php
        echo "<label>Select Customer:</label><br>";
        dropdown('manual_customer_id', $customer_options, $customer_id_from_url, false);
        echo "<label>Employee (Salesperson):</label><br>";
        dropdown('employee_id', $employee_options);
        echo "<label>Car:</label><br>";
        dropdown('car_id', $car_options);
        date_input('Sale Date', 'sale_date');
        number_input('Sale Price',      'sale_price',      '', '0.01', '0',);
        number_input('Financed Amount (optional)', 'financed_amount', '', '0.01', '0');
        number_input('Down Payment (optional)',    'downpayment',     '', '0.01', '0');
        number_input('Commission (optional)',      'commission',      '', '0.01', '0');
        submit_button('Record Sale');

        
    ?>
</form>

<?php endif; ?>



<?php back_button(); ?>
</body>
</html>