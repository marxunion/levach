<script setup lang="ts">
    import { ref } from "vue";

    import { LangDataHandler } from "./../../ts/LangDataHandler";
    import langsData from "./locales/SearchMobileModal.json";
    
    const langData = ref(LangDataHandler.initLangDataHandler("SearchMobileModal", langsData).langData);
</script>

<template>
    <div class="form">
        <div class="form__search">
            <input :placeholder="(langData['searchBarPlaceholder'] as string)" type="text" class="form__search__input">
            <a href="#/" class="form__search__button"></a>
        </div>
    </div>
</template>

<style lang="scss" scoped src="./scss/SearchMobileModal.scss"></style>