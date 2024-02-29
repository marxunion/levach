<script setup lang="ts">
	import { LangDataHandler } from "./../ts/LangDataHandler";
	import langsData from "./locales/Sponsoring.json";

	const langData = LangDataHandler.initLangDataHandler("Sponsoring", langsData).langData;
</script>

<template>
	<main class="main">
		<h1 class="main__title">{{ langData['title'] }}</h1>
		<h1 class="main__titleBlock">{{ langData['titleCrypto'] }}</h1>
		<div class="main__blockgrid">
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/BTC.png" alt="BTC">
				</div>
				<p class="main__blockgrid__block__title">Bitcoin</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/XMR.png" alt="XMR">
				</div>
				<p class="main__blockgrid__block__title">Monero</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/ETH.png" alt="ETH" class="main__blockgrid__block__img">
				</div>
				<p class="main__blockgrid__block__title">Ethereum</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/ETC.png" alt="ETC" class="main__blockgrid__block__img">
				</div>
				<p class="main__blockgrid__block__title">Ethereum Classic</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/DASH.png" alt="DASH">
				</div>
				<p class="main__blockgrid__block__title">Dash</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/BCH.png" alt="BCH">
				</div>
				<p class="main__blockgrid__block__title">Bitcoin Cash</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/DOGE.png" alt="DOGE">
				</div>
				<p class="main__blockgrid__block__title">Dogecoin</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/LTC.png" alt="LTC">
				</div>
				<p class="main__blockgrid__block__title">Litecoin</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/BNB.png" alt="BNB">
				</div>
				<p class="main__blockgrid__block__title">BNB</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/TRX.png" alt="TRX">
				</div>
				<p class="main__blockgrid__block__title">Tron</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/crypto/USDT.png" alt="USDT">
				</div>
				<p class="main__blockgrid__block__title">USD Tether</p>
			</div>
		</div>
		<h1 class="main__titleBlock">{{ langData['titleCards'] }}</h1>
		<div class="main__blockgrid">
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/cards/visa.png" alt="BNB">
				</div>
				<p class="main__blockgrid__block__title">Visa</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/cards/mastercard.svg" alt="MasterCard">
				</div>
				<p class="main__blockgrid__block__title">MasterCard</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/cards/mir.svg" alt="Mir">
				</div>
				<p class="main__blockgrid__block__title">{{ langData['cardsMir'] }}</p>
			</div>
		</div>
		<h1 class="main__titleBlock">{{ langData['titleWallets'] }}</h1>
		<div class="main__blockgrid">
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/wallets/paypal.svg" alt="PayPal">
				</div>
				<p class="main__blockgrid__block__title">PayPal</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/wallets/payeer.svg" alt="Payeer">
				</div>
				<p class="main__blockgrid__block__title">Payeer</p>
			</div>
			<div class="main__blockgrid__block">
				<div class="main__blockgrid__block__img">
					<img src="../assets/img/wallets/yoomoney.svg" alt="YooMoney">
				</div>
				<p class="main__blockgrid__block__title">YooMoney</p>
			</div>
		</div>
	</main>
</template>

<style lang="scss" scoped src="./scss/Sponsoring.scss"></style>