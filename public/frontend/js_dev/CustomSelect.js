class CustomSelect {
    constructor(customSelectElement) 
    {
        this.customSelectElement = customSelectElement;
        this.selectBtn = this.customSelectElement.querySelector(".select-button");
        this.selectedValue = this.customSelectElement.querySelector(".selected-value");
        this.optionsList = this.customSelectElement.querySelectorAll(".select-dropdown-option");
  
        this.selectBtn.addEventListener("click", () => {this.OnClickSelectBtn()});

        this.optionsList.forEach((option) => 
        {
            option.addEventListener("keyup", (e) => this.OnKeyUpOption(e, option));
            option.addEventListener("mousedown", (e) => {this.OnClickOption(e, option)});
        });
    }
  
    OnClickSelectBtn() 
    {
        this.customSelectElement.classList.toggle("active");
        this.selectBtn.setAttribute(
            "aria-expanded",
            this.selectBtn.getAttribute("aria-expanded") === "true" ? "false" : "true"
        );
    }
  
    OnKeyUpOption(e, option) 
    {
      if (e.key === "Enter" && e.type === "keyup") {
        this.selectedValue.textContent = option.textContent;
        this.customSelectElement.classList.remove("active");
      }
    }
  
    OnClickOption(e, option) 
    {
      if (e.clientX !== 0 && e.clientY !== 0) {
        this.selectedValue.textContent = option.children[1].textContent;
        this.customSelectElement.classList.remove("active");
      }
    }
}
  
const customSelects = document.querySelectorAll(".custom-select");
const customSelectsObjects = [];

customSelects.forEach((customSelectElement) => {
    customSelectsObjects.push(new CustomSelect(customSelectElement));
});