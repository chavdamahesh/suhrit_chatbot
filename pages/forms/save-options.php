<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = $_POST['tree'];
    $title = $_POST['title'] ?? '';
    $tree = json_decode($json, true);
    $id = $_POST['id'] ?? null;

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=chatbot", "root", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($id) {
            // Update existing
            $stmt = $pdo->prepare("UPDATE option_trees SET title = :title, tree_data = :data WHERE id = :id");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':data', $json);
            $stmt->bindParam(':id', $id);
        } else {
            // Insert new
            $stmt = $pdo->prepare("INSERT INTO option_trees (title, tree_data) VALUES (:title, :data)");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':data', $json);
        }

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
