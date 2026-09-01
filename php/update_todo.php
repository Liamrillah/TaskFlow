<?php
require_once 'config.php';
check_auth();

$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM todos WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$todo = $stmt->fetch();

if (!$todo) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $due_date = $_POST['due_date'] ?? '';
    $status = $_POST['status'] ?? 'working';
    
    $extra_label_1 = trim($_POST['extra_label_1'] ?? '');
    $extra_value_1 = trim($_POST['extra_value_1'] ?? '');
    $extra_label_2 = trim($_POST['extra_label_2'] ?? '');
    $extra_value_2 = trim($_POST['extra_value_2'] ?? '');

    if (empty($title) || empty($description) || empty($due_date)) {
        $error = "Title, Description, and Due Date cannot be empty.";
    } else {
        $updateStmt = $pdo->prepare("UPDATE todos SET title = ?, description = ?, due_date = ?, status = ?, extra_label_1 = ?, extra_value_1 = ?, extra_label_2 = ?, extra_value_2 = ? WHERE id = ? AND user_id = ?");
        if ($updateStmt->execute([$title, $description, $due_date, $status, $extra_label_1, $extra_value_1, $extra_label_2, $extra_value_2, $id, $user_id])) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Failed to update task.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task - TaskFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="navbar">
        <a href="dashboard.php" class="nav-brand">TaskFlow</a>
    </header>

    <main class="container">
        <div class="form-card">
            <h2>Edit Task</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div id="error-message" class="alert alert-danger" style="display:none;"></div>

            <form action="update_todo.php?id=<?= $id ?>" method="POST" onsubmit="return validateTodoForm(event)">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($todo['title']) ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="4" class="form-control"><?= htmlspecialchars($todo['description']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="due_date">Due Date *</label>
                    <input type="date" id="due_date" name="due_date" value="<?= htmlspecialchars($todo['due_date']) ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="working" <?= $todo['status'] === 'working' ? 'selected' : '' ?>>Working On It</option>
                        <option value="finished" <?= $todo['status'] === 'finished' ? 'selected' : '' ?>>Finished</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Extra Field 1</label>
                    <div class="extra-row">
                        <input type="text" name="extra_label_1" value="<?= htmlspecialchars($todo['extra_label_1'] ?? '') ?>" placeholder="Label" class="form-control">
                        <input type="text" name="extra_value_1" value="<?= htmlspecialchars($todo['extra_value_1'] ?? '') ?>" placeholder="Value" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>Extra Field 2</label>
                    <div class="extra-row">
                        <input type="text" name="extra_label_2" value="<?= htmlspecialchars($todo['extra_label_2'] ?? '') ?>" placeholder="Label" class="form-control">
                        <input type="text" name="extra_value_2" value="<?= htmlspecialchars($todo['extra_value_2'] ?? '') ?>" placeholder="Value" class="form-control">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Task</button>
                    <a href="dashboard.php" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </main>
    <script src="../js/validation.js"></script>
</body>
</html>