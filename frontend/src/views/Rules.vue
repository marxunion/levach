<script setup lang="ts">
	import { computed, ComputedRef, onUnmounted } from 'vue';

	import { JsonData } from '../ts/interfaces/JsonData';

	import { LangDataHandler } from '../ts/handlers/LangDataHandler';
	import langsData from './locales/Rules.json';

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler('Rules', langsData).langData;

	interface Section
	{
		title: string;
		description: string[];
	}

	const sectionData : ComputedRef<Section[]> = computed(() => 
	{
		const sections : JsonData[] = (langData.value as JsonData)['sections'] as JsonData[];
 		return sections.map((section: JsonData) => 
		{
			const title : string = section['title'] as string;
			const description : string[] = section['description'] as string[];
			return { title, description }
		});
	});

	onUnmounted(() =>
	{
		LangDataHandler.destroyLangDataHandler('Rules');
	});
</script>

<template>
	<main class="main">
		<h1 class="main__title">{{ langData['title'] }}</h1>
		<div v-for="(section, index) in sectionData" :key="index" class="main__block">
			<p class="main__block__title" :key="section.title">{{ section.title }}</p>
			<p v-for="(desc, descIndex) in section.description" :key="descIndex" class="main__block__description">{{ desc }}</p>
		</div>
		<div class="main__captcha">
			<p class="main__captcha__text">
				{{ langData['captchaText'] }}
				<a class="main__captcha__text__link" target="_blank" href="https://policies.google.com/privacy">{{ langData['captchaTextLinkPrivacyPolicy'] }}</a>{{ langData['captchaText1'] }}
				<a class="main__captcha__text__link" target="_blank" href="https://policies.google.com/terms">{{ langData['captchaTextLinkPrivacyTermsOfService'] }}</a>{{ langData['captchaText2'] }}
			</p>
		</div>
	</main>
</template>

<style lang="scss" scoped src="./scss/Rules.scss"></style>