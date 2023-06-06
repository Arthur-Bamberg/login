import { Connector } from "../Connector.js";

const form = document.querySelector('form');

(async () => {
    createUsersList();
    createRolesList();
    form.addEventListener('submit', createUser);
})();

async function createUsersList() {
    const list = document.querySelector('.list-group');
    const connector = new Connector('UserController');
    const data = await connector.getRequest('getAll');

    data.forEach(function (user) {
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
        name.innerText = 'Nome: ' + user.name;

        const username = document.createElement('p');
        username.innerText = 'Apelido: ' + user.username;

        const phone = document.createElement('p');
        phone.innerText = 'Telefone: ' + user.phone;

        const email = document.createElement('p');
        email.innerText = 'Email: ' + user.email;

        const roles = document.createElement('div');
        roles.innerHTML = '<h6>Funções:</h6>';

        user.roles.forEach(function (role) {
            const roleName = document.createElement('p');
            roleName.innerText = 'Nome: ' + role.name;

            const roleDescription = document.createElement('p');
            roleDescription.innerText = 'Descrição: ' + role.description;

            roles.appendChild(roleName);
            roles.appendChild(roleDescription);
        });

        li.appendChild(name);
        li.appendChild(username);
        li.appendChild(phone);
        li.appendChild(email);
        li.appendChild(roles);
        li.appendChild(div);
        li.setAttribute('id', user.idUser);

        list.appendChild(li);
    });
}

async function createRolesList() {
    const rolesContainer = document.getElementById('roles');

    const connector = new Connector('RoleController');
    const rolesData = await connector.getRequest('getAll');

    rolesData.forEach(function (role) {
        const div = document.createElement('div');

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = role.idRole;
        checkbox.name = 'roles';
        checkbox.value = role.idRole;

        const label = document.createElement('label');
        label.htmlFor = role.idRole;
        label.innerText = role.name;
        label.classList.add('ml-2');

        div.appendChild(checkbox);
        div.appendChild(label);

        rolesContainer.appendChild(div);
    });
}

async function createUser(event) {
    event.preventDefault();

    const inputs = form.querySelectorAll('input');

    const formData = {};
    inputs.forEach(function (input) {
        const name = input.getAttribute('name');
        const value = input.value;
        formData[name] = value;
    });

    formData['method'] = 'createUser';

    const connector = new Connector('UserController');
    const data = await connector.postRequest(formData);

    const checkboxes = document.querySelectorAll('input[name="roles"]:checked');
    const checkedIds = Array.from(checkboxes).map(checkbox => checkbox.id);

    for (const idRole of checkedIds) {
        const formData = {};
        formData['idUser'] = data.idUser;
        formData['idRole'] = idRole;
        formData['method'] = 'addRole';

        const connector = new Connector('UserController');
        const rolesData = await connector.postRequest(formData);
    }

    form.reset();

    await reloadScreen();
}

async function deleteItem(li) {
    const idUser = li.getAttribute('id');

    const formData = {};
    formData['idUser'] = idUser;
    formData['method'] = 'deleteUser';

    const connector = new Connector('UserController');
    const data = await connector.postRequest(formData);

    await reloadScreen();
}

async function editItem(li) {
    const checkboxes = document.querySelectorAll('input[name="roles"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = false;
    });

    const idUser = li.getAttribute('id');

    const connector = new Connector('UserController');
    const data = await connector.getRequest('getUser', `&idUser=${idUser}`);

    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input');
    inputs.forEach(function (input) {
        const name = input.getAttribute('name');
        input.value = data[name];
    });

    const roles = data.roles;

    for (const role of roles) {
        const checkbox = document.querySelector(`input[type="checkbox"][id="${role.idRole}"]`);
        if (checkbox) {
            checkbox.checked = true;
        }
    }

    const header = document.querySelector('#header');

    if (header.innerText != 'Editar Usuário') {
        header.innerText = 'Editar Usuário';

        const button = form.querySelector('button');
        button.innerText = 'Salvar';
        button.removeEventListener('click', createUser);

        const updateOnClick = async function (event) {
            event.preventDefault();
            await updateUser(idUser);
        }

        button.addEventListener('click', updateOnClick);

        const passwordDiv = document.querySelector('#passwordDiv');
        passwordDiv.classList.add('d-none');

        const cancelButton = document.createElement('button');
        cancelButton.classList.add('btn', 'btn-danger', 'btn-sm');
        cancelButton.innerText = 'Cancelar';
        cancelButton.id = 'cancelButton';

        cancelButton.addEventListener('click', cancelEdit);

        form.appendChild(cancelButton);
    }
}

async function updateUser(idUser) {
    const inputs = form.querySelectorAll('input');

    const formData = {};
    inputs.forEach(function (input) {
        const name = input.getAttribute('name');
        const value = input.value;
        formData[name] = value;
    });

    formData['idUser'] = idUser;
    formData['method'] = 'updateUser';

    const connector = new Connector('UserController');
    const data = await connector.postRequest(formData);

    const checkboxes = document.querySelectorAll('input[name="roles"]:checked');
    const checkedIds = Array.from(checkboxes).map(checkbox => checkbox.id);
    checkedIds.forEach(async function (idRole) {
        const formData = {};
        formData['idUser'] = idUser;
        formData['idRole'] = idRole;
        formData['method'] = 'addRole';

        const connector = new Connector('UserController');
        const rolesData = await connector.postRequest(formData);
    });

    const uncheckedCheckboxes = document.querySelectorAll('input[name="roles"]:not(:checked)');
    const uncheckedIds = Array.from(uncheckedCheckboxes).map(checkbox => checkbox.id);
    uncheckedIds.forEach(async function (idRole) {
        const formData = {};
        formData['idUser'] = idUser;
        formData['idRole'] = idRole;
        formData['method'] = 'removeRole';

        const connector = new Connector('UserController');
        const rolesData = await connector.postRequest(formData);
    });

    form.reset();

    cancelEdit();

    await reloadScreen();
}

function cancelEdit() {
    const form = document.querySelector('form');

    const header = document.querySelector('#header');
    header.innerText = 'Criar Usuário';

    const button = form.querySelector('button');
    button.innerText = 'Criar';
    button.removeEventListener('click', updateUser);
    button.addEventListener('click', createUser);

    const passwordDiv = document.querySelector('#passwordDiv');
    passwordDiv.classList.remove('d-none');

    const cancelButton = document.querySelector('#cancelButton');

    cancelButton.remove();

    form.reset();
}

async function reloadScreen() {
    const list = document.querySelector('.list-group');
    list.innerHTML = '';

    await createUsersList();
}