<?php
session_start();

if (!isset($_SESSION['iduser'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
include 'php/config.php';

// Initialize variables
$task_title = $task_description = $task_start_date = $task_end_date = $task_category = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $task_title = $_POST['task_title'];
    $task_description = $_POST['task_description'];
    $task_start_date = $_POST['task_start_date'];
    $task_end_date = $_POST['task_end_date'];
    $task_category = $_POST['task_category'];

    // Insert task into database
    try {
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO task (tasktitle, taskdescription, taskstartdate, tastenddate, taskcategory) VALUES (?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $task_title);
        $stmt->bindParam(2, $task_description);
        $stmt->bindParam(3, $task_start_date);
        $stmt->bindParam(4, $task_end_date);
        $stmt->bindParam(5, $task_category);

        // Execute the statement
        if ($stmt->execute()) {
            // Task inserted successfully
            $success_message = "Task added successfully.";
        } else {
            // Error inserting task
            $error_message = "Error: Unable to add task.";
        }
    } catch(PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="src/output.css">
</head>
<body>
    <h2>Welcome to your dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <h3>Add Task</h3>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="task_title">Task Title:</label><br>
        <input type="text" id="task_title" name="task_title" required><br>
        <label for="task_description">Task Description:</label><br>
        <textarea id="task_description" name="task_description" required></textarea><br>
        <label for="task_start_date">Start Date:</label><br>
        <input type="date" id="task_start_date" name="task_start_date" required><br>
        <label for="task_end_date">End Date:</label><br>
        <input type="date" id="task_end_date" name="task_end_date" required><br>
        <label for="task_category">Category:</label><br>
        <select id="task_category" name="task_category" required>
            <!-- Populate with categories from database -->
            <?php
            // Include database connection
            include 'config.php';
            
            // Fetch categories from database
            $stmt = $conn->prepare("SELECT categoryid, categorytitle FROM category");
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Loop through categories and create options
            foreach ($categories as $category) {
                echo "<option value='" . $category['categoryid'] . "'>" . $category['categorytitle'] . "</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Add Task">
    </form>

    <a href="logout.php">Logout</a>
</body>
</html>
