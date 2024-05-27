<script setup lang="ts">
    import { ref, ComputedRef } from "vue";

    import { JsonData } from "../../ts/interfaces/JsonData";

    import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
    import langsData from "./locales/ShareWith.json";
    
    const props = defineProps(["link", "text"]);

    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("ShareWith", langsData).langData;

    const copyLinkButton = () =>
    {
        navigator.clipboard.writeText(props.link);
    }

    const telegramLink = ref('https://t.me/share/url?url='+encodeURIComponent(props.link));

    if(props.text)
    {
        telegramLink.value = 'https://t.me/share/url?url='+encodeURIComponent(props.link)+'&text='+encodeURIComponent(props.text);
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
        <a :href="telegramLink" target="_blank" class="form__block__subblock">
            <div class="form__block__subblock__img">
                <img src="./../../assets/img/modals/share/telegramLogo.png" alt="">
            </div>
            <div class="form__block__subblock__title">
                <p class="form__block__subblock__title__text">{{ langData['telegramTitle'] }}</p>
            </div>
        </a>
        <a :href="'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(link)" target="_blank" class="form__block__subblock">
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