<?php
$pdo = new PDO(
    "pgsql:host={$_POST['host']};port={$_POST['port']};dbname={$_POST['dbname']}",
    $_POST['user'],
    $_POST['pass']
);

$stmt = $pdo->prepare("UPDATE tbl_list_server SET status = 0 WHERE id = ?");
$stmt->execute([$_POST['id']]);

echo json_encode(['success' => true]);