<script setup lang="ts">
	import { ref, watch, reactive, Ref } from 'vue';
	import axios from 'axios';

	import { MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { openModal } from "jenesius-vue-modal";
    import InfoModal from "./../../components/modals/InfoModal.vue";
	import InfoModalWithLink from "./../../components/modals/InfoModalWithLink.vue";

	import { LangDataHandler } from "./../../ts/LangDataHandler";
	import langsData from "./locales/articleNew.json";

	import './../../libs/font_2605852_prouiefeic';

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

	const modalProps = {status: 1, text: "Это гибрид анонимного форума и интернет-журнала, предназначенный для анонимного общения в левом политическом дискурсе. Добро пожаловать.", link: "levach.com/article/edit/3238r94y9843ufggevb9yfd8v89df89v8d8989vdf67", text2: "Не забудьте сохранить ссылку, иначе ваша статья будет не доступна к редактированию"};
</script>

<template>
	<main class="main">
		<article class="main__article">
			<div class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="(editorState.text as string)" @onUploadImg="onUploadImg" :language="editorState.language" :preview="true" noIconfont/>
				<button class="main__article__editorContainer__sendButton" @click="openModal(InfoModalWithLink, modalProps)">{{ langData['sendButton'] }}</button>	
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