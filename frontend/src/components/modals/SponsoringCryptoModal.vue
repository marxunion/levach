<script setup lang="ts">
    import { ref, onMounted } from "vue";

    import axios from 'axios';

    import { LangDataHandler } from "./../../ts/LangDataHandler";
    import langsData from "./locales/SponsoringCryptoModal.json";

    const langData = ref(LangDataHandler.initLangDataHandler("SponsoringCryptoModal", langsData).langData);

    const props = defineProps(["crypto"]); 

    const walletLink = ref('');
    const inputWallet = ref();

    const copyToClipboard = () => 
    {
        navigator.clipboard.writeText(walletLink.value)
        inputWallet.value.select();
    }

    onMounted(() => 
    {
        axios.get('api/sponsoring')
        .then(response => 
        {
            console.log(response);
            
            if(response.data[props.crypto])
            {
                walletLink.value = response.data[props.crypto];
            }
            else
            {
                console.error('');
                walletLink.value = '';
            }
        });

        inputWallet.value.select();
    });
;</script>

<template>
    <div class="form">
        <p class="form__text">Monero Receive Address</p>
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