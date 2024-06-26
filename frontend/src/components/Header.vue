<script setup lang="ts">
    import { ComputedRef } from 'vue';
    import { useRoute, useRouter, RouteLocationNormalizedLoaded, Router } from 'vue-router';

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
</script>

<template>
    <header class="header lock_padding">
        <a class="header__logo" href="#/">
            <img src="../assets/img/logo/logo.svg" alt="LOGO" class="header__logo__icon">
        </a>
        <div class="header__bar">
            <div class="header__bar__search">
                <input @keyup.enter="onSearchButton" v-model="searchText" :placeholder="(langData['search'] as string)" type="text" class="header__bar__search__input">
                <a @click="onSearchButton" class="header__bar__search__button"></a>
            </div>
            <div class="header__bar__subbar">
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