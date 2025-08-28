<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "u253184498_savat", "Stavat@123", "u253184498_artventure");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Login check
if (!isset($_SESSION['admin_logged_in']) && $_SERVER['REQUEST_METHOD'] != 'POST') {
    showLoginForm();
    exit();
}

// Login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if ($email === 'Startup@5' && $password === '$@N!@y') {
        $_SESSION['admin_logged_in'] = true;
    } else {
        showLoginForm("Invalid credentials");
        exit();
    }
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

// Delete functionality
if (isset($_GET['delete_quotation'])) {
    $id = (int)$_GET['delete_quotation'];
    $conn->query("DELETE FROM quotations WHERE id = $id");
    header("Location: admin.php");
    exit();
}

if (isset($_GET['delete_contact'])) {
    $id = (int)$_GET['delete_contact'];
    $conn->query("DELETE FROM contacts WHERE id = $id");
    header("Location: admin.php");
    exit();
}

// Function to show login form
function showLoginForm($error = "") {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Admin Login</div>
                        <div class="card-body">
                            '.($error ? '<div class="alert alert-danger">'.$error.'</div>' : '').'
                            <form method="post">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';
}

// Main admin panel
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col">
                <h2>Admin Panel</h2>
                <a href="?logout" class="btn btn-danger float-right">Logout</a>
            </div>
        </div>

        <h3>Quotations</h3>
        <table id="quotationsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Property Type</th>
                    <th>Wall Count</th>
                    <th>Total Area</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM quotations");
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['property_type']}</td>
                        <td>{$row['wall_count']}</td>
                        <td>{$row['total_area']}</td>
                        <td>{$row['created_at']}</td>
                        <td><a href='?delete_quotation={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <h3 class="mt-5">Contacts</h3>
        <table id="contactsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM contacts");
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['message']}</td>
                        <td>{$row['created_at']}</td>
                        <td><a href='?delete_contact={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#quotationsTable').DataTable();
            $('#contactsTable').DataTable();
        });
    </script>
</body>
</html>