<script setup lang="ts">
    import { ref, Ref, ComputedRef, defineEmits, onUnmounted } from "vue";

    import { VueRecaptcha } from 'vue-recaptcha';

    import { JsonData } from "../ts/interfaces/JsonData";

    import { LangDataHandler } from "../ts/handlers/LangDataHandler";
    import langsData from "./locales/Captcha.json";
    
    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("Captcha", langsData).langData;

    const emits = defineEmits(["onVerify", "onExpired", "onError"]);

    const recaptchaSitekey = ref('6LfaS9spAAAAAFo0_FElcn_sYvxjb3wlt8czk2E2');
    const recaptcha : Ref<VueRecaptcha | null> = ref(null);

    const onVerify = () => 
    {
        emits('onVerify');
    };

    const onExpired = () => 
    {
        emits('onExpired');
    };

    const onError = () => 
    {
        emits('onError');
    };

    const executeRecaptcha = () => 
    {
        const recaptchaInstance = recaptcha.value?.execute();
    };
    
    // 
    onUnmounted(() =>
    {
        const element = document.querySelector('[style="visibility: hidden; position: absolute; width: 100%; top: -10000px; left: 0px; right: 0px; transition: visibility 0s linear 0.3s, opacity 0.3s linear; opacity: 0;"]');

        if(element) 
        {
            const parent = element.parentElement;

            if(parent)
            {
                parent.removeChild(element);
            }
        }
    });
</script>

<template>
    <div class="captcha">
        <p class="captcha__text">
            {{ langData['captchaText'] }}
            <a class="captcha__text__link" target="_blank" href="https://policies.google.com/privacy">{{ langData['captchaTextLinkPrivacyPolicy'] }}</a>{{ langData['captchaText1'] }}
            <a class="captcha__text__link" target="_blank" href="https://policies.google.com/terms">{{ langData['captchaTextLinkPrivacyTermsOfService'] }}</a>{{ langData['captchaText2'] }}
        </p>
        
        <div class="captcha__handler">
            <vue-recaptcha
                ref="recaptcha"
                @verify="onVerify"
                @expired="onExpired"
                @error="onError"
                :sitekey="recaptchaSitekey"
                size="invisible">
            </vue-recaptcha>
        </div>
    </div>
</template>

<style lang="scss" scoped src="./scss/Captcha.scss"></style>