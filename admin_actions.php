<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SNEAKERSTORE";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_POST['action'] ?? '';

switch($action) {
    case 'add':
        addEntry($conn);
        break;
    case 'edit':
        editEntry($conn);
        break;
    case 'delete':
        deleteEntry($conn);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}

function addEntry($conn) {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $card_number = $conn->real_escape_string($_POST['card_number']);
    $expiry_month = $conn->real_escape_string($_POST['expiry_month']);
    $expiry_year = $conn->real_escape_string($_POST['expiry_year']);
    
    $sql = "INSERT INTO payments (name, phone, address, card_number, expiry_month, expiry_year) 
            VALUES ('$name', '$phone', '$address', '$card_number', '$expiry_month', '$expiry_year')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php?message=Entry added successfully");
    } else {
        header("Location: admin.php?error=Error adding entry: " . $conn->error);
    }
}

function editEntry($conn) {
    $id = $conn->real_escape_string($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $card_number = $conn->real_escape_string($_POST['card_number']);
    $expiry_month = $conn->real_escape_string($_POST['expiry_month']);
    $expiry_year = $conn->real_escape_string($_POST['expiry_year']);
    
    $sql = "UPDATE payments SET 
            name='$name', 
            phone='$phone', 
            address='$address', 
            card_number='$card_number', 
            expiry_month='$expiry_month', 
            expiry_year='$expiry_year' 
            WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php?message=Entry updated successfully");
    } else {
        header("Location: admin.php?error=Error updating entry: " . $conn->error);
    }
}

function deleteEntry($conn) {
    $id = $conn->real_escape_string($_POST['id']);
    
    $sql = "DELETE FROM payments WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php?message=Entry deleted successfully");
    } else {
        header("Location: admin.php?error=Error deleting entry: " . $conn->error);
    }
}

$conn->close();
?>
