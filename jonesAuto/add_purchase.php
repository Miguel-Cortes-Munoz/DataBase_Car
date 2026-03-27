<?php
include 'db_connect.php';

$success = "";

// This runs when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $make      = $_POST['make'];
    $model     = $_POST['model'];
    $year      = $_POST['year'];
    $color     = $_POST['color'];
    $miles     = $_POST['miles'];
    $price_paid = $_POST['price_paid'];
    $book_price = $_POST['book_price'];
    $purchase_date = $_POST['purchase_date'];

    $sql = "INSERT INTO purchases (make, model, year, color, miles, price_paid, book_price, purchase_date)
            VALUES ('$make', '$model', '$year', '$color', '$miles', '$price_paid', '$book_price', '$purchase_date')";

    if (mysqli_query($conn, $sql)) {
        $success = "Car purchase recorded successfully!";
    } else {
        $success = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Car Purchase</title>
</head>
<body>
    <h2>Record Car Purchase</h2>

    <?php if ($success) echo "<p>$success</p>"; ?>

    <form method="POST" action="">
        <label>Make:</label>
        <input type="text" name="make" required><br><br>

        <label>Model:</label>
        <input type="text" name="model" required><br><br>

        <label>Year:</label>
        <input type="number" name="year" required><br><br>

        <label>Color:</label>
        <input type="text" name="color"><br><br>

        <label>Miles:</label>
        <input type="number" name="miles"><br><br>

        <label>Book Price:</label>
        <input type="number" step="0.01" name="book_price"><br><br>

        <label>Price Paid:</label>
        <input type="number" step="0.01" name="price_paid" required><br><br>

        <label>Purchase Date:</label>
        <input type="date" name="purchase_date" required><br><br>

        <input type="submit" value="Submit Purchase">
    </form>
</body>
</html>