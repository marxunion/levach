<script setup lang="ts">
	import { ref, watch, reactive, Ref } from 'vue';
	import axios from 'axios';

	import { MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { LangDataHandler } from "./../../ts/LangDataHandler";
	import langsData from "./locales/articleNew.json";

	const langData = LangDataHandler.initLangDataHandler("articleNew", langsData).langData;


	//Editor
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

	let editorState = reactive(
	{
		text: langData.value['editorDefaultText'],
		language: LangDataHandler.currentLanguage.value
	});

	watch(langData, () =>
	{
		//state.text = langData.value['editorDefaultText'];
		editorState.language = LangDataHandler.currentLanguage.value;
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
						headers: 
						{
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


	//Tags
	const newTag = ref('');
	const tags : Ref<string[]>= ref([]);

	const addTag = () => 
	{
		if (newTag.value.length > 0 && newTag.value.length < 40 && !tags.value.includes(newTag.value.trim())) 
		{
			tags.value.push(newTag.value.trim());
			newTag.value = '';
		}
	};
	const removeTag = (index: number) => 
	{
		tags.value.splice(index, 1);
	};
</script>

<template>
	<main class="main">
		<article class="main__article">
			<div class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="(editorState.text as string)" @onUploadImg="onUploadImg" :language="editorState.language"/>
				<button class="main__article__editorContainer__sendButton">{{ langData['sendButton'] }}</button>	
			</div>	
			<div class="main__article__editTags">
				<div class="main__article__editTags__tags__tag" v-for="(tag, index) in tags" :key="index">
					<p class="main__article__editTags__tags__tag__title">{{ tag }}</p>
					<button class="main__article__editTags__tags__tag__button" @click="removeTag(index)"><p>+</p></button>
				</div>
				<div class="main__article__editTags__addTag">
					<input v-model="newTag" class="main__article__editTags__addTag__input" type="text" :placeholder="(langData['addTagPlaceholder'] as string)">
					<button @click="addTag" class="main__article__editTags__addTag__button">+</button>
				</div>
			</div>
		</article>
	</main>
</template>

<style lang="scss" scoped src="./scss/articleNew.scss"></style>