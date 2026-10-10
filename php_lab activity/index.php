<?php 
include 'dbcon.php'; 


$message = "";
$msg_type = "";

if (isset($_POST['add_user'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (!empty($name) && !empty($email)) {
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);
        
        if ($stmt->execute()) {
            header("Location: index.php?msg=added");
            exit();
        } else {
            $message = "Error: " . $stmt->error;
            $msg_type = "danger";
        }
        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
        $msg_type = "warning";
    }
}


if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'added') {
        $message = "User successfully added!";
        $msg_type = "success";
    } elseif ($_GET['msg'] == 'updated') {
        $message = "User successfully updated!";
        $msg_type = "success";
    } elseif ($_GET['msg'] == 'deleted') {
        $message = "User successfully deleted!";
        $msg_type = "success";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container my-5">
        <h2 class="mb-4 text-center">User Management System</h2>

        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Form Column (Create) -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Add New User</h5>
                    </div>
                    <div class="card-body">
                        <form action="index.php" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label text-muted">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder= "Enter Full Name"
                                required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-muted">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder= "Enter Email Address"
                                required>
                            </div>
                            <button type="submit" name="add_user" class="btn btn-primary w-100">Add User</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table Column (Read) -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light text-muted fw-semibold">
                        Registered users
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Joined</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                            echo "<td>" . $row['created_at'] . "</td>";
                                            echo "<td>
                                                    <a href='edit.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning me-1'>Edit</a>
                                                    <a href='delete.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center text-muted py-3'>No users found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>