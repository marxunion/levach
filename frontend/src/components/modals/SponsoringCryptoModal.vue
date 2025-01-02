<script setup lang="ts">
    import { ref, Ref, ComputedRef, onMounted, onUnmounted } from "vue";

    import axios from 'axios';

    import { JsonData } from "../../ts/interfaces/JsonData";

    import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
    import langsData from "./locales/SponsoringCryptoModal.json";

    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("SponsoringCryptoModal", langsData).langData;
    
    const props = defineProps(
    {
        crypto: 
        {
            type: String,
            default: '',
        },
    });

    const walletLink : Ref<string> = ref('');
    const inputWallet : Ref<HTMLInputElement | null> = ref(null);

    const copyToClipboard = () => 
    {
        navigator.clipboard.writeText(walletLink.value);
        if(inputWallet.value !== null)
        {
            inputWallet.value.select();
        }
        
    }

    onMounted(() => 
    {
        axios.get('api/sponsoring')
        .then(response => 
        {
            if(response.data[props.crypto])
            {
                walletLink.value = response.data[props.crypto];
            }
            else
            {
                walletLink.value = '';
            }
        });

        if(inputWallet.value !== null)
        {
            inputWallet.value.select();
        }
    });

    onUnmounted(() =>
	{
		LangDataHandler.destroyLangDataHandler('SponsoringCryptoModal');
	});
</script>

<template>
    <div class="form">
        <p class="form__text">{{ langData['formTitleText'] }}</p>
        <img class="form__qrcode" :src="'/api/media/img/'+crypto+'.png'" alt="QR Code">
        <div class="form__link">
            <input v-model="walletLink" ref="inputWallet" type="text" class="form__link__input" readonly></input>
            <button class="form__link__copyButton" @click="copyToClipboard">
                <img src="./../../assets/img/modals/CopyButton.svg" alt="Copy" class="form__link__copyButton__icon">
            </button>
        </div>
    </div>
</template>

<style lang="scss" scoped src="./scss/SponsoringCryptoModal.scss"></style>