<script setup lang="ts">
    import { ref } from "vue";

    import { LangDataHandler } from "./../../ts/LangDataHandler";
    import langsData from "./locales/ShareWith.json";
    
    const props = defineProps(["link"]);

    const langData = LangDataHandler.initLangDataHandler("ShareWith", langsData).langData;

    const copyLinkButton = () =>
    {
        navigator.clipboard.writeText(props.link);
    }
</script>

<template>
    <div class="form">
        <p class="form__title">{{ langData['shareWithTitle']}}</p>
       <div class="form__block">
        <a @click="copyLinkButton()" class="form__block__subblock">
            <div class="form__block__subblock__img">
                <img src="./../../assets/img/modals/share/copyLink.png" alt="copyLink">
            </div>
            <div class="form__block__subblock__title">
                <p class="form__block__subblock__title__text">{{ langData['copyLinkTitle'] }}</p>
            </div>
            
        </a>
        <a :href="'https://t.me/share/url?url='+link" target="_blank" class="form__block__subblock">
            <div class="form__block__subblock__img">
                <img src="./../../assets/img/modals/share/telegramLogo.png" alt="">
            </div>
            <div class="form__block__subblock__title">
                <p class="form__block__subblock__title__text">{{ langData['telegramTitle'] }}</p>
            </div>
        </a>
        <a :href="'https://www.facebook.com/sharer/sharer.php?u='+link" target="_blank" class="form__block__subblock">
            <div class="form__block__subblock__img">
                <img src="./../../assets/img/modals/share/facebookLogo.png" alt="">
            </div>
            <div class="form__block__subblock__title">
                <p class="form__block__subblock__title__text">{{ langData['facebookTitle'] }}</p>
            </div>
        </a>
       </div>
    </div>
</template>

<style lang="scss" scoped src="./scss/ShareWith.scss"></style>