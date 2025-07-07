<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=chatbot", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_POST['id'];
    $title = $_POST['title'];
    $tree_data = $_POST['tree_data'];

    $stmt = $pdo->prepare("UPDATE option_trees SET title = ?, tree_data = ? WHERE id = ?");
    $stmt->execute([$title, $tree_data, $id]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
