<?php
$pdo = new PDO(
    "pgsql:host={$_POST['host']};port={$_POST['port']};dbname={$_POST['dbname']}",
    $_POST['user'],
    $_POST['pass']
);

$stmt = $pdo->query("
    SELECT id, server_name, server_port, sql_user, db_type
    FROM tbl_list_server WHERE status = 1
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));