<?php
require_once(__DIR__ . '/paper_management.php');
session_start();
if(!isset($_SESSION["loggedin"])){
    header("location: ./login.php");
    exit;
}

if (isset($_POST['doc_ids'])) {

    foreach ($_POST['doc_ids'] as $pid) {
        $check_sql = "SELECT userid FROM documents WHERE pid = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $pid);
        $check_stmt->execute();
        $check_stmt->bind_result($existing_userids);
        $check_stmt->fetch();
        $check_stmt->close();

        if (!in_array($_SESSION['userid'], explode(',', $existing_userids))) {
            $new_userids = $existing_userids . (empty($existing_userids) ? '' : ',') . $_SESSION['userid'];
            $update_sql = "UPDATE documents SET editor = ? WHERE pid = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $new_userids, $pid);
            $update_stmt->execute();
            //$update_stmt->close();
        }
    }

    echo "Add success";
    $update_stmt->close();
    $conn->close();
}
?>
