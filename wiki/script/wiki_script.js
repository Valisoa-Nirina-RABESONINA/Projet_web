
/*
|--------------------------------------------------------------------------
| SIDEBAR
|--------------------------------------------------------------------------
*/
console.log("WIKI JS LOADED");

function toggleSidebar()
{
    document
        .getElementById('sidebar')
        .classList
        .toggle('hidden');
}


/*
|--------------------------------------------------------------------------
| TREE MENU
|--------------------------------------------------------------------------
*/

function toggleTree(id)
{
    document
        .getElementById(id)
        .classList
        .toggle('show');
}


/*
|--------------------------------------------------------------------------
| LOAD PAGE
|--------------------------------------------------------------------------
*/

function loadWikiPage(page)
{
    document.getElementById('wiki-content').innerHTML =
        `
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
        </div>
        `;

    fetch(page)

        .then(response => response.text())

        .then(data => {

            document.getElementById('wiki-content').innerHTML = data;

        })

        .catch(error => {

            document.getElementById('wiki-content').innerHTML =
                `
                <div class="alert alert-danger">
                    Erreur de chargement.
                </div>
                `;

            console.error(error);

        });
}

function createMenu()
{
    alert("Créer un nouveau menu (formulaire à faire)");
}



function searchPages(value)
{
    console.log("Recherche :", value);

    // plus tard : AJAX vers DB
}


/*
|--------------------------------------------------------------------------
| CREATE PAGE
|--------------------------------------------------------------------------
*/


function showAlert(message, status)
{
    let alertBox =
    document.getElementById('alertBox');

    let div =
    document.createElement('div');

    div.className =
    'alert-custom ' +
    (status === 'error'
        ? 'alert-danger'
        : 'alert-success');

    div.innerHTML = message;

    alertBox.appendChild(div);

    setTimeout(() =>
    {
        div.remove();
    }, 5000);
}
function initWiki()
{
    const menuForm =
    document.getElementById('menuForm');

    if(menuForm)
    {
        menuForm.addEventListener('submit', submitMenu);
    }

    const menuModal =
    document.getElementById('menuModal');

    if(menuModal)
    {
        menuModal.addEventListener(
            'shown.bs.modal',
            function()
            {
                const input =
                menuModal.querySelector(
                    'input[name="text_menu"]'
                );

                if(input)
                {
                    input.focus();
                }
            }
        );
    }
}
