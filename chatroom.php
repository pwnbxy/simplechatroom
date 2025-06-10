<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "chatroom");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM messages ORDER BY created_at ASC");
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
}

// Post a message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $message = $_POST['message'];

    if ($username && $message) {
        $stmt = $conn->prepare("INSERT INTO messages (username, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $message);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
    }
}

$conn->close();
?>
