<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SNEAKERSTORE</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-header">
        <h2>Payment Management Dashboard</h2>
        <div class="admin-actions">
            <button class="add-button" onclick="showAddForm()">
                <i class="fas fa-plus"></i> Add New Entry
            </button>
            <a href="index.html" class="home-button">
                <i class="fas fa-home"></i> Back to Store
            </a>
        </div>
    </div>

    <!-- Add New Entry Form -->
    <div id="addEntryForm" class="modal">
        <div class="modal-content">
            <h3>Add New Payment Entry</h3>
            <form action="admin_actions.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Customer Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" required>
                </div>
                <div class="form-group">
                    <label>Card Number</label>
                    <input type="text" name="card_number" maxlength="16" required>
                </div>
                <div class="form-row">
                    <div class="form-group half">
                        <label>Expiry Month</label>
                        <input type="text" name="expiry_month" maxlength="2" required>
                    </div>
                    <div class="form-group half">
                        <label>Expiry Year</label>
                        <input type="text" name="expiry_year" maxlength="4" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="submit-button">Add Entry</button>
                    <button type="button" class="cancel-button" onclick="hideAddForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Phone Number</th>
                <th>Shipping Address</th>
                <th>Card Number</th>
                <th>Expiry</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SNEAKERSTORE";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, phone, address, card_number, expiry_month, expiry_year, cvv FROM payments";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
$serialNumber = 1;

   if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Mask sensitive data
        $maskedCard = "****" . substr($row["card_number"], -4);
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($serialNumber) . "</td>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
        echo "<td>" . $maskedCard . "</td>";
        echo "<td>" . htmlspecialchars($row["expiry_month"]) . "/" . htmlspecialchars($row["expiry_year"]) . "</td>";
        echo "<td><span class='status-badge'>Completed</span></td>";
        echo "<td class='actions'>";
        echo "<button onclick='editEntry({
            id: " . json_encode($serialNumber) . ",
            name: " . json_encode($row["name"]) . ",
            phone: " . json_encode($row["phone"]) . ",
            address: " . json_encode($row["address"]) . ",
            card_number: " . json_encode($row["card_number"]) . ",
            expiry_month: " . json_encode($row["expiry_month"]) . ",
            expiry_year: " . json_encode($row["expiry_year"]) . "
        })' class='edit-button'><i class='fas fa-edit'></i></button>";
        echo "<button onclick='deleteEntry(" . $serialNumber . ")' class='delete-button'><i class='fas fa-trash-alt'></i></button>";
        echo "</td>";
        echo "</tr>";
        $serialNumber++;
    }
} else {
    echo "<tr><td colspan='8' class='empty-message'>No payment records found</td></tr>";
}
$conn->close();
?>
        </table>
    </div>
    <script>
        function showAddForm() {
            document.getElementById('addEntryForm').style.display = 'block';
        }

        function hideAddForm() {
            document.getElementById('addEntryForm').style.display = 'none';
        }

        function editEntry(entry) {
            // Create edit form modal
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.id = 'editEntryForm';
            
            modal.innerHTML = `
                <div class="modal-content">
                    <h3>Edit Payment Entry</h3>
                    <form action="admin_actions.php" method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="${entry.id}">
                        <div class="form-group">
                            <label>Customer Name</label>
                            <input type="text" name="name" value="${entry.name}" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" value="${entry.phone}" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" value="${entry.address}" required>
                        </div>
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" name="card_number" value="${entry.card_number}" maxlength="16" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group half">
                                <label>Expiry Month</label>
                                <input type="text" name="expiry_month" value="${entry.expiry_month}" maxlength="2" required>
                            </div>
                            <div class="form-group half">
                                <label>Expiry Year</label>
                                <input type="text" name="expiry_year" value="${entry.expiry_year}" maxlength="4" required>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="submit-button">Save Changes</button>
                            <button type="button" class="cancel-button" onclick="closeEditForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            `;
            
            document.body.appendChild(modal);
            modal.style.display = 'block';
        }

        function closeEditForm() {
            const modal = document.getElementById('editEntryForm');
            modal.remove();
        }

        function deleteEntry(id) {
            if (confirm('Are you sure you want to delete this entry?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'admin_actions.php';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete';
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                
                form.appendChild(actionInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addEntryForm');
            const editModal = document.getElementById('editEntryForm');
            
            if (event.target === addModal) {
                hideAddForm();
            }
            if (event.target === editModal) {
                closeEditForm();
            }
        }

        // Show success or error messages
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            const error = urlParams.get('error');
            
            if (message) {
                alert(message);
            }
            if (error) {
                alert(error);
            }
        }
    </script>
</body>
</html>