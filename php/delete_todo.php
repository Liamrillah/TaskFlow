<?php
require_once 'config.php';
check_auth();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
}

header("Location: dashboard.php");
exit();
?>