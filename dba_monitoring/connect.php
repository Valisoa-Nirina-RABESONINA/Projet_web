<?php
try {
    new PDO(
        "pgsql:host={$_POST['host']};port={$_POST['port']};dbname={$_POST['dbname']}",
        $_POST['user'],
        $_POST['pass']
    );
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}