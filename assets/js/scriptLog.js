let items = document.querySelectorAll('nav ul li');
let active = 0;
let firstPosition = 0;
let lastPosition = items.length - 1;
let navigation = document.querySelectorAll('nav ul li');
let path = ['index.html', 'login.html', 'contato.html'];

navigation.forEach((item, index) => {
    item.addEventListener('click', () => {
        window.location.href = path[index];
    });
});
