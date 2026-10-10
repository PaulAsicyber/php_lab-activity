<?php
include 'dbcon.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($name != "" && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare(
            "UPDATE users SET name = ?, email = ? WHERE id = ?"
        );
        $stmt->bind_param("ssi", $name, $email, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php?msg=updated");
        exit;
    } else {
        $error = "Please enter a valid name and email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card">
        <div class="card-header">Edit User</div>
        <div class="card-body">

            <?php if ($error != ""): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control"
                           value="<?= htmlspecialchars(
                               $_POST['name'] ?? $user['name']
                           ) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars(
                               $_POST['email'] ?? $user['email']
                           ) ?>" required>
                </div>

                <button type="submit" name="update"
                        class="btn btn-success">Update User</button>

                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>