<script setup lang="ts">
    import { ref, Ref, ComputedRef, defineEmits } from "vue";

    import { VueRecaptcha } from 'vue-recaptcha';

    import { JsonData } from "../ts/interfaces/JsonData";

    import { LangDataHandler } from "../ts/handlers/LangDataHandler";
    import langsData from "./modals/locales/InfoModal.json";
    
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
    
    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("InfoModal", langsData).langData;
</script>

<template>
    <div class="captcha">
        <p class="captcha__text">
            This site is protected by reCAPTCHA and the Google
            <a class="captcha__text__link" target="_blank" href="https://policies.google.com/privacy">Privacy Policy</a> and
            <a class="captcha__text__link" target="_blank" href="https://policies.google.com/terms">Terms of Service</a> apply.
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