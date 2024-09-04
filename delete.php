<?php
require_once(__DIR__ . '/paper_management.php');

if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $sql = "DELETE FROM documents WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pid);
    if ($stmt->execute()) {
        echo "Delete success";
    } else {
        echo "Delete failed";
    }
    $stmt->close();
    $conn->close();
}
?>
