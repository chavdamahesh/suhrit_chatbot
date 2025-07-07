<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=chatbot", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['id'], $input['title'], $input['tree_data'])) {
        $stmt = $pdo->prepare("UPDATE option_trees SET title = ?, tree_data = ? WHERE id = ?");
        $stmt->execute([
            $input['title'],
            json_encode($input['tree_data']),
            $input['id']
        ]);
        echo json_encode(['status' => 'success']);
    } else {
        throw new Exception("Invalid input.");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
