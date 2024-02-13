class CustomSelect {
  private customSelectElement: HTMLElement;
  private selectBtn: HTMLElement;
  private selectedValue: HTMLElement;
  private optionsList: NodeListOf<HTMLElement>;

  constructor(customSelectElement: HTMLElement) {
    this.customSelectElement = customSelectElement;
    this.selectBtn = this.customSelectElement.querySelector(".select-button") as HTMLElement;
    this.selectedValue = this.customSelectElement.querySelector(".selected-value") as HTMLElement;
    this.optionsList = this.customSelectElement.querySelectorAll(".select-dropdown-option");

    this.selectBtn.addEventListener("click", () => this.OnClickSelectBtn());

    this.optionsList.forEach((option) => {
      option.addEventListener("keyup", (e) => this.OnKeyUpOption(e, option));
      option.addEventListener("mousedown", (e) => this.OnClickOption(e, option));
    });
  }

  private OnClickSelectBtn(): void {
    this.customSelectElement.classList.toggle("active");
    this.selectBtn.setAttribute(
      "aria-expanded",
      this.selectBtn.getAttribute("aria-expanded") === "true" ? "false" : "true"
    );
  }

  private OnKeyUpOption(e: KeyboardEvent, option: HTMLElement): void {
    if (e.key === "Enter" && e.type === "keyup") {
      this.selectedValue.textContent = option.textContent;
      this.customSelectElement.classList.remove("active");
    }
  }

  private OnClickOption(e: MouseEvent, option: HTMLElement): void {
    if (e.clientX !== 0 && e.clientY !== 0) {
      this.selectedValue.textContent = option.children[1].textContent;
      this.customSelectElement.classList.remove("active");
    }
  }
}

const customSelects = document.querySelectorAll(".custom-select");
const customSelectsObjects: CustomSelect[] = [];

customSelects.forEach((customSelectElement) => {
  customSelectsObjects.push(new CustomSelect(customSelectElement as HTMLElement));
});