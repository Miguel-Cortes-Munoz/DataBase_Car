<?php
include 'db_connect.php';
include 'functions.php';

# Define all queries with labels for display and the SQL to execute when selected
$queries = [

    'affordable_cars' => [
        'label'    => '1. Cars Under $20,000',
        'sql'      => "SELECT Car_ID, Year, Make, Model, Color, Miles, Condition_Desc, Book_Price
                       FROM Car WHERE Book_Price < 20000 ORDER BY Book_Price ASC"
    ],
    'employee_list' => [
        'label'    => '2. Employee Directory',

        'sql'      => "SELECT Employee_ID, First_Name, Last_Name, Position, Phone_Number, Email
                       FROM Employee ORDER BY Position, Last_Name"
    ],
    'cars_by_condition' => [
        'label'    => '3.  Cars by sold status then by Year and Condition',
        'sql'      => "SELECT Car_ID,   
                       CASE
                       WHEN Car_ID IN (SELECT Car_ID FROM Sale) THEN 'Sold'
                       ELSE 'Available'
                       END AS Sold_Status, Year, Make, Model, Color, Miles, Condition_Desc, Book_Price
                       FROM Car 
                       ORDER BY Sold_Status asc, Year DESC, Miles ASC"
    ],
    'revenue_per_employee' => [
        'label'    => '4. Revenue per Employee',
        'sql'      => "SELECT  e.Employee_ID, e.First_Name, e.Last_Name, e.Position,
                        COALESCE(s.Total_Sales, 0) AS Total_Sales, ROUND(COALESCE(s.Total_Revenue, 0), 2) AS Total_Revenue, 
                        ROUND(COALESCE(w.Warranty_Commission, 0), 2) AS Warranty_Commission, Round(COALESCE(s.Total_Commission, 0), 2) AS Sales_Commission,
                        ROUND(COALESCE(s.Total_Commission, 0) + COALESCE(w.Warranty_Commission, 0), 2) AS Total_Commission
                        FROM Employee e 
                    LEFT JOIN ( SELECT Employee_ID, COUNT(Sale_ID) AS Total_Sales, 
                        SUM(Financed_Amount + downpayment) AS Total_Revenue, SUM(commission) AS Total_Commission
                        FROM Sale
                        GROUP BY Employee_ID
                    ) s ON e.Employee_ID = s.Employee_ID

                    LEFT JOIN (
                        SELECT 
                            s.Employee_ID, SUM(w.Warranty_Cost * 0.25) AS Warranty_Commission
                        FROM Warranties w
                        JOIN Sale s ON w.Sale_ID = s.Sale_ID
                        GROUP BY s.Employee_ID
                    ) w ON e.Employee_ID = w.Employee_ID
                        ORDER BY Total_Revenue DESC;"
    ],
    'full_sale_details' => [
        'label'    => '5. Full Sale Details',
        'sql'      => "SELECT s.Sale_ID, s.Sale_Date,
                            CONCAT(c.First_Name, ' ', c.Last_Name)       AS Customer,
                            c.Phone_Number                                AS Customer_Phone,
                            CONCAT(e.First_Name, ' ', e.Last_Name)       AS Salesperson,
                            CONCAT(ca.Year, ' ', ca.Make, ' ', ca.Model) AS Car,
                            ca.Color, s.Sale_Price, s.Financed_Amount, s.downpayment, s.commission
                       FROM Sale s
                       JOIN Customer c  ON s.Customer_ID = c.Customer_ID
                       JOIN Employee e  ON s.Employee_ID = e.Employee_ID
                       JOIN Car      ca ON s.Car_ID      = ca.Car_ID
                       ORDER BY s.Sale_Date DESC"
    ],
    'late_payment_financed' => [
        'label'    => '6. Late Payment Risk',
        'sql'      => "SELECT c.Customer_ID, c.First_Name, c.Last_Name,
                            c.num_Late_Payment, c.Avg_Day_Late,
                            COUNT(s.Sale_ID)       AS Financed_Cars,
                            SUM(s.Financed_Amount) AS Total_Financed
                       FROM Customer c JOIN Sale s ON c.Customer_ID = s.Customer_ID
                       WHERE s.Financed_Amount > 0 AND c.num_Late_Payment > 0
                       GROUP BY c.Customer_ID, c.First_Name, c.Last_Name,
                                c.num_Late_Payment, c.Avg_Day_Late
                       ORDER BY c.Avg_Day_Late DESC"
    ],
    'damage_estimate_accuracy' => [
        'label'    => '7. Damage Estimate Accuracy by Employee',

        'sql'      => "SELECT e.Employee_ID, e.First_Name, e.Last_Name,
                            COUNT(cd.Damage_ID)                                    AS Total_Estimates,
                            ROUND(AVG(ABS(cd.Estimated_Repair_Cost
                                       - cd.Actual_Repair_Cost)), 2)               AS Avg_Error,
                            ROUND(SUM(cd.Estimated_Repair_Cost), 2)               AS Total_Estimated,
                            ROUND(SUM(cd.Actual_Repair_Cost), 2)                  AS Total_Actual
                       FROM car_damage cd
                       JOIN Car c       ON cd.Car_ID      = c.Car_ID
                       JOIN Purchase p  ON c.Car_ID       = p.Car_ID
                       JOIN Employee e  ON p.Employee_ID  = e.Employee_ID
                       WHERE cd.Actual_Repair_Cost IS NOT NULL
                       GROUP BY e.Employee_ID, e.First_Name, e.Last_Name
                       ORDER BY Avg_Error ASC"
    ],
    'all_damage_records' => [
        'label'    => '8. All Damage Records',
       
        'sql'      => "SELECT cd.Damage_ID, c.Car_ID,
                            CONCAT(c.Year, ' ', c.Make, ' ', c.Model) AS Car,
                            cd.Damage_Desc,
                            cd.Estimated_Repair_Cost,
                            cd.Actual_Repair_Cost,
                            CASE WHEN cd.Actual_Repair_Cost IS NULL
                                 THEN 'Pending' ELSE 'Complete' END    AS Status
                       FROM car_damage cd
                       JOIN Car c ON cd.Car_ID = c.Car_ID
                       ORDER BY Status desc ,cd.Damage_ID DESC"
    ],
    'warranties_by_sale' => [
        'label'    => '9. Warranties by Sale',
        'sql'      => "SELECT w.Warranty_ID, s.Sale_ID, s.Sale_Date,
                            CONCAT(c.First_Name, ' ', c.Last_Name)       AS Customer,
                            CONCAT(ca.Year, ' ', ca.Make, ' ', ca.Model) AS Car,
                            w.Warranty_Desc, w.Warranty_Cost,
                            w.Start_Date, w.Length_Months, w.deductible,
                            w.itemized_coverage
                       FROM warranties w
                       JOIN Sale s     ON w.Sale_ID     = s.Sale_ID
                       JOIN Customer c ON s.Customer_ID = c.Customer_ID
                       JOIN Car ca     ON s.Car_ID      = ca.Car_ID
                       ORDER BY w.Warranty_ID DESC"
    ],
    'payment_summary_by_sale' => [
        'label'    => '10. Payment Summary per Sale',
        
        'sql'      => "SELECT s.Sale_ID, s.Sale_Date,
                            CONCAT(c.First_Name, ' ', c.Last_Name)       AS Customer,
                            CONCAT(ca.Year, ' ', ca.Make, ' ', ca.Model) AS Car,
                            s.Financed_Amount,
                            concat(Financed_Amount * 0.01) AS Interest_Per_Month,
                            COALESCE(SUM(p.Payment_Amount), 0)           AS Total_Paid,
                            s.Financed_Amount
                                - COALESCE(SUM(p.Payment_Amount), 0)     AS Remaining_Balance
                            
                       FROM Sale s
                       JOIN Customer c  ON s.Customer_ID = c.Customer_ID
                       JOIN Car ca      ON s.Car_ID      = ca.Car_ID
                       LEFT JOIN payments p ON s.Sale_ID = p.Sale_ID
                       WHERE s.Financed_Amount > 0
                       GROUP BY s.Sale_ID, s.Sale_Date, c.First_Name, c.Last_Name,
                                ca.Year, ca.Make, ca.Model, s.Financed_Amount
                       ORDER BY Remaining_Balance DESC"
    ],
];

# Initialize variables for results and error handling
$result_data  = [];
$active_query = null;
$error        = "";

# Check if a query action was submitted and execute the corresponding SQL
if (isset($_POST['action']) && array_key_exists($_POST['action'], $queries)) {
    $active_query = $_POST['action'];
    $result       = mysqli_query($conn, $queries[$active_query]['sql']);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $result_data[] = $row;
        }
    } else {
        $error = "Query error: " . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html>
<head><title>Jones Auto - Reports</title></head>
<body>

<!-- Display buttons for each query -->
<form method="POST">
    <?php foreach ($queries as $key => $q): ?>
        <input type="submit" name="action" value="<?= $key ?>"
            <?= $active_query === $key ? 'style="font-weight:bold;"' : '' ?>>
        <?= htmlspecialchars($q['label']) ?><br>
    <?php endforeach; ?>
</form>

<hr>
<!-- Display results or error messages -->
<?php if ($error): ?>
    <?php show_messages('', $error); ?>
<?php elseif ($active_query): ?>
    <?php results_table($result_data); ?>
<?php else: ?>
    <p>Select a report above to view results.</p>
<?php endif; ?>

<?php back_button(); ?>
</body>
</html>