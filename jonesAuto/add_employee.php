<?php
include 'db_connect.php';
include 'functions.php';

$success = "";
$error   = "";
# Handle form submission to add a new employee using prepared statements for security
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $phone      = $_POST['phone'];
    $email      = $_POST['email'];
    $position   = $_POST['position'];

    $stmt = $conn->prepare("INSERT INTO Employee (First_Name, Last_Name, Phone_Number, Email, position)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $phone, $email, $position);
    # Execute the statement and handle success or error
    if ($stmt->execute()) {
        $success = "Employee added! (ID: " . $conn->insert_id . ")";
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Employee</title></head>
<body>
<h2>Add Employee</h2>
<?php show_messages($success, $error); ?>

<?php if (!$success): ?>
<form method="POST" action="">
    <?php
        text_input('First Name', 'first_name');
        text_input('Last Name',  'last_name');
        text_input('Phone Number', 'phone');
        text_input('Email', 'email', '', true, 'email');
        text_input('Position', 'position');
        submit_button('Add Employee');
    ?>
</form>
<?php endif; ?>

<?php back_button(); ?>
</body>
</html>