<script setup lang="ts">
    import { ref, onMounted } from "vue";

    import { LangDataHandler } from "./../../ts/LangDataHandler";
    import langsData from "./locales/InfoModalWithLink.json";
    
    const props = defineProps(["status", "text", "link", "text2"]);

    const langData = ref(LangDataHandler.initLangDataHandler("InfoModalWithLink", langsData).langData);

    const inputText = ref(props.link);
    const textInput = ref();

    const copyToClipboard = () => 
    {
        navigator.clipboard.writeText(inputText.value)
        textInput.value.select();
    }

    onMounted(() => 
    {
        textInput.value.select();
    });
;</script>

<template>
    <div class="form">
        <p class="form__title">{{ status ? langData['success'] : langData['failure']}}</p>
        <p class="form__text">{{ text }}</p>
        <div class="form__link">
            <input v-model="inputText" ref="textInput" type="text" class="form__link__input"></input>
            <button class="form__link__copyButton" @click="copyToClipboard">
                <img src="./../../assets/img/modals/CopyButton.svg" alt="Copy" class="form__link__copyButton__icon">
            </button>
        </div>
        
        <p class="form__text footer">{{ text2 }}</p>
    </div>
</template>

<style lang="scss" scoped src="./scss/InfoModalWithLink.scss"></style>