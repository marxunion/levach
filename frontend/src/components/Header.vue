<script setup lang="ts">
    import { ref } from 'vue';
    import { useRoute, useRouter } from 'vue-router';

    import DropDown from "./DropDown.vue";

    import "./scss/Header.scss"

    import { openModal } from "jenesius-vue-modal";
    import SearchMobileModal from "./../components/modals/SearchMobileModal.vue";

    import { LangDataHandler } from "../ts/handlers/LangDataHandler";
    import langsData from "./locales/Header.json";

    import { searchText, searchQuery } from "../ts/handlers/SearchHandler";
    
    import { defineEmits } from 'vue';

    const langData = LangDataHandler.initLangDataHandler("Header", langsData).langData;

    const route = useRoute();
    const router = useRouter();

    const onSearchButton = () =>
    {
        if(isCurrentRouteName('editoriallyArticles') ||  isCurrentRouteName('editoriallyArticles') || isCurrentRouteName('editoriallyApprovedArticles') || isCurrentRouteName('abyssArticles') || isCurrentRouteName('articlesWaitingApproval') || isCurrentRouteName('articlesWaitingApproval'))
        {   
            console.log("TEST");
            console.log(searchQuery.value);
            searchQuery.value = true;
        }
        else
        {
            if(searchText.value.length > 0)
            {
                searchQuery.value = true;
                router.push('/articles/search/'+searchText.value);
            }
        }
    }

    const isCurrentRouteName = (routeName: string) => 
    {
        return routeName == route.name ? true : false;
    }

    const emit = defineEmits();
</script>

<template>
    <header class="header lock_padding">
        <a class="header__logo" href="#/">
            <img src="../assets/img/logo/logo.png" alt="LOGO" class="header__logo__icon">
        </a>
        <div class="header__bar">
            <div class="header__bar__search">
                <input v-model="searchText" :placeholder="(langData['search'] as string)" type="text" class="header__bar__search__input">
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
        <div class="header__burger header-burger" @click="emit('toggleBurger')">
            <div class="header__burger__lines header-burger"></div>
        </div>
    </header>
</template>