<script setup lang="ts">
    import { ComputedRef, onUnmounted } from "vue";

    import { JsonData } from "../../ts/interfaces/JsonData";

    import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
    import langsData from "./locales/InfoModal.json";


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
        }
    });

    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("InfoModal", langsData).langData;

    onUnmounted(() =>
	{
		LangDataHandler.destroyLangDataHandler('InfoModal');
	});
</script>

<template>
    <div class="form">
        <p class="form__title">{{ props.status ? langData['success'] : langData['failure'] }}</p>
        <p class="form__text">{{ props.text }}</p>
    </div>
</template>

<style lang="scss" scoped src="./scss/InfoModal.scss"></style>