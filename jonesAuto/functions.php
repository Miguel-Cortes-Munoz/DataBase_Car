<?php
# Common functions for the Jones Auto web app

# Display success and error messages in a consistent format
function show_messages($success, $error) {
    if ($error)   echo "<p style='color:red;'>"   . htmlspecialchars($error)   . "</p>";
    if ($success) echo "<p style='color:green;'>" . htmlspecialchars($success) . "</p>";
}

#  Simple back button to return to a previous page, defaulting to index.php
function back_button($href = "index.php") {
    echo "<br><a href='$href'><button>Back</button></a>";
}

# Generate a dropdown select element from an array of options
# $options = [['value' => 1, 'label' => 'John Smith'], ...]
function dropdown($name, $options, $selected = 0, $required = true) {
    $req = $required ? 'required' : '';
    echo "<select name='$name' $req>";
    echo "<option value=''>-- Select --</option>";
    foreach ($options as $opt) {
        $sel = ($opt['value'] == $selected) ? 'selected' : '';
        echo "<option value='{$opt['value']}' $sel>" . htmlspecialchars($opt['label']) . "</option>";
    }
    echo "</select><br><br>";
}

# Text input with optional type (default is 'text') 
function text_input($label, $name, $value = '', $required = true, $type = 'text') {
    $req = $required ? 'required' : '';
    $val = htmlspecialchars($value);
    echo "<label>$label:</label><br>
          <input type='$type' name='$name' value='$val' $req><br><br>";
}

#  Number input with step and min attributes 
function number_input($label, $name, $value = '', $step = '1', $min = '0', $required = false) {
    $req = $required ? 'required' : '';
    $val = htmlspecialchars($value);
    echo "<label>$label:</label><br>
          <input type='number' name='$name' value='$val' step='$step' min='$min' placeholder='0.00' $req><br><br>";
}

#  Date input with default to today's date
function date_input($label, $name, $value = '', $required = true) {
    $req      = $required ? 'required' : '';
    $val      = $value ?: date('Y-m-d');
    echo "<label>$label:</label><br>
          <input type='date' name='$name' value='$val' $req><br><br>";
}

#  Textarea input for longer text fields
function textarea_input($label, $name, $value = '', $required = true) {
    $req = $required ? 'required' : '';
    $val = htmlspecialchars($value);
    echo "<label>$label:</label><br>
          <textarea name='$name' rows='4' cols='50' $req>$val</textarea><br><br>";
}

#  Submit button with optional name attribute for form handling
function submit_button($label, $name = '') {
    $n = $name ? "name='$name'" : '';
    echo "<input type='submit' $n value='$label'>";
}

#  Results table (used in view_data.php) 
function results_table($rows) {
    if (count($rows) === 0) {
        echo "<p>No results found.</p>";
        return;
    }
    echo "<p>" . count($rows) . " row(s) returned.</p>";
    echo "<table border='1' cellpadding='5'><tr>";
    foreach (array_keys($rows[0]) as $col) {
        echo "<th>" . htmlspecialchars($col) . "</th>";
    }
    echo "</tr>";
    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $val) {
            echo "<td>" . htmlspecialchars($val ?? '-') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

#   Fetch options for dropdowns from the database
function fetch_options($conn, $sql, $value_col, $label_col) {
    $options = [];
    $result  = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $options[] = ['value' => $row[$value_col], 'label' => $row[$label_col]];
    }
    return $options;
}