<script setup lang="ts">
    import { ref, Ref, ComputedRef, onMounted, onUnmounted } from "vue";

    import { JsonData } from "../../ts/interfaces/JsonData";

    import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
    import langsData from "./locales/InfoModalWithLink.json";
    
    import { closeModal } from "jenesius-vue-modal"

    const props = defineProps(
    {
        status: 
        {
            type: Boolean,
            default: true,
        },
        text:
        {
            type: String,
            default: '',
        },
        textFooter: 
        {
            type: String,
            default: '',
        },
        link: 
        {
            type: String,
            default: '',
        }
    });

    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("InfoModalWithLink", langsData).langData;

    const inputText : Ref<string> = ref(props.link as string);
    const textInput : Ref<HTMLInputElement | null> = ref(null);

    const copyToClipboard = () => 
    {
        navigator.clipboard.writeText(inputText.value)
        if(textInput.value !== null)
        {
            textInput.value.select();
        }
        closeModal();
    }

    onMounted(() => 
    {
        if(textInput.value !== null)
        {
            textInput.value.select();
        }
    });

    onUnmounted(() =>
	{
		LangDataHandler.destroyLangDataHandler('InfoModalWithLink');
	});
</script>

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
        
        <p class="form__text footer">{{ textFooter }}</p>
    </div>
</template>

<style lang="scss" scoped src="./scss/InfoModalWithLink.scss"></style>