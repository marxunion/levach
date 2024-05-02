<script setup lang="ts">
    import { ref, Ref } from "vue";
    import { useRouter } from 'vue-router';

    import { LangDataHandler } from "./../../ts/LangDataHandler";
    import langsData from "./locales/SearchMobileModal.json";
    
    import { searchText } from "../../ts/searchHelper";
    
    const langData = LangDataHandler.initLangDataHandler("SearchMobileModal", langsData).langData;

    const router = useRouter();

    const onSearchButton = () =>
    {
        if(searchText.value.length > 0)
        {
            router.push('/articles/search/'+searchText.value);
        }
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