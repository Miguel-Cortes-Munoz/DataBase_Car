<?php
include 'db_connect.php';
include 'functions.php';

$error   = "";
$success = isset($_GET['success']) ? "Operation completed successfully!" : "";

# Get car_id from URL if available to pre-select in the form
$car_id_from_url = intval($_GET['car_id'] ?? 0);

# If car_id is provided, fetch the car details for display
$car_from_url = null;
if ($car_id_from_url > 0) {
    $stmt = $conn->prepare("SELECT Make, Model, Year FROM car WHERE car_ID = ?");
    $stmt->bind_param("i", $car_id_from_url);
    $stmt->execute();
    $car_from_url = $stmt->get_result()->fetch_assoc();
}

# Fetch car options for the dropdown, excluding cars already sold
$car_options = fetch_options($conn,
    "SELECT Car_ID, CONCAT('#', Car_ID, ' — ', Year, ' ', Make, ' ', Model) AS label
     FROM Car WHERE Car_ID NOT IN (SELECT Car_ID FROM Sale) ORDER BY Car_ID ASC",
    'Car_ID', 'label'
);
# Fetch pending damage records for the right side form
$pending_damages = [];
$result = $conn->query("SELECT cd.Damage_ID, cd.Damage_Desc, cd.Estimated_Repair_Cost,
                                c.Car_ID, c.Year, c.Make, c.Model
                         FROM car_damage cd
                         JOIN Car c ON cd.Car_ID = c.Car_ID
                         WHERE cd.Actual_Repair_Cost IS NULL
                         ORDER BY cd.Damage_ID DESC");
while ($row = $result->fetch_assoc()) {
    $pending_damages[] = $row;
}

# handle new damage form submission using prepare and bind_param for security
if (isset($_POST['add_damage'])) {
    $car_id                = intval($_POST['car_id']);
    $damage_desc           = $_POST['damage_desc'];
    $estimated_repair_cost = $_POST['estimated_repair_cost'] !== '' ? $_POST['estimated_repair_cost'] : null;
    $actual_repair_cost    = $_POST['actual_repair_cost']    !== '' ? $_POST['actual_repair_cost']    : null;

    if ($car_id === 0) {
        $error = "Please select a car.";
    } else {
        $stmt = $conn->prepare("INSERT INTO car_damage (Car_ID, Damage_Desc, Estimated_Repair_Cost, Actual_Repair_Cost)
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isdd", $car_id, $damage_desc, $estimated_repair_cost, $actual_repair_cost);
        # Execute the statement and handle success or error
        if ($stmt->execute()) {   
            $car_param = $car_id_from_url > 0 ? "?car_id=$car_id_from_url&" : "?";
            header("Location: " . $_SERVER['PHP_SELF'] . $car_param . "success=1");
            exit;

        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}

# handle update actual repair cost form submission using prepare and bind_param for security
if (isset($_POST['update_cost'])) {
    $damage_id          = intval($_POST['damage_id']);
    $actual_repair_cost = $_POST['actual_repair_cost_update'];

    if ($damage_id === 0) {
        $error = "Please select a damage record.";
    } elseif ($actual_repair_cost === '') {
        $error = "Please enter the actual repair cost.";
    } else {
        $stmt = $conn->prepare("UPDATE car_damage SET Actual_Repair_Cost = ? WHERE Damage_ID = ?");
        $stmt->bind_param("di", $actual_repair_cost, $damage_id);
        if ($stmt->execute()) {

            // re-fetch pending damages so the table reflects the new record
            $pending_damages = [];
            $result = $conn->query("SELECT cd.Damage_ID, cd.Damage_Desc, cd.Estimated_Repair_Cost,
                                            c.Car_ID, c.Year, c.Make, c.Model
                                    FROM car_damage cd JOIN Car c ON cd.Car_ID = c.Car_ID
                                    WHERE cd.Actual_Repair_Cost IS NULL ORDER BY cd.Damage_ID DESC");
            while ($row = $result->fetch_assoc()) {
                $pending_damages[] = $row;
            }

        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Car Damage</title></head>
<body>
<h2>Car Damage</h2>
<?php show_messages($success, $error); ?>

<table border="0" cellpadding="20">
<tr valign="top">

<!-- LEFT: ADD NEW DAMAGE -->
<td valign="top" style="border-right: 1px solid #c1c1c1;">
    <h3>Record New Damage</h3>
    <form method="POST" action="">
        <?php if ($car_from_url): ?>
            <p><strong>Car from previous step:</strong>

            <!-- Show the car details from URL and include hidden input to use this car_id for the damage record -->
            <?= htmlspecialchars($car_from_url['Year'] . ' ' . $car_from_url['Make'] . ' ' . $car_from_url['Model']) ?>
            (ID: <?= $car_id_from_url ?>)</p>
            <input type="hidden" name="car_id_hidden" value="<?= $car_id_from_url ?>">
        <?php else: ?>
            <input type="hidden" name="car_id_hidden" value="0">
        <?php endif; ?>

        <?php
            echo "<label>Car:</label><br>";
            dropdown('car_id', $car_options, $car_id_from_url);
            textarea_input('Damage Description', 'damage_desc');
            number_input('Estimated Repair Cost',            'estimated_repair_cost', '', '0.01', '0');
            number_input('Actual Repair Cost (fill in later)', 'actual_repair_cost',  '', '0.01', '0');
            submit_button('Record Damage', 'add_damage');
        ?>
    </form>
</td>

<!-- RIGHT: UPDATE ACTUAL COST -->
<td valign="top">
    <h3>Update Actual Repair Cost</h3>
    <!-- Show dropdown of pending damage records that need actual cost updates -->
    <?php if (count($pending_damages) === 0): ?>
        <p>No damage records missing an actual cost.</p>
    <?php else: ?>
        <?php
        # Prepare options for the dropdown to select which damage record to update
            $damage_options = array_map(fn($d) => [
                'value' => $d['Damage_ID'],
                'label' => "#$d[Damage_ID] — $d[Year] $d[Make] $d[Model] | Est: $" .
                           number_format($d['Estimated_Repair_Cost'], 2) . " | " .
                           substr($d['Damage_Desc'], 0, 30) . "..."
            ], $pending_damages);
        ?>
        <form method="POST" action="">
            <?php
                echo "<label>Select Damage Record:</label><br>";
                dropdown('damage_id', $damage_options);
                number_input('Actual Repair Cost', 'actual_repair_cost_update', '', '0.01', '0', true);
                submit_button('Update Cost', 'update_cost');
            ?>
        </form>
        <br>
        <h4>Pending records:</h4>
        <?php results_table($pending_damages); ?>
    <?php endif; ?>
</td>
</tr>
</table>

<?php back_button(); ?>
</body>
</html>