<script setup lang="ts">
    import { ref } from "vue";

    import { LangDataHandler } from "./../../ts/LangDataHandler";
    import langsData from "./locales/InfoModal.json";
    
    defineProps(["status", "text"]);

    const langData = LangDataHandler.initLangDataHandler("InfoModal", langsData).langData;
</script>

<template>
    <div class="form">
        <p class="form__title">{{ status ? langData['success'] : langData['failure']}}</p>
        <p class="form__text">{{ text }}</p>
    </div>
</template>

<style lang="scss" scoped src="./scss/InfoModal.scss"></style>