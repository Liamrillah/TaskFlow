<?php
require_once 'config.php';
check_auth();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $due_date = $_POST['due_date'] ?? '';
    
    $extra_label_1 = trim($_POST['extra_label_1'] ?? '');
    $extra_value_1 = trim($_POST['extra_value_1'] ?? '');
    $extra_label_2 = trim($_POST['extra_label_2'] ?? '');
    $extra_value_2 = trim($_POST['extra_value_2'] ?? '');

    if (empty($title) || empty($description) || empty($due_date)) {
        $error = "Title, Description, and Due Date are mandatory fields.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO todos (user_id, title, description, due_date, extra_label_1, extra_value_1, extra_label_2, extra_value_2) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$_SESSION['user_id'], $title, $description, $due_date, $extra_label_1, $extra_value_1, $extra_label_2, $extra_value_2])) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Failed to create task.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Task - TaskFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="navbar">
        <a href="dashboard.php" class="nav-brand">TaskFlow</a>
    </header>

    <main class="container">
        <div class="form-card">
            <h2>Create New Task</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div id="error-message" class="alert alert-danger" style="display:none;"></div>

            <form action="create_todo.php" method="POST" onsubmit="return validateTodoForm(event)">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" class="form-control">
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="4" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label for="due_date">Due Date *</label>
                    <input type="date" id="due_date" name="due_date" class="form-control">
                </div>

                <div id="extra-fields-container"></div>

                <button type="button" id="add-field-btn" class="btn btn-secondary" onclick="addExtraField()">+ Add Custom Extra Field</button>
                
                <div class="form-actions" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">Done / Save Task</button>
                    <a href="dashboard.php" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <script src="../js/validation.js"></script>
    <script src = "../js/createtodo.js">
    </script>
</body>
</html>