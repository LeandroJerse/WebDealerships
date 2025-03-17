let leftButton = document.querySelector('#left-button');
let rightButton = document.querySelector('#right-button');
let container = document.querySelector('.container');
let items = container.querySelectorAll('.list .item');
let indicators = document.querySelector('.indicators');
let dots = indicators.querySelectorAll('ul li');
let readmore = [];
items.forEach(item => {
    readmore.push(item.querySelector('.content .read-more'));
});

let active = 0;
let fistPosition = 0;
let lastPosition = items.length - 1;


function removeOldActive() {
    let itemOld = container.querySelector('.list .item.active-item');
    itemOld.classList.remove('active-item');
}

/*Função que umenta o numero do slide */
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
})

