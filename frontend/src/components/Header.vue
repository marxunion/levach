<script setup lang="ts">
    import { ref } from 'vue';
    import DropDown from "./DropDown.vue";

    import "./scss/Header.scss"

    import { LangDataHandler } from "./../ts/LangDataHandler";
    import langsData from "./locales/Header.json";
    
    const langData = ref(LangDataHandler.initLangDataHandler("Header", langsData).langData);
</script>

<template>
    <header class="header lock_padding">
        <a class="header__logo" href="#/">
            <img src="../assets/img/logo/logo.png" alt="LOGO" class="header__logo__icon">
        </a>
        <div class="header__bar">
            <div class="header__bar__search">
                <input :placeholder="(langData['search'] as string)" type="text" class="header__bar__search__input">
                <a href="/" class="header__bar__search__button"></a>
             </div>
            <div class="header__bar__subbar">
                <a href="#/article/new" class="header__bar__subbar__createarticle">{{ langData['createArticle'] }}</a>
                <DropDown :options="LangDataHandler.langs" :default="LangDataHandler.currentLanguage.value" class="header__bar__subbar__select" @input="LangDataHandler.changeLanguage" @input-on-mounted="LangDataHandler.changeLanguage"/>
                <a class="header__bar__subbar__searchmobile">
                    <div class="header__bar__subbar__searchmobile__icon"></div>
                </a>
                <a href="#/article/new" class="header__bar__subbar__createarticlemobile">
                    <div class="header__bar__subbar__createarticlemobile__icon"></div>
                </a>
            </div>
        </div>
        <div class="header__burger header-burger">
            <div class="header__burger__lines header-burger"></div>
        </div>
    </header>
</template>