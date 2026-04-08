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

# Handle form submission to record a new payment using prepared statements for security
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sale_id        = intval($_POST['sale_id']);
    $payment_date   = $_POST['payment_date'];
    $payment_amount = $_POST['payment_amount'];

    if ($sale_id === 0) {
        $error = "Please select a sale.";
    } else {
        $stmt = $conn->prepare("INSERT INTO payments (Sale_ID, Payment_Date, Payment_Amount) VALUES (?, ?, ?)");
        $stmt->bind_param("isd", $sale_id, $payment_date, $payment_amount);

        # Execute the statement and handle success or error
        if ($stmt->execute()) {
            $success = "Payment recorded successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Record Payment</title></head>
<body>

<h2>Record Payment</h2>

<?php show_messages($success, $error); ?>

<?php if (!$success): ?>
<form method="POST" action="">
    <?php
        echo "<label>Sale:</label><br>";
        dropdown('sale_id', $sale_options);
        date_input('Payment Date', 'payment_date');
        number_input('Payment Amount', 'payment_amount', '', '0.01', '0', true);
        submit_button('Record Payment');
    ?>
</form>
<?php endif; ?>

<?php back_button(); ?>
</body>
</html>