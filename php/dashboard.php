<?php
require_once './config.php';
check_auth();

$user_id =$_SESSION['user_id'];
$status_filter =$_GET['filter'] ?? 'all';
$sort_order =$_GET['sort'] ?? 'newest';

$query = "SELECT * FROM todos WHERE user_id = :user_id";
if ($status_filter === 'working') {$query .= " AND status = 'working'";
} elseif ($status_filter === 'finished') {$query .= " AND status = 'finished'";
}

if ($sort_order === 'deadline') {$query .= " ORDER BY due_date ASC";
} else {
    $query .= " ORDER BY created_at DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' =>$user_id]);
$todos =$stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - TaskFlow</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="navbar">
        <div class="nav-left">
            <a href="dashboard.php" class="nav-brand">TaskFlow</a>
        </div>
        <div class="nav-right">
            <a href="create_todo.php" class="btn-create-icon" title="Create New Task">✚</a>
            <div class="user-menu">
                <span class="user-icon">👤<?= htmlspecialchars($_SESSION['username']) ?></span>
                <div class="dropdown-content">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <hr>

    <main class="container">
        <section class="dashboard-header">
            <h2>Your Tasks</h2>
            <form method="GET" action="dashboard.php" class="filter-form">
                <select name="filter" onchange="this.form.submit()" class="select-input">
                    <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All Status</option>
                    <option value="working" <?= $status_filter === 'working' ? 'selected' : '' ?>>Working On It</option>
                    <option value="finished" <?= $status_filter === 'finished' ? 'selected' : '' ?>>Finished</option>
                </select>

                <select name="sort" onchange="this.form.submit()" class="select-input">
                    <option value="newest" <?= $sort_order === 'newest' ? 'selected' : '' ?>>Newest</option>
                    <option value="deadline" <?= $sort_order === 'deadline' ? 'selected' : '' ?>>Near Deadline</option>
                </select>
            </form>
        </section>

        <section class="todo-grid">
    <?php if (empty($todos)): ?>
        <div class="empty-state">No tasks found. Click '+' to create a new task!</div>
    <?php else: ?>
        <?php foreach ($todos as $todo): ?>
            <!-- Main Card -->
            <article class="todo-card <?= $todo['status'] === 'finished' ? 'card-finished' : 'card-working' ?>">
                <div class="card-header">
                    <h3><?= htmlspecialchars($todo['title']) ?></h3>
                    <div class="action-menu">
                        <button class="three-dots">⋮</button>
                        <div class="action-dropdown">
                            <button type="button" onclick="openModal('<?= $todo['id'] ?>')">More</button>
                            <a href="update_todo.php?id=<?= $todo['id'] ?>">Update</a>
                            <a href="delete_todo.php?id=<?= $todo['id'] ?>" onclick="return confirm('Are you sure?');" class="text-danger">Delete</a>
                        </div>
                    </div>
                </div>
                <p class="description"><?= htmlspecialchars($todo['description']) ?></p>
                <div class="card-footer">
                    <span class="due-date">📅 <?= htmlspecialchars($todo['due_date']) ?></span>
                    <span class="badge badge-<?= $todo['status'] ?>"><?= ucfirst($todo['status']) ?></span>
                </div>
            </article>

            <div id="modal-<?= $todo['id'] ?>" class="modal-backdrop">
                <div class="cascade-card">
                    <div class="cascade-header">
                        <h2><?= htmlspecialchars($todo['title']) ?></h2>
                        <button type="button" class="close-btn" onclick="closeModal('<?= $todo['id'] ?>')">&times;</button>
                    </div>
                    
                    <div class="cascade-body">
                        <div class="info-row">
                            <span class="label">Status:</span>
                            <span class="badge badge-<?= $todo['status'] ?>"><?= ucfirst($todo['status']) ?></span>
                        </div>
                        
                        <div class="info-row">
                            <span class="label">Due Date:</span>
                            <span>📅 <?= htmlspecialchars($todo['due_date']) ?></span>
                        </div>

                        <div class="info-block">
                            <span class="label">Description:</span>
                            <p><?= nl2br(htmlspecialchars($todo['description'])) ?></p>
                        </div>

                        <!-- Menampilkan Custom Extra Fields Jika Ada -->
                        <?php if (!empty($todo['extra_label_1'])): ?>
                            <div class="info-row extra-field">
                                <span class="label"><?= htmlspecialchars($todo['extra_label_1']) ?>:</span>
                                <span><?= htmlspecialchars($todo['extra_value_1']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($todo['extra_label_2'])): ?>
                            <div class="info-row extra-field">
                                <span class="label"><?= htmlspecialchars($todo['extra_label_2']) ?>:</span>
                                <span><?= htmlspecialchars($todo['extra_value_2']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="cascade-footer">
                        <a href="update_todo.php?id=<?= $todo['id'] ?>" class="btn btn-primary">Edit Task</a>
                        <button type="button" class="btn btn-light" onclick="closeModal('<?= $todo['id'] ?>')">Close</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
    </main>
    <script src="../js/cascade.js"></script>
    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> TaskFlow dibuat oleh LS030.</p>
        </div>  
    <hr>
</body>
</html>