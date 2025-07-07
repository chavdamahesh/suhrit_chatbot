<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=chatbot", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Make sure an ID is sent
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = intval($_POST['id']);

        $stmt = $pdo->prepare("DELETE FROM option_trees WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount()) {
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Item not found.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
