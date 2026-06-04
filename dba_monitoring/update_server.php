<?php
$pdo = new PDO(
"pgsql:host={$_POST['host']};port={$_POST['port']};dbname={$_POST['dbname']}",
$_POST['user'],
$_POST['pass']
);


$stmt = $pdo->prepare("
UPDATE tbl_list_server
SET server_name=?, server_port=?, sql_user=?, db_type=?
WHERE id=?
");
$stmt->execute([
$_POST['name'],
$_POST['port'],
$_POST['user'],
$_POST['type'],
$_POST['id']
]);


echo json_encode(['success' => true]);