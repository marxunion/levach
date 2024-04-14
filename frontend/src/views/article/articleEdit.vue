<script setup lang="ts">
	import { ref, watch, reactive, Ref, computed, onMounted } from 'vue';
	import { useRoute } from 'vue-router';
	import axios from 'axios';

	import DropDown from "./../../components/DropDown.vue";

	import { MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { JsonData } from './../../ts/JsonHandler';

	import { closeModal, openModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
	import InfoModal from "./../../components/modals/InfoModal.vue";
    import InfoModalWithLink from "./../../components/modals/InfoModalWithLink.vue";

	import { abbreviateNumber } from './../../ts/AbbreviateNumberHelper';

	import langsData from "./locales/articleEdit.json";

	import { LangDataHandler } from "./../../ts/LangDataHandler";

	import { StringWithEnds } from "./../../ts/StringWithEnds";

	import './../../libs/font_2605852_prouiefeic';

	interface Statistic 
	{
		count: number;
		title: StringWithEnds;
	}

	interface Statistics
	{
		[statisticName: string]: Statistic;
	}

	const langData = LangDataHandler.initLangDataHandler("articleEdit", langsData).langData;


	const route = useRoute();
	const articleEditCode = ref<string | null>(null);

	onMounted(() => {
		articleEditCode.value = route.params.articleEditCode as string;
	});
	
	async function fetchData()
	{
		return await axios.get('/api/article/edit/preload',  {params: {'editCode': articleEditCode}})
		.then(response =>
		{
			if(response.data.title)
			{
				return response.data;
			}
			else
			{
				if(response.data.Warning)
				{
					openModal(InfoModal, (langData.value['warnings'] as JsonData)['unknown']);
					return null;
				}
				else if(response.data.Error)
				{
					if(response.data.Error.message == "Article for edit not found")
					{
						openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNotFound']})
						return null;
					}
					else
					{
						openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
						return null;
					}
				}
				else if(response.data.Critical)
				{
					openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
					return null;
				}
				else
				{
					openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
					return null;
				}
			}
		})
		.catch(response =>
		{
			if(response.data.Warning)
			{
				openModal(InfoModal, (langData.value['warnings'] as JsonData)['unknown']);
				return null;
			}
			else if(response.data.Error)
			{
				if(response.data.Error.message == "Article for edit not found")
				{
					openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['articleNotFound']})
					return null;
				}
				else
				{
					openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
					return null;
				}
			}
			else if(response.data.Critical)
			{
				openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
				return null;
			}
			else
			{
				openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
				return null;
			}
		});
	}

	let fetchedData = await fetchData();

	let statistics = null;
	let statuses = null;
	let statusesTexts = null;
	let editorState = null;
	if(fetchedData)
	{
		statistics = computed(() => 
		{
			const statisticsTemp : Statistics = {};

			(langData.value['statistics'] as JsonData[]).forEach((statistic: JsonData) => 
			{
				statisticsTemp[statistic['statisticName'] as string] = 
				{
					count: 5,
					title: new StringWithEnds(((statistic['data'] as JsonData)["titleWithEnds"]) as JsonData)
				};
			});
			return statisticsTemp;
		});
		
		// Statuses
		 statuses = reactive({
			premoderationStatus: 0,
			acceptedEditoriallyStatus: 0
		})

		 statusesTexts = computed(() => 
			({
				premoderationStatus: ((langData.value['statuses'] as JsonData)['premoderationStatus'] as JsonData)[statuses.premoderationStatus.toString()],
				acceptedEditoriallyStatus: ((langData.value['statuses'] as JsonData)['acceptedEditoriallyStatus'] as JsonData)[statuses.acceptedEditoriallyStatus.toString()]
			})
		);

		editorState = reactive(
		{
			text: fetchedData,
			language: LangDataHandler.currentLanguage.value
		});
	}
	
	// Tags
	let newTag = ref('');
	let tags : Ref<string[]> = ref([]);



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

						console.log(form);

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
	

	const onSendButton = () =>
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
							axios.post('/api/article/edit/'+articleEditCode, {"text": editorState.text, "tags": tags.value})
							.then(response => 
							{
								if(response.data.editLink)
								{
									openModal(InfoModalWithLink, {status: true, text: "", link: window.location.hostname + "/article/edit/" + response.data.editLink, text2: (langData.value['warnings'] as JsonData)['articleEditLinkCopyWarning']})
								}
								else
								{
									if(response.data.Warning)
									{
										if(response.data.Warning.message == "Article for edit not found")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNotFound']})
										}
										else if(response.data.Warning.message == "Please add a title for the article")
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
											openModal(InfoModal, (langData.value['warnings'] as JsonData)['unknown']);
										}
									}
									else if(response.data.Error)
									{
										openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
									}
									else if(response.data.Critical)
									{
										openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
									}
									else
									{
										openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
									}
								}
							})
							.catch(response => 
							{
								if(response.data.Warning)
									{
										if(response.data.Warning.message == "Article for edit not found")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNotFound']})
										}
										else if(response.data.Warning.message == "Please add a title for the article")
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
											openModal(InfoModal, (langData.value['warnings'] as JsonData)['unknown']);
										}
									}
									else if(response.data.Error)
									{
										openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
									}
									else if(response.data.Critical)
									{
										openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
									}
									else
									{
										openModal(InfoModal, (langData.value['errors'] as JsonData)['unknown']);
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

	watch(langData, () =>
	{
		editorState.language = LangDataHandler.currentLanguage.value;
	});
</script>

<template>
	<main class="main">
		<article v-if="fetchedData" class="main__article">
			<div class="main__article__info">
				<div class="main__article__info__statistics">
					<div class="main__article__info__statistics__statistic" v-for="(status, index) in statistics" :key="index">
						<h1 class="main__article__info__statistics__statistic__counter">{{ abbreviateNumber(status.count) }}</h1>
						<p class="main__article__info__statistics__statistic__title">{{ status.title.getStringWithEnd(status.count) }}</p>
					</div>
				</div>
				<div class="main__article__info__statusesContainer">
					<div class="main__article__info__statusesContainer__statuses">
						<div class="main__article__info__statusesContainer__status">
							<p>{{ (langData['statuses'] as JsonData)['premoderationStatusText'] }}</p>
							<img :src="`/src/assets/img/article/statuses/${statuses.premoderationStatus}.svg`" alt="premoderationStatus">
							<p>{{ statusesTexts.premoderationStatus }}</p>
						</div>
						<div class="main__article__info__statusesContainer__status">
							<p>{{ (langData['statuses'] as JsonData)['acceptedEditoriallyStatusText'] }}</p>
							<img :src="`/src/assets/img/article/statuses/${statuses.acceptedEditoriallyStatus}.svg`" alt="acceptedEditoriallyStatus">
							<p>{{ statusesTexts.acceptedEditoriallyStatus }}</p>
						</div>
					</div>
				</div>
			</div>
			<div class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="(editorState.text as string)" @onUploadImg="onUploadImg" :language="editorState.language" :preview="false" noIconfont/>
				<button class="main__article__editorContainer__sendButton" @click="onSendButton">{{ langData['sendButton'] }}</button>	
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
		<article v-else class="main__article">
			<h1 class="main__article__title">{{ (langData['warnings'] as JsonData)['articleNotFound'] }}</h1>
		</article>
	</main>
</template>

<style lang="scss" scoped src="./scss/articleEdit.scss"></style>