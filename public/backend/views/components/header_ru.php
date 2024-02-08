<header class="header lock_padding">
  <a class="header__logo" href="/">
    <img src="/frontend/img/logo/logo.png" alt="LOGO" class="header__logo__icon">
  </a> 
  <div class="header__bar">
    <div class="header__bar__search">
      <input placeholder="Поиск..." type="text" class="header__bar__search__input">
      <a href="/" class="header__bar__search__button"></a>
    </div>
    <div class="header__bar__subbar">
      <a href="/" class="header__bar__subbar__createarticle"><p>Написать статью</p></a>
      <div class="header__bar__subbar__select custom-select">
        <button
          class="header__bar__subbar__select__button select-button"
          role="combobox"
          aria-labelledby="select button"
          aria-haspopup="listbox"
          aria-expanded="false"
          aria-controls="select-dropdown"
        >
          <span class="header__bar__subbar__select__value selected-value">RU</span>
          <span class="header__bar__subbar__select__arrow arrow"></span>
        </button>
        <ul class="header__bar__subbar__select__dropdown select-dropdown" role="listbox" id="select-dropdown">
          <a href="/ru" class="header__bar__subbar__select__dropdown__option select-dropdown-option" role="option">
            <label for="ru">RU</label>
          </a>
          <a href="/en" class="header__bar__subbar__select__dropdown__option select-dropdown-option" role="option">
            <label for="en">EN</label>
          </a>
        </ul>
      </div>
      <a class="header__bar__subbar__searchmobile">
        <div class="header__bar__subbar__searchmobile__icon"></div>
      </a>
      <a href="/newarticle" class="header__bar__subbar__createarticlemobile">
        <div class="header__bar__subbar__createarticlemobile__icon"></div>
      </a>
    </div>
  </div>
  <div class="header__burger header-burger">
    <div class="header__burger__lines header-burger"></div>
  </div>
</header>