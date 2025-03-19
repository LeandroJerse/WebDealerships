let leftButton = document.querySelector('#left-button');
let rightButton = document.querySelector('#right-button');
let container = document.querySelector('.container');
let items = container.querySelectorAll('.list .item');
let indicators = document.querySelector('.indicators');
let dots = indicators.querySelectorAll('ul li');

let active = 0;
let fistPosition = 0;
let lastPosition = items.length - 1;

function removeOldActive() {
    let itemOld = container.querySelector('.list .item.active-item');
    itemOld.classList.remove('active-item');
}

/*Função que aumenta o numero do slide */
function createDots() {
    let dotsOld = indicators.querySelector('ul li.active');
    dotsOld.classList.remove('active');
    dots[active].classList.add('active');
    indicators.querySelector('.number').innerText = (active + 1).toString().padStart(2, '0');
}

/*Função dos botões*/
rightButton.addEventListener('click', () => {
    removeOldActive();

    active = active + 1 > lastPosition ? fistPosition : active + 1;
    items[active].classList.add('active-item');
    createDots();
});

leftButton.addEventListener('click', () => {
    removeOldActive();

    active = active - 1 < fistPosition ? lastPosition : active - 1;
    items[active].classList.add('active-item');

    createDots();
});

let navigation = document.querySelectorAll('nav ul li');
let path = ['index.html', 'login.html', 'contato.html'];

navigation.forEach((item, index) => {
    item.addEventListener('click', () => {
        window.location.href = path[index];
    });
});

const menuHamburguer = document.querySelector('.menu');
menuHamburguer.addEventListener('click', () => {
    toggleMenu();
});

toggleMenu = () => {
    menuHamburguer.classList.toggle('change');
    let navigation = document.querySelector('.nav-responsive');
    navigation.classList.toggle('change');
    if (navigation.classList.contains('change')) {
        navigation.style.display = 'block';
    } else {
        navigation.style.display = 'none';
    }


    if (document.querySelector('.nav-responsive').classList.contains('change')) {
        document.body.classList.add('spand');
    } else {
        document.body.classList.remove('spand');
    }
    
}


window.addEventListener('resize', () => {
    if (window.innerWidth > 760) {
        if (document.body.classList.contains('spand')) {
            document.body.classList.remove('spand');

            if (menuHamburguer.classList.contains('change')){
                toggleMenu();

            }

        }
    }
});



