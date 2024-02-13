const burger: HTMLElement | null = document.querySelector(".header-burger");
const menu: HTMLElement | null = document.querySelector(".header-menu");

if (burger && menu) {
  burger.addEventListener("click", () => {
    //menu.classList.toggle("active");
    burger.classList.toggle("active");
  });
}