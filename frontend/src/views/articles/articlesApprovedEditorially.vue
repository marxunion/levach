<script setup lang="ts">
    import { ref } from 'vue';
	
    import { LangDataHandler } from "./../../ts/LangDataHandler";
	import langsData from "./locales/articlesApprovedEditorially.json";

	const langData = LangDataHandler.initLangDataHandler("articlesApprovedEditorially", langsData).langData;
</script>

<template>
    <main class="main">
		<article class="main__article">

        </article>
    </main>
</template>

<style lang="scss" scoped src="./scss/articlesApprovedEditorially.scss"></style>