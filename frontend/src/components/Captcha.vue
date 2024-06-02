<script setup lang="ts">
    import { ref, Ref, /*ComputedRef,*/ defineEmits, onUnmounted } from "vue";

    import { VueRecaptcha } from 'vue-recaptcha';

    //import { JsonData } from "../ts/interfaces/JsonData";

    //import { LangDataHandler } from "../ts/handlers/LangDataHandler";
    //import langsData from "./locales/Captcha.json";
    
    //const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("Captcha", langsData).langData;

    import settings from '../configs/main.json';

    const emits = defineEmits(["onVerify", "onExpired", "onError"]);

    const recaptchaSitekey = ref(settings['recaptchaSiteKey']);
    const recaptcha : Ref<VueRecaptcha | null> = ref(null);

    const onVerify = (response : string) => 
    {
        emits('onVerify', response);
        reset();
    }

    const onError = () => 
    {
        emits('onError');
    }

    const reset = () => 
    {
        recaptcha.value?.reset();
    }

    const onExpired = () => 
    {
        reset();
        emits('onExpired');
    }

    const execute = () => 
    {
        recaptcha.value?.execute();
    }
    
    defineExpose({
        execute
    })

    onUnmounted(() =>
    {
        const elements : NodeListOf<Element> = document.querySelectorAll('[style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; z-index: 2000000000; background-color: rgb(255, 255, 255); opacity: 0.5;"]');

        elements.forEach(element => 
        {
            if(element) 
            {
                const parent : HTMLElement | null = element.parentElement;
                if(parent)
                {
                    const grandParent : HTMLElement | null = parent.parentElement;
                    if(grandParent)
                    {
                        grandParent.removeChild(parent);
                    }
                }
            }
        });
    });
</script>

<template>
    <div class="captcha">
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