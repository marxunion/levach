<script setup lang="ts">
	import { ref, watch, reactive, Ref, ComputedRef, computed, onMounted } from 'vue';
	import { useRoute } from 'vue-router';
	import axios from 'axios';

	//import DropDown from "./../../components/DropDown.vue";

	import { MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { JsonData } from './../../ts/JsonHandler';

	import Loader from "./../../components/Loader.vue";

	import { closeModal, openModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
	import InfoModal from "./../../components/modals/InfoModal.vue";
    import InfoModalWithLink from "./../../components/modals/InfoModalWithLink.vue";

	import { abbreviateNumber } from './../../ts/AbbreviateNumberHelper';

	import langsData from "./locales/articleEdit.json";

	import { LangDataHandler } from "./../../ts/LangDataHandler";

	import { StringWithEnds } from "./../../ts/StringWithEnds";

	import './../../libs/font_2605852_prouiefeic';

	import { timestampToLocaleFormatedTime } from '../../ts/DateTimeHelper';

	import { csrfTokenInput, getNewCsrfToken } from '../../ts/csrfTokenHelper';
	
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

	articleEditCode.value = route.params.articleEditCode as string;
	
	async function fetchData()
	{
		return await axios.get('/api/article/edit/preload/'+articleEditCode.value)
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
					return null;
				}
				else if(response.data.Error)
				{
					if(response.data.Error.message == "Article for edit not found")
					{
						return null;
					}
					else
					{
						return null;
					}
				}
				else if(response.data.Critical)
				{
					return null;
				}
				else
				{
					return null;
				}
			}
		})
		.catch(error =>
		{
			if(error.response.data.Warning)
			{
				return null;
			}
			else if(error.response.data.Error)
			{
				if(error.response.data.Error.message == "Article for edit not found")
				{
					return null;
				}
				else
				{
					return null;
				}
			}
			else if(error.response.data.Critical)
			{
				return null;
			}
			else
			{
				return null;
			}
		});
	}

	let fetchedData = ref();
	let loaded = ref(false);
	
	let statistics : ComputedRef<Statistics> = computed(() => ({
		rating: {
			count: 0,
			title: 'rating'
		},
		comments: {
			count: 0,
			title: 'comments'
		}
	}) as unknown as Statistics);

	let statuses = reactive({
		premoderationStatus: 0,
		acceptedEditoriallyStatus: 0
	});
	let statusesTexts = computed(() => 
		({
			premoderationStatus: ((langData.value['statuses'] as JsonData)['premoderationStatus'] as JsonData)[statuses.premoderationStatus.toString()],
			acceptedEditoriallyStatus: ((langData.value['statuses'] as JsonData)['acceptedEditoriallyStatus'] as JsonData)[statuses.acceptedEditoriallyStatus.toString()]
		})
	);

	let editorState = {
		text: '',
		language: ''
	};

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

						axios.post('/api/media/img/upload', form, 
						{
							headers: 
							{
								'Content-Type': 'multipart/form-data'
							}
						})
						.then((response) => 
						{
							if (response.data) 
							{
								if(response.data.fileName)
								{
									resolve(response);
								}
								else 
								{
									openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["unknown"]});
								}
							}
							else
							{
								openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
							}
						})
						.catch((error) => 
						{
							if (error.response.data) 
							{
								if(error.response.data.Warning)
								{
									if(error.response.data.Warning.message == "UploadImage Invalid image type")
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]});
									}
									else if(error.response.data.Warning.message == "UploadImage File size exceeds the maximum allowable file size")
									{
										openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)["imageMaxSize"] as string).replace('{size}', error.response.data.Warning.params.max_upload_filesize_mb)});
									}
									else if(error.response.data.Warning.message == "UploadImage Invalid image type")
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageUnallowedType"]});
									}
									else
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["unknown"]})
									}
								}
								else if(error.response.data.Error)
								{
									openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
								}
								else if(error.response.data.Critical)
								{
									openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
								}
								else 
								{
									openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
								}
							}
							else
							{
								openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
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

	const onSendButton = async () =>
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
							await getNewCsrfToken();

							if(csrfTokenInput.value == null)
							{
								openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
								return;
							}

							const data = 
							{
								csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
								text: editorState.text, 
								tags: tags.value
							};

							await axios.post('/api/article/edit/'+articleEditCode.value, data)
							.then(response => 
							{
								if(response.data.success)
								{
									openModal(InfoModalWithLink, {status: true, text: langData.value['articleEditedSuccessfully'], link: window.location.hostname + "/article/edit/" + articleEditCode.value, text2: (langData.value['warnings'] as JsonData)['articleEditLinkCopyWarning']});
								}
								else
								{
									if(response.data.Warning)
									{
										if(response.data.Warning.message == "Please add a title for the article")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle']});
										}
										else if(response.data.Warning.message == 'Article has duplicated tags')
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleDuplicatedTags']});
										}
										else if(response.data.Warning.message == "Title must contain between 5 and 120 characters")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols']});
										}
										else if(response.data.Warning.message == "Please add content for the article")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent']});
										}
										else if(response.data.Warning.message == "Article content must contain between 25 and 10000 characters")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols']});
										}
										else
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
										}
									}
									else if(response.data.Error)
									{
										if(response.data.Error.message == "Article for edit not found")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNotFound']});
										}
										else if(response.data.Error.message == "Please make changes for edit")
										{
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedChanges']});
										}
										else
										{
											openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
										}
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
										else if(error.response.data.Warning.message == "Wait for a timeout to re-edit the article")
										{
											openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)['editTimeoutToDate'] as string).replace('{date}', timestampToLocaleFormatedTime(error.response.data.Warning.params['edit_timeout_to_date']))})
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
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
										}
									}
									else if(error.response.data.Error)
									{
										if(error.response.data.Error.message == "Article for edit not found")
										{
											openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['articleNotFound']})
										}
										else if(error.response.data.Error.message == "Please make changes for edit")
										{
											openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['articleNeedChanges']});
										}
										else
										{
											openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
										}
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

	onMounted(async function()
	{
		try 
		{
			fetchedData.value = await fetchData();
			if(fetchedData.value != null)
			{
				statistics = computed(() => 
				{
					const statisticsTemp : Statistics = {};
					let statisticName;
					(langData.value['statistics'] as JsonData[]).forEach((statistic: JsonData) => 
					{
						statisticName = statistic['statisticName'] as string;
						statisticsTemp[statisticName] = 
						{
							count: fetchedData.value['statistics'][statisticName],
							title: new StringWithEnds(((statistic['data'] as JsonData)["titleWithEnds"]) as JsonData)
						};
					});
					return statisticsTemp;
				});
				
				statuses = reactive({
					premoderationStatus: fetchedData.value['premoderation_status'],
					acceptedEditoriallyStatus: fetchedData.value['approvededitorially_status']
				});

				statusesTexts = computed(() => 
					({
						premoderationStatus: ((langData.value['statuses'] as JsonData)['premoderationStatus'] as JsonData)[statuses.premoderationStatus.toString()],
						acceptedEditoriallyStatus: ((langData.value['statuses'] as JsonData)['acceptedEditoriallyStatus'] as JsonData)[statuses.acceptedEditoriallyStatus.toString()]
					})
				);

				editorState = reactive(
				{
					text: fetchedData.value['text'],
					language: LangDataHandler.currentLanguage.value
				});

				tags.value = fetchedData.value['tags'];
			}
			loaded.value = true;
		} 
		catch (error) 
		{
			loaded.value = true;
			fetchedData.value = null;
		}
	});
</script>

<template>
	<main v-if="loaded" class="main">
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
			<h1 class="main__article__title">{{ (langData['errors'] as JsonData)['articleForEditNotFound'] }}</h1>
		</article>
	</main>
	<main v-else class="main">
		<Loader/>
	</main>
</template>

<style lang="scss" scoped src="./scss/articleEdit.scss"></style>