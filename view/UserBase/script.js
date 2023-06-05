const url = 'http://localhost/trabalho/controllers/UserController.php';

const data = {
    method: 'createUser',
    name: 'John Doe',
    email: 'johndoe@example.com'
};

const options = {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
};

fetch(url, options)
    .then(response => response.json())
    .then(result => {
        console.log('Response:', result);
    })
    .catch(error => {
        console.error('Error:', error);
    });
