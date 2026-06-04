<?php

require_once '../../connect.php';

header('Content-Type: application/json');

$text_menu =
trim($_POST['text_menu'] ?? '');

$description_menu =
trim($_POST['description_menu'] ?? '');

if($text_menu === '')
{
    echo json_encode([
        'status' => 'error',
        'message' => 'Titre obligatoire'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Vérifier existence menu
|--------------------------------------------------------------------------
*/

$sql =
"
SELECT id
FROM tbl_menu
WHERE LOWER(text_menu) = LOWER(:text_menu)
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':text_menu' => $text_menu
]);

$exist = $stmt->fetch();

if($exist)
{
    echo json_encode([
        'status' => 'error',
        'message' => 'Le menu existe déjà'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Insertion
|--------------------------------------------------------------------------
*/

$sql =
"
INSERT INTO tbl_menu
(
    text_menu,
    description_menu
)
VALUES
(
    :text_menu,
    :description_menu
)
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':text_menu' => $text_menu,
    ':description_menu' => $description_menu
]);

echo json_encode([
    'status' => 'success',
    'message' => 'Menu enregistré avec succès'
]);