const burger = document.querySelector(".header-burger");
const menu = document.querySelector(".header-menu");
const body = document.querySelector("body");
let unlocker = 1;
burger.addEventListener("click" , () => {
  if (unlocker == 1) 
  {
    console.log(unlocker);
    body.setAttribute('style', 'padding-right:' + scrollWidth + 'px;overflow: hidden;');
    unlocker = 0;
  } 
  else 
  {
    console.log(unlocker);
    body.setAttribute('style', 'padding-right:0px;overflow: hidden;');
    unlocker = 1;
  }
  
  menu.classList.toggle("active");
  burger.classList.toggle("active");
});