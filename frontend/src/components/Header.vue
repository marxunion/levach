<script setup lang="ts">
    import { ComputedRef } from 'vue';
    import { useRoute, useRouter, RouteLocationNormalizedLoaded, Router } from 'vue-router';

    import { ThemeHandler } from '../ts/handlers/ThemeHandler';

    import DropDown from "./DropDown.vue";

    import { JsonData } from '../ts/interfaces/JsonData';

    import "./scss/Header.scss"

    import { openModal } from "jenesius-vue-modal";
    import SearchMobileModal from "./../components/modals/SearchMobileModal.vue";

    import { LangDataHandler } from "../ts/handlers/LangDataHandler";
    import langsData from "./locales/Header.json";

    import { searchText, searchQuery } from "../ts/handlers/SearchHandler";
    
    import { defineEmits } from 'vue';

    const emits = defineEmits(['toggleBurger']);

    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("Header", langsData).langData;

    const route : RouteLocationNormalizedLoaded = useRoute();
    const router : Router = useRouter();

    const onSearchButton = () =>
    {
        if(isCurrentRouteName('editoriallyArticles') || isCurrentRouteName('editoriallyApprovedArticles') || isCurrentRouteName('abyssArticles') || isCurrentRouteName('articlesWaitingPremoderate') || isCurrentRouteName('articlesWaitingApproval'))
        {
            searchQuery.value = true;
        }
        else
        {
            if(searchText.value.length > 0)
            {
                searchQuery.value = true;
                router.push('/articles/search/'+encodeURIComponent(searchText.value));
            }
        }
    }

    const isCurrentRouteName = (routeName: string) : boolean => 
    {
        return routeName == route.name ? true : false;
    }

    const onThemeSwitchClick = () => 
    {
        ThemeHandler.instance.changeTheme(ThemeHandler.instance.getCurrentTheme.value == 'light' ? "dark" : "light");
    }
</script>

<template>
    <header class="header lock_padding">
        <a class="header__logo" href="#/">
            <img src="../assets/img/logo/logo.svg" alt="LOGO" class="header__logo__icon">
        </a>
        <div class="header__bar">
            <div class="header__bar__search">
                <input @keyup.enter="onSearchButton" v-model="searchText" :placeholder="(langData['search'] as string)" type="text" class="header__bar__search__input">
                <svg @click="onSearchButton" class="header__bar__search__button" viewBox="0 0 31 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="13" cy="13" r="11.5" stroke-width="3"/>
                    <line x1="20.5617" y1="22.7506" x2="28.5617" y2="32.7506" stroke-width="4"/>
                </svg>
            </div>
            
            <div class="header__bar__subbar">
                <label class="header__bar__subbar__switch">
                    <input type="checkbox" @change="onThemeSwitchClick" :checked="ThemeHandler.instance.getCurrentTheme.value == 'light' ? false : true" id="theme__switcher" class="header__bar__subbar__switch__checkbox">
                    <span class="header__bar__subbar__switch__round"></span>
                </label>
                <a href="#/article/new" class="header__bar__subbar__createarticle">{{ langData['createArticle'] }}</a>
                <DropDown :options="LangDataHandler.langs" :default="LangDataHandler.currentLanguage.value" class="header__bar__subbar__select" @input="LangDataHandler.changeLanguage" @input-on-mounted="LangDataHandler.changeLanguage"/>
                <p class="header__bar__subbar__searchmobile" @click="openModal(SearchMobileModal)">
                    <img src="../assets/img/header/searchiconsmall.svg" alt="Search" class="header__bar__subbar__searchmobile__icon">
                </p>
                <a href="#/article/new" class="header__bar__subbar__createarticlemobile">
                    <img src="../assets/img/header/createarticle.svg" alt="Create Article" class="header__bar__subbar__createarticlemobile__icon">
                </a>
            </div>
        </div>
        <div class="header__burger header-burger" @click="$emit('toggleBurger')">
            <div class="header__burger__lines header-burger"></div>
        </div>
    </header>
</template>