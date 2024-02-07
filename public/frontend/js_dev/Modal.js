class ModalDialogSwitch {
  constructor (modal, modal_content , modal_closer , elements_lock_paddings, scrollWidth = window.innerWidth - document.querySelector('body').clientWidth ){
    this.modal = modal;
    this.modal_content = modal_content;
    this.modal_closer = modal_closer;
    this.elements_lock_paddings = elements_lock_paddings;
    this.scrollWidth = scrollWidth;
    this.body = document.querySelector('body');
    this.modal_content_className = modal_content.className;

  }
  _closeDialog(timeout_modal_closed, class_name_lock_padding ) {
    this.modal_content.classList.remove("active");
    setTimeout(() => {
      this.modal.classList.remove("active");
    }, timeout_modal_closed);
    
    this.body.classList.remove(class_name_lock_padding);
    this.body.setAttribute('style', 'padding-right:0px');
    if (this.elements_lock_paddings.length > 0) {
      this.elements_lock_paddings.forEach(el => {
        el.setAttribute('style', 'padding-right:0px');
      });
    }
  }
  activateDialog(timeout_modal_content_activate = 100 ,timeout_modal_closed = 700, class_name_lock_padding = 'lock-padding-modal' ) {
    this.body.classList.add(class_name_lock_padding);
    this.body.setAttribute('style', 'padding-right:' + this.scrollWidth + 'px');


    if (this.elements_lock_paddings.length > 0 ) {
      this.elements_lock_paddings.forEach(el => {
        el.setAttribute('style', 'padding-right:' + this.scrollWidth + 'px');
      });
    }
    
    this.modal.classList.add("active");
    setTimeout(() => {
      this.modal_content.classList.add("active");
    }, timeout_modal_content_activate);

    
    
    setTimeout(() => {
      this.modal.addEventListener("click", (e) => {
        if (!e.target.classList.contains(this.modal_content_className)) {
          this._closeDialog(timeout_modal_closed, class_name_lock_padding);
        }
      });
      this.modal_closer.addEventListener("click", (e) => {
        e.stopPropagation();
        this._closeDialog(timeout_modal_closed, class_name_lock_padding);
      });
    }, timeout_modal_content_activate);
    
  }
}