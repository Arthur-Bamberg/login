import { Connector } from "../Connector.js";

const form = document.querySelector('form');

(async () => {
    createRolesList();
    form.addEventListener('submit', createRole);
})();

async function createRolesList() {
    const list = document.querySelector('.list-group');
    const connector = new Connector('RoleController');
    const data = await connector.getRequest('getAll');

    data.forEach(function (role) {
        const li = document.createElement('li');
        li.classList.add('list-group-item');

        const div = document.createElement('div');
        div.classList.add('float-right');

        const editButton = document.createElement('button');
        editButton.classList.add('btn', 'btn-primary', 'btn-sm');
        editButton.addEventListener('click', function () {
            editItem(li);
        });
        editButton.innerText = 'Editar';

        const deleteButton = document.createElement('button');
        deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
        deleteButton.addEventListener('click', function () {
            deleteItem(li);
        });
        deleteButton.innerText = 'Excluir';

        div.appendChild(editButton);
        div.appendChild(deleteButton);

        const name = document.createElement('h5');
        name.innerText = 'Nome da função: ' + role.name;

        const description = document.createElement('p');
        description.innerText = 'Descrição: ' + role.description;

        li.appendChild(name);
        li.appendChild(description);
        li.setAttribute('id', role.idRole);

        li.appendChild(div);
        list.appendChild(li);
    });
}

async function createRole(event) {
    event.preventDefault();

    const inputs = form.querySelectorAll('input');

    const formData = {};
    inputs.forEach(function (input) {
        const name = input.getAttribute('name');
        const value = input.value;
        formData[name] = value;
    });

    formData['method'] = 'createRole';

    const connector = new Connector('RoleController');
    const data = await connector.postRequest(formData);

    form.reset();

    await reloadScreen();
}

async function deleteItem(li) {
    const idRole = li.getAttribute('id');

    const formData = {};
    formData['idRole'] = idRole;
    formData['method'] = 'deleteRole';

    const connector = new Connector('RoleController');
    const data = await connector.postRequest(formData);

    await reloadScreen();
}

async function editItem(li) {
    const idRole = li.getAttribute('id');

    const connector = new Connector('RoleController');
    const data = await connector.getRequest('getRole', '&idRole=' + idRole);

    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input');
    inputs.forEach(function (input) {
        const name = input.getAttribute('name');
        input.value = data[name];
    });

    const header = document.querySelector('#header');
    header.innerText = 'Editar Função';

    const button = form.querySelector('button');
    button.innerText = 'Salvar';
    button.removeEventListener('click', createRole);

    const updateOnClick = async function (event) {
        event.preventDefault();
        await updateRole(idRole);
    }

    button.addEventListener('click', updateOnClick);

    const cancelButton = document.createElement('button');
    cancelButton.classList.add('btn', 'btn-danger', 'btn-sm');
    cancelButton.innerText = 'Cancelar';
    cancelButton.id = 'cancelButton';

    cancelButton.addEventListener('click', cancelEdit);

    form.appendChild(cancelButton);
}

async function updateRole(idRole) {
    const inputs = form.querySelectorAll('input');

    const formData = {};
    inputs.forEach(function (input) {
        const name = input.getAttribute('name');
        const value = input.value;
        formData[name] = value;
    });

    formData['idRole'] = idRole;
    formData['method'] = 'updateRole';

    const connector = new Connector('RoleController');
    const data = await connector.postRequest(formData);

    form.reset();

    cancelEdit();

    await reloadScreen();
}

function cancelEdit() {
    const form = document.querySelector('form');

    const header = document.querySelector('#header');
    header.innerText = 'Criar Função';

    const button = form.querySelector('button');
    button.innerText = 'Criar';
    button.removeEventListener('click', updateRole);    
    button.addEventListener('click', createRole);

    const cancelButton = document.querySelector('#cancelButton');

    cancelButton.remove();

    form.reset();
}

async function reloadScreen() {
    const list = document.querySelector('.list-group');
    list.innerHTML = '';

    await createRolesList();
}