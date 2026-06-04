<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin serveurs</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="p-4">

<h3>Connexion PostgreSQL</h3>

<div class="row g-2 mb-3">
    <div class="col"><input class="form-control" id="host" placeholder="Serveur"></div>
    <div class="col"><input class="form-control" id="port" placeholder="Port"></div>
    <div class="col"><input class="form-control" id="dbname" placeholder="Base"></div>
    <div class="col"><input class="form-control" id="user" placeholder="Utilisateur"></div>
    <div class="col"><input class="form-control" id="pass" type="password" placeholder="Mot de passe"></div>
    <div class="col">
        <button class="btn btn-primary w-100" id="connectBtn">Connexion</button>
    </div>
</div>

<div id="alertZone"></div>

<table class="table table-bordered" id="serverTable">
    <thead class="table-light">
        <tr>
            <th>Nom serveur</th>
            <th>Port</th>
            <th>User</th>
            <th>Type DB</th>
            <th style="width:90px">Modifier</th>
            <th style="width:90px">Supprimer</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- MODAL EDIT -->
<div class="modal fade" id="editModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Modifier serveur</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<input type="hidden" id="editId">
<input class="form-control mb-2" id="editName" placeholder="Nom serveur">
<input class="form-control mb-2" id="editPort" placeholder="Port">
<input class="form-control mb-2" id="editUser" placeholder="Utilisateur">
<input class="form-control mb-2" id="editType" placeholder="Type DB">
</div>
<div class="modal-footer">
<button class="btn btn-success" id="saveEdit">Enregistrer</button>
</div>
</div>
</div>
</div>

<script>
/* =========================
   GLOBAL
========================= */
let dbData = {};

/* =========================
   UI HELPERS
========================= */
function showAlert(msg, type, timeout = null) {
    $("#alertZone").html(`<div class="alert alert-${type}">${msg}</div>`);
    if (timeout) setTimeout(() => $("#alertZone").html(""), timeout);
}

function setConnectionInputsDisabled(state = true) {
    const ids = ["host", "port", "dbname", "user", "pass", "connectBtn"];
    ids.forEach(id => $("#" + id).prop("disabled", state));
}

/* =========================
   CONNECTION
========================= */

$("#connectBtn").on("click", () => {
    dbData = {
        host: $("#host").val(),
        port: $("#port").val(),
        dbname: $("#dbname").val(),
        user: $("#user").val(),
        pass: $("#pass").val()
    };

    $.post("connect.php", dbData, res => {
        if(res.success){
            showAlert("Connexion réussie", "success", 5000);
            setConnectionInputsDisabled(true);
            loadServers()
        }
        else{
            showAlert(res.error, "danger");
        }
    }, "json");
});

$("#connectBtn").text("Connecté").removeClass("btn-primary").addClass("btn-success");

/* =========================
   TABLE RENDER
========================= */
function renderTable(servers = []) {
    let html = "";

    if (servers.length === 0) {
        html += `
        <tr>
            <td colspan="6" class="text-center text-muted">
                Aucun serveur enregistré
            </td>
        </tr>`;
    }

    servers.forEach(s => {
        html += `
        <tr>
            <td><a href="server_detail.php?id=${s.id}">${s.server_name}</a></td>
            <td>${s.server_port}</td>
            <td>${s.sql_user}</td>
            <td>${s.db_type}</td>
            <td>
                <button class="btn btn-warning btn-sm" onclick='openEdit(${JSON.stringify(s)})'>✏️</button>
            </td>
            <td>
                <button class="btn btn-danger btn-sm" onclick="deleteServer(${s.id})">🗑</button>
            </td>
        </tr>`;
    });

    /* LIGNE AJOUT */
    html += `
    <tr class="table-success">
        <td><input class="form-control" id="addName" placeholder="Nom serveur"></td>
        <td><input class="form-control" id="addPort" placeholder="Port"></td>
        <td><input class="form-control" id="addUser" placeholder="Utilisateur"></td>
        <td><input class="form-control" id="addType" placeholder="Type DB"></td>
        <td colspan="2">
            <button class="btn btn-success w-100" onclick="addServer()">➕ Ajouter</button>
        </td>
    </tr>`;

    $("#serverTable tbody").html(html);
}

/* =========================
   LOAD SERVERS
========================= */
function loadServers() {
    $.post("list_servers.php", dbData, res => renderTable(res), "json");
}

/* =========================
   ADD SERVER
========================= */
function addServer() {
    $.post("add_server.php", {
        ...dbData,
        name: $("#addName").val(),
        port: $("#addPort").val(),
        user: $("#addUser").val(),
        type: $("#addType").val()
    }, res => {
        if (res.success) {
            showAlert("Serveur ajouté", "success", 5000);
            loadServers();
        } else {
            showAlert(res.error, "danger");
        }
    }, "json");
}

/* =========================
   DELETE
========================= */
function deleteServer(id) {
    if (!confirm("Confirmer la suppression ?")) return;

    $.post("delete_server.php", {...dbData, id}, () => {
        showAlert("Serveur supprimé", "success", 5000);
        loadServers();
    }, "json");
}

/* =========================
   EDIT
========================= */
function openEdit(s) {
    $("#editId").val(s.id);
    $("#editName").val(s.server_name);
    $("#editPort").val(s.server_port);
    $("#editUser").val(s.sql_user);
    $("#editType").val(s.db_type);
    new bootstrap.Modal('#editModal').show();
}

$("#saveEdit").click(() => {
    $.post("update_server.php", {
        ...dbData,
        id: $("#editId").val(),
        name: $("#editName").val(),
        port: $("#editPort").val(),
        user: $("#editUser").val(),
        type: $("#editType").val()
    }, () => {
        showAlert("Modification réussie", "success", 5000);
        loadServers();
        bootstrap.Modal.getInstance('#editModal').hide();
    }, "json");
});
</script>

</body>
</html>