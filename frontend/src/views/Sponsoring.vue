<script setup lang="ts">
	import { ComputedRef } from 'vue';

	import { LangDataHandler } from "../ts/handlers/LangDataHandler";
	import langsData from "./locales/Sponsoring.json";

	import { JsonData } from "../ts/interfaces/JsonData";

	import { openModal } from "jenesius-vue-modal";
	import SponsoringCryptoModal from "./../components/modals/SponsoringCryptoModal.vue";

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("Sponsoring", langsData).langData;
</script>

<template>
	<main class="main">
		<h1 class="main__title">{{ langData['title'] }}</h1>
		<h1 class="main__titleBlock">{{ langData['titleCrypto'] }}</h1>
		<div class="main__blockgrid">
			<div class="main__blockgrid__block" @click="openModal(SponsoringCryptoModal, { crypto: 'monero'})">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/sponsoring/crypto/XMR.svg" alt="XMR">
				</div>
				<p class="main__blockgrid__block__title">Monero</p>
			</div>
		</div>
	</main>
</template>

<style lang="scss" scoped src="./scss/Sponsoring.scss"></style>