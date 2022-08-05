// PR
'use strict';
let burger = document.querySelector('.menu__burger');
burger.addEventListener('click', function(event) {
    let menu = document.querySelector('.menu__wrapper');
    menu.classList.toggle('_open');
});
