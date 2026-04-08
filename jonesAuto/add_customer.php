<?php
include 'db_connect.php';
include 'functions.php';

# Initialize variables for form handling and messages
$success         = "";
$error           = "";
$new_customer_id = null;

# Define gender options for the dropdown
$gender_options = [
    ['value' => 'Male', 'label' => 'Male'],
    ['value' => 'Female', 'label' => 'Female'],
    ['value' => 'Other', 'label' => 'Other'],
    ['value' => 'Prefer not to say', 'label' => 'Prefer not to say']
];

# handle customer form submission using prepare and bind_param for security
if (isset($_POST['save_customer'])) {
    $first_name   = $_POST['first_name'];
    $last_name    = $_POST['last_name'];
    $phone        = $_POST['phone'];
    $email        = $_POST['email'];
    $address      = $_POST['address'];
    $city         = $_POST['city'];
    $province     = $_POST['province'];
    $gender       = $_POST['gender'];
    $dob          = $_POST['dob'];
    $tax_id       = $_POST['tax_id'];
    $num_late     = $_POST['num_late'];
    $avg_day_late = $_POST['avg_day_late'];

    $stmt = $conn->prepare("INSERT INTO Customer
        (First_Name, Last_Name, Phone_Number, Email, Customer_address, City, Province, Gender, DOB, Tax_ID, num_Late_Payment, Avg_Day_Late)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssid",
        $first_name, $last_name, $phone, $email,
        $address, $city, $province, $gender,
        $dob, $tax_id, $num_late, $avg_day_late
    );

    # Execute the statement and handle success or error
    if ($stmt->execute()) {
        $new_customer_id = $conn->insert_id;
        $success = "Customer #$new_customer_id saved! Add employment history, then continue to sale.";
    } else {
        $error = "Error saving customer: " . $stmt->error;
    }
}

# handle employment form submission using prepare and bind_param for security
if (isset($_POST['save_employment'])) {
    $new_customer_id = intval($_POST['customer_id']);
    $employer    = $_POST['employer'];
    $title       = $_POST['title'];
    $sup_phone   = $_POST['sup_phone'];
    $emp_address = $_POST['emp_address'];
    $start_date  = $_POST['start_date'];

    $stmt = $conn->prepare("INSERT INTO Customer_Employment
        (Customer_ID, Employer, Title, Supervisor_Phone, Address, Start_Date)
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $new_customer_id, $employer, $title, $sup_phone, $emp_address, $start_date);

    if ($stmt->execute()) {
        $success = "Employment record added!";
    } else {
        $error = "Error saving employment: " . $stmt->error;
    }
}

# Fetch employment records for the new customer to display on the right side
$employment_records = [];
if (isset($_POST['customer_id']) && intval($_POST['customer_id']) > 0) {
    $new_customer_id = intval($_POST['customer_id']);
    $stmt = $conn->prepare("SELECT * FROM Customer_Employment WHERE Customer_ID = ?");
    $stmt->bind_param("i", $new_customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $employment_records[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Customer</title></head>
<body>
<h2>Add New Customer</h2>
<?php show_messages($success, $error); ?>

<table border="0" cellpadding="20">
<tr valign="top">

<!-- LEFT: CUSTOMER FORM -->
<td valign="top" style="border-right: 1px solid #ccc;">
<?php if (!$new_customer_id): ?>
    <form method="POST" action="">
        <?php
            text_input('First Name',    'first_name');
            text_input('Last Name',     'last_name');
            text_input('Phone Number',  'phone');
            text_input('Email',         'email', '', true, 'email');
            text_input('Address',       'address');
            text_input('City',          'city');
            text_input('Province',      'province');
            echo "<label>Gender:</label><br>";
            dropdown('gender', $gender_options);
            date_input('Date of Birth', 'dob', '');
            text_input('Tax ID',        'tax_id');
            number_input('Number of Late Payments', 'num_late', '0', '1', '0', true);
            number_input('Average Days Late',       'avg_day_late', '0.00', '0.01', '0', true);
            submit_button('Save Customer', 'save_customer');
        ?>
    </form>
<?php else: ?>
    <!-- Show success message and link to continue to add sale -->
    <p><strong>Customer #<?= $new_customer_id ?> saved.</strong></p>
    <p>Add employment history on the right, then continue when done.</p><br>
    <a href="add_sale.php?customer_id=<?= $new_customer_id ?>">
        <button>Continue to Add Sale &rarr;</button>
    </a>
<?php endif; ?>
</td>

<!-- RIGHT: EMPLOYMENT HISTORY -->
<td valign="top">
<?php if ($new_customer_id): ?>
    <h3>Employment History for Customer #<?= $new_customer_id ?></h3>
    
    <!-- Show existing employment records in a table -->
    <?php if (count($employment_records) > 0): ?>
        <?php results_table($employment_records); ?>
        <br>
    <?php else: ?>
        <p>No employment records yet.</p>
    <?php endif; ?>

    <h4>Add Employment Record:</h4>
    <form method="POST" action="">
        <input type="hidden" name="customer_id" value="<?= $new_customer_id ?>">
        <?php
            text_input('Employer',          'employer');
            text_input('Job Title',         'title', '', false);
            text_input('Supervisor Phone',  'sup_phone', '', false);
            text_input('Work Address',      'emp_address', '', false);
            date_input('Start Date',        'start_date', '', false);
            submit_button('Add Employment Record', 'save_employment');
        ?>
    </form>
<?php else: ?>
    <p><i>Save the customer first to add employment history.</i></p>
<?php endif; ?>
</td>
</tr>
</table>

<?php back_button(); ?>
</body>
</html>