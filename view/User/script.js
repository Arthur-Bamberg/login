import { Connector } from "../Connector.js";

const form = document.querySelector('form');

(async () => {
    createUsersList();
    const button = form.querySelector('#submitButton');
    button.addEventListener('submit', createUser);
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

        const deleteButton = document.createElement('button');
        deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
        deleteButton.addEventListener('click', function () {
            deleteItem(li);
        });
        deleteButton.innerText = 'Excluir';

        const editButton = document.createElement('button');
        editButton.classList.add('btn', 'btn-primary', 'btn-sm');
        editButton.addEventListener('click', function () {
            editItem(li);
        });
        editButton.innerText = 'Editar';

        div.appendChild(deleteButton);
        div.appendChild(editButton);

        const name = document.createElement('h5');
        name.innerText = 'Nome: ' + user.name;

        const username = document.createElement('p');
        username.innerText = 'Apelido: ' + user.username;

        const phone = document.createElement('p');
        phone.innerText = 'Telefone: ' + user.phone;

        const email = document.createElement('p');
        email.innerText = 'Email: ' + user.email;

        li.appendChild(name);
        li.appendChild(username);
        li.appendChild(phone);
        li.appendChild(email);
        li.appendChild(div);
        li.setAttribute('id', user.idUser);

        list.appendChild(li);
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

    form.reset();

    reloadScreen();
}

async function deleteItem(li) {
    const idUser = li.getAttribute('id');

    const formData = {};
    formData['idUser'] = idUser;
    formData['method'] = 'deleteUser';

    const connector = new Connector('UserController');
    const data = await connector.postRequest(formData);

    reloadScreen();
}

async function editItem(li) {
    const idUser = li.getAttribute('id');

    const formData = {};
    formData['idUser'] = idUser;
    formData['method'] = 'getUser';

    const connector = new Connector('UserController');
    const data = await connector.postRequest(formData);

    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input');
    inputs.forEach(function (input) {
        const name = input.getAttribute('name');
        input.value = data[name];
    });

    const header = document.querySelector('#header');
    header.innerText = 'Editar Usuário';

    const button = form.querySelector('button');
    button.innerText = 'Editar';
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

    cancelButton.addEventListener('click', cancelEdit);

    form.appendChild(cancelButton);
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

    form.reset();

    cancelEdit();

    reloadScreen();
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

    const cancelButton = form.querySelector('.btn .btn-danger .btn-sm');

    form.removeChild(cancelButton);

    form.reset();
}

function reloadScreen() {
    const list = document.querySelector('.list-group');
    list.innerHTML = '';

    createUsersList();
}