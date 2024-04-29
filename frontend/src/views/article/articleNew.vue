<script setup lang="ts">
	import { ref, watch, reactive, Ref } from 'vue';
	import { useRouter } from 'vue-router';
	import axios from 'axios';

	import { JsonData } from './../../ts/JsonHandler';

	import { MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { openModal, closeModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
    import InfoModal from "./../../components/modals/InfoModal.vue";
	import InfoModalWithLink from "./../../components/modals/InfoModalWithLink.vue";

	import { LangDataHandler } from "./../../ts/LangDataHandler";
	import langsData from "./locales/articleNew.json";

	import './../../libs/font_2605852_prouiefeic';

	import { csrfToken, getNewCsrfToken } from '../../ts/csrfTokenHelper';

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
		editorState.language = LangDataHandler.currentLanguage.value;
	});

	const onUploadImg = async (files: File[], callback: (urls: string[]) => void) => 
	{
		if(files.length > 0)
		{
			openModal(LoaderModal);
			const promises = files.map((file) => 
				{
					return new Promise<{ data: { fileName: string } }>((resolve, reject) => 
					{
						const form = new FormData();
						form.append('file', file);

						axios.post('/api/media/img/upload', form, 
						{
							headers: 
							{
								'Content-Type': 'multipart/form-data'
							}
						})
						.then((response) => 
						{
							let modalInfoProps;
							
							
							if (response.data) 
							{
								if(response.data.fileName)
								{
									resolve(response);
								}
								else 
								{
									modalInfoProps = {
										status: false, text: (langData.value['warnings'] as JsonData)["unknown"]
									}
									openModal(InfoModal, modalInfoProps);
									reject(new Error("UnknownError"));
								}
							}
							else
							{
								modalInfoProps = {
									status: false, text: (langData.value['errors'] as JsonData)["unknown"]
								}
								openModal(InfoModal, modalInfoProps);
								reject(new Error("UnknownError"))
							}
						})
						.catch((error) => 
						{
							let modalInfoProps;

							if (error.response.data) 
							{
								if(error.response.data.Warning)
								{
									if(error.response.data.Warning.message == "UploadImage Invalid image type")
									{
										modalInfoProps = {
											status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]
										}
									}
									else if(error.response.data.Warning.message == "UploadImage File size exceeds the maximum allowable file size")
									{
										modalInfoProps = {
											status: false, text: (langData.value['warnings'] as JsonData)["imageMaxSize"]
										}
									}
									else if(error.response.data.Warning.message == "UploadImage Invalid image type")
									{
										modalInfoProps = {
											status: false, text: (langData.value['warnings'] as JsonData)["imageUnallowedType"]
										}
									}
									else
									{
										modalInfoProps = {
											status: false, text: (langData.value['warnings'] as JsonData)["unknown"]
										}
									}
									openModal(InfoModal, modalInfoProps);
								}
								else if(error.response.data.Error)
								{
									modalInfoProps = {
										status: false, text: (langData.value['errors'] as JsonData)["unknown"]
									}
									openModal(InfoModal, modalInfoProps);
									reject(new Error("UnknownError"));
								}
								else if(error.response.data.Critical)
								{
									modalInfoProps = {
										status: false, text: (langData.value['errors'] as JsonData)["unknown"]
									}
									openModal(InfoModal, modalInfoProps);
									reject(new Error("UnknownError"));
								}
								else 
								{
									modalInfoProps = {
										status: false, text: (langData.value['errors'] as JsonData)["unknown"]
									}
									openModal(InfoModal, modalInfoProps);
									reject(new Error("UnknownError"));
								}
							}
							else
							{
								modalInfoProps = {
									status: false, text: (langData.value['errors'] as JsonData)["unknown"]
								}
								openModal(InfoModal, modalInfoProps);
								reject(new Error("UnknownError"))
							}
						});
					});
				}
			);

			const res = await Promise.all(promises);
			
			const successfulResults = res.filter(item => item !== null);

			closeModal();
			callback(successfulResults.map((item) => '/api/media/img/'+item.data.fileName));
		}
	};

	//Tags
	const newTag = ref('');
	const tags : Ref<string[]>= ref([]);

	const addTag = () => 
	{
		if(!tags.value.includes(newTag.value.trim()))
		{
			if (newTag.value.length >= 1 && newTag.value.length <= 40)
			{
				tags.value.push(newTag.value.trim());
				newTag.value = '';
			}
			else
			{
				openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['tagSymbols']});
			}
		}
		else
		{
			openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['tagAlreadyExist']});
		}
	};
	const removeTag = (index: number) => 
	{
		tags.value.splice(index, 1);
	};

	const router = useRouter();
	const onSendButton = function ()
	{
		const contentParts = (editorState.text as string).split('\n');

		if(contentParts.length >= 1) 
		{
			const title = contentParts[0];
			if(title.substring(0, 2) == '# ') 
			{
				if(title.length >= 5 && title.length <= 120) 
				{
					if(contentParts.length >= 2) 
					{
						const content = contentParts.slice(1).join('\n');
						if(content.length >= 25 && content.length <= 10000) 
						{
							getNewCsrfToken();

							const data = {
								'csrfToken': csrfToken.value,
								"text": editorState.text,
								"tags": tags.value
							}

							axios.post('/api/article/new', data)
							.then(async response => 
							{
								if(response.data.editCode)
								{
									const modal = await openModal(InfoModalWithLink, {status: true, text: langData.value['articleCreatedSuccessfully'], link: "https://"+window.location.hostname + "/#/article/edit/" + response.data.editCode, text2: (langData.value['warnings'] as JsonData)['articleEditCodeCopy']})
									
									modal.onclose = function(event)
									{
										router.push("/article/edit/" + response.data.editCode);
									};
								}
								else
								{
									if(response.data.Warning)
									{
										if(response.data.Warning.message == "Please add a title for the article")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle']})
										}
										else if(response.data.Warning.message == "Title must contain between 5 and 120 characters")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols']})
										}
										else if(response.data.Warning.message == "Please add content for the article")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent']})
										}
										else if(response.data.Warning.message == "Article content must contain between 25 and 10000 characters")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols']})
										}
										else
										{
											openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
										}
									}
									else if(response.data.Error)
									{
										openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
									}
									else if(response.data.Critical)
									{
										openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
									}
									else
									{
										openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
									}
								}
							})
							.catch(error => 
							{
								if(error.response.data.Warning)
								{
									if(error.response.data.Warning.message == "Please add a title for the article")
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle']})
									}
									else if(error.response.data.Warning.message == "Title must contain between 5 and 120 characters")
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols']})
									}
									else if(error.response.data.Warning.message == "Please add content for the article")
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent']})
									}
									else if(error.response.data.Warning.message == "Article content must contain between 25 and 10000 characters")
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols']})
									}
									else
									{
										openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
									}
								}
								else if(error.response.data.Error)
								{
									openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
								}
								else if(error.response.data.Critical)
								{
									openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
								}
								else
								{
									openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
								}
							});
						}
						else
						{
							openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols']})
						}
					}
					else
					{
						openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent']})
					}
					
				}
				else
				{
					openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols']})
				}
			}
			else
			{
				openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle']})
			}
		}
		else
		{
			openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle']})
		}	
	}
</script>

<template>
	<main class="main">
		<article class="main__article">
			<div class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="(editorState.text as string)" @onUploadImg="onUploadImg" :language="editorState.language" :preview="true" noIconfont/>
				<button class="main__article__editorContainer__sendButton" @click="onSendButton()">{{ langData['sendButton'] }}</button>	
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