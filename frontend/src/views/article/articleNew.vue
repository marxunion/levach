<script setup lang="ts">
import { ref, watch, reactive } from 'vue';
import axios from 'axios';

import { MdEditor, config } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';

import { LangDataHandler } from "./../../ts/LangDataHandler";
import langsData from "./locales/articleNew.json";

console.log(langsData);

const langData = ref(LangDataHandler.initLangDataHandler("articleNew", langsData).langData);


config(
{
  	editorConfig: 
  	{
		languageUserDefined: 
		{
			'RU': langsData['RU'],
			'EN': langsData['EN']
		}
	}
});

let state = reactive(
{
	text: '',
	language: LangDataHandler.currentLanguage.value
});

watch(langData, () =>
{
	state.language = LangDataHandler.currentLanguage.value;
});

const onUploadImg = async (files: File[], callback: (urls: string[]) => void) => 
{
	const res = await Promise.all(
		files.map((file) => 
		{
			return new Promise<{ data: { url: string } }>((rev, rej) => 
			{
				const form = new FormData();
				form.append('file', file);

				axios
				.post('/api/media/img/upload', form, 
				{
					headers: {
					'Content-Type': 'multipart/form-data'
					}
				})
				.then((response) => rev(response))
				.catch((error) => rej(error));
			});
		})
	);

	callback(res.map((item) => item.data.url));
};
const text = ref('');

</script>

<template>
	<MdEditor class="editor" v-model="text" @onUploadImg="onUploadImg" :language="state.language" />
</template>

<style lang="scss" scoped src="./scss/articleNew.scss"></style>