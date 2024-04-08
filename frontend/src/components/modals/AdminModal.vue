<script setup lang="ts">
    import { ref } from "vue";

    import { LangDataHandler } from "./../../ts/LangDataHandler";
    import langsData from "./locales/AdminModal.json";
    
    const langData = ref(LangDataHandler.initLangDataHandler("AdminModal", langsData).langData);
</script>

<template>
    <div class="form">
        <p class="form__title">{{ langData["formTitle"] }}</p>
        <div class="form__fields">
            <div class="form__fields__field">
                <p class="form__fields__field__title">{{ langData["formTitleLogin"] }}</p>
                <input :placeholder="(langData['userLoginPlaceholder'] as string)" class="form__fields__field__input" type="text">
            </div>
            <div class="form__fields__field">
                <p class="form__fields__field__title">{{ langData["formTitlePassword"] }}</p>
                <input :placeholder="(langData['userPasswordPlaceholder'] as string)" class="form__fields__field__input" type="password">
            </div>
        </div>
        <button class="form__button">{{ langData["formButton"] }}</button>
    </div>
</template>

<style lang="scss" scoped src="./scss/AdminModal.scss"></style>