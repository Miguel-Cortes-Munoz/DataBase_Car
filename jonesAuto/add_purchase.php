<?php
include 'db_connect.php';
include 'functions.php';

$success = "";
$error   = "";

# Fetch employee options for the dropdown to assign who made the purchase
$employee_options = fetch_options($conn,
    "SELECT Employee_ID, CONCAT('#', Employee_ID, ' — ', Last_Name, ', ', First_Name) AS name FROM Employee ORDER BY Last_Name",
    'Employee_ID', 'name'
);

# Define condition options for the dropdown
$condition_options = [
    ['value' => 'Used - Like New', 'label' => 'Used - Like New'],
    ['value' => 'Used - Good',     'label' => 'Used - Good'],
    ['value' => 'Used - Fair',     'label' => 'Used - Fair'],
    ['value' => 'Used - Poor',     'label' => 'Used - Poor'],
];

# Handle form submission to record a new car purchase using prepared statements for security
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $make           = $_POST['make'];
    $model          = $_POST['model'];
    $year           = $_POST['year'];
    $color          = $_POST['color'];
    $miles          = $_POST['miles'];
    $book_price     = $_POST['book_price'];
    $price_paid     = $_POST['price_paid'];
    $purchase_date  = $_POST['purchase_date'];
    $buy_location   = $_POST['Buy_Location'];
    $auction        = isset($_POST['Auction']) ? 1 : 0;
    $employee_id    = intval($_POST['employee_id']);
    $condition_desc = $_POST['Condition_Desc'];

    # Insert the new car into the Car table and then record the purchase in the Purchase table
    $car_sql = "INSERT INTO car (make, model, year, color, miles, book_price, Condition_Desc)
                VALUES ('$make', '$model', '$year', '$color', '$miles', '$book_price', '$condition_desc')";

    # After inserting the car, get the generated Car_ID to link it in the Purchase record
    if (mysqli_query($conn, $car_sql)) {
        $car_id = mysqli_insert_id($conn);
        # Now insert the purchase record with the new Car_ID
        $purchase_sql = "INSERT INTO purchase (car_id, employee_id, Auction, price_paid, Buy_Location, Purchase_Date)
                         VALUES ('$car_id', '$employee_id', '$auction', '$price_paid', '$buy_location', '$purchase_date')";

        # Execute the purchase insertion and handle success or error
        if (mysqli_query($conn, $purchase_sql)) {
            header("Location: add_damage.php?car_id=" . $car_id);
            exit();
        } else {
            $error = "Purchase Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Car Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Record Car Purchase</title></head>
<body>
<h2>Record Car Purchase</h2>
<?php show_messages($success, $error); ?>

<form method="POST" action="">
    <?php
        echo "<label>Employee:</label><br>";
        dropdown('employee_id', $employee_options);
        text_input('Make',  'make');
        text_input('Model', 'model');
        number_input('Year',  'year', '', '1', '1900', true);
        text_input('Color', 'color', '', false);
        number_input('Miles', 'miles', '', '1', '0', false);
        echo "<label>Condition:</label><br>";
        dropdown('Condition_Desc', $condition_options);
        number_input('Book Price',  'book_price',    '', '0.01', '0', false);
        number_input('Price Paid',  'price_paid',    '', '0.01', '0', true);
        date_input('Purchase Date', 'purchase_date');
        text_input('Buy Location',  'Buy_Location',  '', false);
    ?>
    <label>Auction:</label><br>
    <input type="checkbox" name="Auction" value="1"> Yes<br><br>

    <?php submit_button('Submit Purchase'); ?>
</form>

<?php back_button(); ?>
</body>
</html>