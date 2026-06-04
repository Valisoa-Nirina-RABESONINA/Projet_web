<?php
try {
    $pdo = new PDO(
        pgsqlhost={$_POST['host']};port={$_POST['port']};dbname={$_POST['dbname']},
        $_POST['user'],
        $_POST['pass'],
        [PDOATTR_ERRMODE = PDOERRMODE_EXCEPTION]
    );

    $stmt = $pdo-prepare(
        INSERT INTO tbl_list_server
        (server_name, server_port, sql_user, db_type, status)
        VALUES (, , , , 1)
    );

    $stmt-execute([
        $_POST['name'],
        $_POST['port'],
        $_POST['user'],
        $_POST['type']
    ]);

    echo json_encode(['success' = true]);

} catch (Exception $e) {
    echo json_encode([
        'success' = false,
        'error' = 'Erreur lors de l’ajout'
    ]);
}