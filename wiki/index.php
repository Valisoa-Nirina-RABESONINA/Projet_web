<?php


require_once __DIR__ . '/connect.php';
$pdo = Database::getInstance()->getConnection();

/*
|--------------------------------------------------------------------------
| HISTORIQUE DES PAGES VISITÉES
|--------------------------------------------------------------------------
*/

$sqlHistory = "
SELECT a.id, b.text_menu
FROM tbl_page_history a
INNER JOIN tbl_menu b on b.id = a.id_menu
ORDER BY a.date_access DESC
LIMIT 5
";

$stmtHistory = $pdo->query($sqlHistory);
$historyMenus = $stmtHistory->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| LISTE DES PAGES + MENUS
|--------------------------------------------------------------------------
*/
// tokony liste menu ny eto, de anatin'ny menu no misy ny page tsirairay
$sqlPages = "
    SELECT
        id,
        text_menu
    FROM tbl_menu
    ORDER BY text_menu ASC
";

$stmtPages = $pdo->query($sqlPages);
$pages = $stmtPages->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| REGROUPEMENT PAR MENU
|--------------------------------------------------------------------------
*/

$menus = [];

foreach ($pages as $page) {

    $menuId = $page['id'];

    if (!isset($menus[$menuId])) {

        $menus[$menuId] = [
            'menu_name' => 'Menu ' . $menuId,
            'pages' => []
        ];
    }

    $menus[$menuId]['pages'][] = $page;
}

?>


<div id="alertBox"></div>

<!-- ========================================================= -->
<!-- HEADER -->
<!-- ========================================================= -->

<div class="wiki-toolbar">

    <!-- toggle menu -->
    <button class="tool-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- search -->
    <div class="tool-search">
        <input type="text" placeholder="Rechercher une page..." onkeyup="searchPages(this.value)">
    </div>

    <!-- add menu -->
    <button class="tool-btn" data-bs-toggle="modal" data-bs-target="#menuModal">
        <i class="fas fa-folder-plus"></i>
    </button>

</div>

<!-- ========================================================= -->
<!-- LAYOUT -->
<!-- ========================================================= -->

<div class="wiki-layout">

    <!-- SIDEBAR -->
    <div class="wiki-sidebar" id="sidebar">

        <!-- HISTORY -->
        <div class="sidebar-section">

            <div class="sidebar-title">
                Dernières pages
            </div>

            <?php foreach($historyMenus as $history): ?>

                <div class="history-item">

                    <i class="fas fa-history me-2"></i>

                    <?= htmlspecialchars($history['text_menu']) ?>

                </div>

            <?php endforeach; ?>

        </div>

        <!-- TREE MENU -->
        <div class="sidebar-section">

            <div class="sidebar-title">
                Toutes les pages
            </div>

            <?php foreach($menus as $menuId => $menu): ?>

                <div class="tree-menu">

                    <div class="tree-header">

                        <span onclick="toggleTree('menu<?= $menuId ?>')">
                            <i class="fas fa-folder me-2"></i>
                            <?= htmlspecialchars($menu['menu_name']) ?>
                        </span>

                        <button class="mini-add" onclick="createPage(<?= $menuId ?>)">
                            <i class="fas fa-plus"></i>
                        </button>

                    </div>

                    <div
                        class="tree-pages"
                        id="menu<?= $menuId ?>"
                    >

                        <?php foreach($menu['pages'] as $page): ?>

                            <div
                                class="tree-page"
                                onclick="loadWikiPage('<?= $page['lien_page'] ?>')"
                            >

                                <i class="fas fa-file-alt me-2"></i>

                                <?= htmlspecialchars($page['titre_page']) ?>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="wiki-content">

        <div class="content-card" id="wiki-content">

            <h1>Bienvenue dans PAPAO Wiki</h1>

            <hr>

            <p>
                Sélectionnez une page dans le menu de gauche.
            </p>

        </div>

    </div>

</div>

<!-- Modal Ajout Menu -->
<div class="modal fade" id="menuModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form id="menuForm">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Nouveau Menu
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Titre du menu
                        </label>

                        <input type="text"
                               class="form-control"
                               name="text_menu"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea class="form-control"
                                  name="description_menu"
                                  maxlength="2000"
                                  rows="5"></textarea>

                        <small class="text-muted">
                            Maximum 2000 caractères
                        </small>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-primary">

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

