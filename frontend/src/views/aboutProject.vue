<script setup lang="ts">
	import './scss/aboutProject.scss';
	import { computed } from 'vue';
	import { LangDataHandler, JsonData } from './../ts/LangDataHandler';
	import langsData from './locales/aboutProject.json';

	const langData = LangDataHandler.initLangDataHandler('aboutProject', langsData).langData;

	const sectionData = computed(() => {
		const sections = (langData.value as JsonData)['sections'] as JsonData[]
 		return sections.map((section: JsonData) => 
		{
			const title : string = section['title'] as string;
			const description : string = section['description'] as string;
			return { title, description };
		})
	});
</script>

<template>
	<main class="main">
		<h1 class="main__title">{{ langData['titleAboutProject'] }}</h1>
		<div class="main__block">
			<p class="main__block__description"><b>{{ langData['descriptionBoldAboutProject']  }}</b>{{ langData['descriptionAboutProject']  }}</p>
		</div>
	  	<h1 class="main__title titleFAQ">{{ langData['titleFAQ'] }}</h1>
		<div v-for="(section, index) in sectionData" :key="index" class="main__block">
		  	<p class="main__block__title" :key="section.title">{{ section.title }}</p>
			<p class="main__block__description" :key="section.description">{{ section.description }}</p>
		</div>
	</main>
</template>