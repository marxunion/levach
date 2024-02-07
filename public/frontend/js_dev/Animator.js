window.addEventListener("load", () => {
  const animItems = document.querySelectorAll(".anim-item");

  if (animItems.length > 0) {
    window.addEventListener("scroll", animOnScroll);
    function animOnScroll() {
      for (let index = 0; index < animItems.length; index++) {
        const animItem = animItems[index];
        const animItemHeight = animItem.offsetHeight;
        let animItemOffset = getOffset(animItem).top;
        const animCofficient = 4;
        
        if (animItem.getAttribute("data-predmatch") !== null) {
          let value = animItem.getAttribute("data-predmatch");
          animItemOffset = animItemOffset - value;
        }

        let animItemPoint = window.innerHeight - animItemHeight / animCofficient;
        if (animItemHeight > window.innerHeight) {
          animItemPoint = window.innerHeight - window.innerHeight / animCofficient;
        }
        if (
          pageYOffset > animItemOffset - animItemPoint &&
          pageYOffset < animItemOffset + animItemHeight
        ) {
          animItem.classList.add("anim_active");
        } else {
          if (!animItem.classList.contains("always_active")) {
            animItem.classList.remove("anim_active");
          }
        }
      }
    }
    setTimeout(() => {
      animOnScroll();
    }, 200);
    function getOffset(el) {
      const rect = el.getBoundingClientRect(),
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      return { top: rect.top + scrollTop, left: rect.left + scrollLeft };
    }
  }
});