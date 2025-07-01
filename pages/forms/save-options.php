<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = $_POST['tree'];
    $title = $_POST['title'] ?? '';
    $tree = json_decode($json, true);

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=chatbot", "root", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO option_trees (title, tree_data) VALUES (:title, :data)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':data', $json);

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
