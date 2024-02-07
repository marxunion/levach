const burger = document.querySelector(".header__burger");
const menu = document.querySelector(".header__menu");
const bodt = document.querySelector("body");
const account_text = document.querySelectorAll(".header__account__text");
const nav_links = document.querySelectorAll(".header__nav__link");
let unlocker = 1;
burger.addEventListener("click" , () => {
  if (unlocker == 1) {
    console.log(unlocker);
    body.setAttribute('style', 'padding-right:' + scrollWidth + 'px;overflow: hidden;');
    unlocker = 0;
  } else {
    console.log(unlocker);
    body.setAttribute('style', 'padding-right:0px;overflow: hidden;');
    unlocker = 1;
    wdsds
  }
  
  menu.classList.toggle("active");
  
  burger.classList.toggle("active");
  account_text.forEach(el => {
    el.classList.toggle("anim_actived");
  });
  nav_links.forEach(el => {
    el.classList.toggle("anim_actived");
  });
});