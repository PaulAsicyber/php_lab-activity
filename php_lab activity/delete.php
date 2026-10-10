<?php
include 'dbcon.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php?msg=deleted");
exit;
?>