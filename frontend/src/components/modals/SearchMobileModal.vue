<script setup lang="ts">
    import { ref, Ref } from "vue";
    import { useRoute, useRouter } from 'vue-router';

    import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
    import langsData from "./locales/SearchMobileModal.json";
    
    import { searchQuery, searchText } from "../../ts/handlers/SearchHandler";
    
    const langData = LangDataHandler.initLangDataHandler("SearchMobileModal", langsData).langData;

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
</script>

<template>
    <div class="form">
        <div class="form__search">
            <input v-model="searchText" :placeholder="(langData['searchBarPlaceholder'] as string)" type="text" class="form__search__input">
            <a @click="onSearchButton" class="form__search__button"></a>
        </div>
    </div>
</template>

<style lang="scss" scoped src="./scss/SearchMobileModal.scss"></style>