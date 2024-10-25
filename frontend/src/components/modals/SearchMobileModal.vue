<script setup lang="ts">
    import { ComputedRef, onUnmounted } from "vue";
    import { useRoute, useRouter, RouteLocationNormalizedLoaded, Router } from 'vue-router';

    import { JsonData } from "../../ts/interfaces/JsonData";

    import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
    import langsData from "./locales/SearchMobileModal.json";
    
    import { searchQuery, searchText } from "../../ts/handlers/SearchHandler";
    
    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("SearchMobileModal", langsData).langData;

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
                router.push('/articles/search/'+ (searchText.value));
            }
        }
    }

    const isCurrentRouteName = (routeName: string) => 
    {
        return routeName == route.name ? true : false;
    }

    onUnmounted(() =>
	{
		LangDataHandler.destroyLangDataHandler('SearchMobileModal');
	});
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