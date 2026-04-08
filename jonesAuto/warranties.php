<?php
include 'db_connect.php';
include 'functions.php';

$success = "";
$error   = "";

# Fetch sale options for the dropdown, showing customer name for context
$sale_options = fetch_options($conn,
    "SELECT s.Sale_ID, CONCAT('Sale #', s.Sale_ID, ' — ', c.Last_Name, ', ', c.First_Name) AS label
     FROM Sale s JOIN Customer c ON s.Customer_ID = c.Customer_ID ORDER BY s.Sale_ID DESC",
    'Sale_ID', 'label'
);

# Fetch employee options for the dropdown to assign who handled the warranty
$employee_options = fetch_options($conn,
    "SELECT Employee_ID, CONCAT('#', Employee_ID, ' — ', Last_Name, ', ', First_Name) AS name FROM Employee ORDER BY Last_Name",
    'Employee_ID', 'name'
);


# Get employee_id and sale_id from URL if available to pre-select in the form
$employee_id_from_url = intval($_GET['employee_id'] ?? 0);


# If employee_id is provided, fetch the employee details for display
$employee_from_url = null;
if ($employee_id_from_url > 0) {
    $stmt = $conn->prepare("SELECT First_Name, Last_Name FROM Employee WHERE Employee_ID = ?");
    $stmt->bind_param("i", $employee_id_from_url);
    $stmt->execute();
    $employee_from_url = $stmt->get_result()->fetch_assoc();
}

# Get sale_id from URL if available to pre-select in the form
$sale_id_from_url = intval($_GET['sale_id'] ?? 0);

# If sale_id is provided, fetch the sale details for display
$sale_from_url = null;
if ($sale_id_from_url > 0) {
    $stmt = $conn->prepare("SELECT Sale_ID, CONCAT('Sale #', Sale_ID, ' — ', c.Last_Name, ', ', c.First_Name) AS label FROM Sale s JOIN Customer c ON s.Customer_ID = c.Customer_ID WHERE s.Sale_ID = ?");
    $stmt->bind_param("i", $sale_id_from_url);
    $stmt->execute();
    $sale_from_url = $stmt->get_result()->fetch_assoc();
}


# Handle form submission to add a warranty using prepared statements for security
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sale_id  = intval($_POST['sale_id']);
    $desc     = $_POST['warranty_desc'];
    $cost     = $_POST['warranty_cost'];
    $start    = $_POST['start_date'];
    $length   = intval($_POST['length_months']);
    $ded      = $_POST['deductible'];
    $coverage = $_POST['itemized_coverage'];
    $employee_id = intval($_POST['employee_id']);
    if ($sale_id === 0) {
        $error = "Please select a sale.";
    } else {
        $stmt = $conn->prepare("INSERT INTO warranties 
            (Sale_ID, Warranty_Desc, Warranty_Cost, Start_Date, Length_Months, deductible, itemized_coverage, Employee_ID)
            VALUES (?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->bind_param("isdsiddi", $sale_id, $desc, $cost, $start, $length, $ded, $coverage, $employee_id);
        
        # Execute the statement and handle success or error
        if ($stmt->execute()) {
            $success = "Warranty added!";
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}





?>
<!DOCTYPE html>
<html>
<head><title>Add Warranty</title></head>
<body>

<h2>Add Warranty</h2>

<?php show_messages($success, $error); ?>

<?php if (!$success): ?>
<form method="POST" action="">
            <!-- Hidden inputs to retain pre-selected sale if coming from add_sale.php -->
            <?php if ($sale_from_url): ?>
        <p><strong>Sale from previous step:</strong>
        <?= htmlspecialchars($sale_from_url['label']) ?></p>
        <input type="hidden" name="sale_id_hidden" value="<?= $sale_id_from_url ?>">
    <?php else: ?>
        <input type="hidden" name="sale_id_hidden" value="0">
    <?php endif; ?>
    
    <!-- Employee pre-selection if coming from add_sale.php -->
    <?php if ($employee_from_url): ?>
        <p><strong>Employee from previous step:</strong>
        <?= htmlspecialchars($employee_from_url['First_Name'] . ' ' . $employee_from_url['Last_Name']) ?>
        (ID: <?= $employee_id_from_url ?>)</p>
        <input type="hidden" name="employee_id_hidden" value="<?= $employee_id_from_url ?>">
    <?php else: ?>
        <input type="hidden" name="employee_id_hidden" value="0">
    <?php endif; ?>
    
        <?php
        echo "<label>Sale:</label><br>";
        dropdown('sale_id', $sale_options, $sale_id_from_url);
        echo "<label>Employee:</label><br>";
        dropdown('employee_id', $employee_options, $employee_id_from_url);
        text_input('Warranty Description', 'warranty_desc');
        number_input('Warranty Cost', 'warranty_cost', '', '0.01', '0', true);
        date_input('Start Date', 'start_date');
        number_input('Length (Months)', 'length_months', '', '1', '1', true);
        number_input('Deductible', 'deductible', '', '0.01', '0', true);
        textarea_input('Items Covered', 'itemized_coverage');
        submit_button('Add Warranty');
    ?>
</form>
<?php endif; ?>

<?php back_button(); ?>
</body>
</html> 