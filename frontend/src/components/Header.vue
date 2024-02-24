<template>    
    <header class="header lock_padding">
        <a class="header__logo" href="/">
        <img src="../assets/img/logo.png" alt="LOGO" class="header__logo__icon">
        </a> 
        <div class="header__bar">
        <div class="header__bar__search">
            <input placeholder="Поиск..." type="text" class="header__bar__search__input">
            <a href="/" class="header__bar__search__button"></a>
        </div>
        <div class="header__bar__subbar">
            <a href="/createarticle/ru" class="header__bar__subbar__createarticle"><p>Написать статью</p></a>
            <select name="select" id="header__bar__subbar__select">
                <option value="RU">RU</option>
                <option value="EN">EN</option>
            </select>
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
</template>