<script setup lang="ts">
	import { ref, reactive, Ref, ComputedRef, computed, onMounted } from 'vue';
	import { useRoute } from 'vue-router';
	import axios from 'axios';

	import { MdEditor, MdPreview, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { JsonData } from '../../ts/helpers/JsonHelper';

	import Loader from "./../../components/Loader.vue";

	import { closeModal, openModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
	import InfoModal from "./../../components/modals/InfoModal.vue";
    import InfoModalWithLink from "./../../components/modals/InfoModalWithLink.vue";

	import { abbreviateNumber } from '../../ts/helpers/numberHelper';

	import langsData from "./locales/ArticleEdit.json";

	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";

	import { StringWithEnds } from "../../ts/interfaces/StringWithEnds";

    import { adminStatus, adminStatusReCheck } from '../../ts/handlers/AdminHandler'

	import './../../libs/font_2605852_prouiefeic';

	import { timestampToLocaleFormatedTime } from '../../ts/helpers/DateTimeHelper';

	import { csrfTokenInput, getNewCsrfToken } from '../../ts/handlers/CSRFTokenHandler';
	
	interface Statistic 
	{
		count: number;
		title: StringWithEnds;
	}

	interface Statistics
	{
		[statisticName: string]: Statistic;
	}

	const langData = LangDataHandler.initLangDataHandler("ArticleEdit", langsData).langData;

	const route = useRoute();
	const articleEditCode = ref<string | null>(null);

	articleEditCode.value = route.params.articleEditCode as string;

	const viewLink = ref('');

	let fetchedData = ref();
	let loaded = ref(false);
	
	let statistics : ComputedRef<Statistics> = computed(() => 
	({
		rating: {
			count: 0,
			title: 'rating'
		},
		comments: {
			count: 0,
			title: 'comments'
		}
	}) as unknown as Statistics);

	let editorState = reactive({
		text: ''
	});

	let previewState = reactive({
		text: ''
	});

	let newTag = ref('');
	let tags : Ref<string[]> = ref([]);

	async function fetchData()
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
        }
		
		return await axios.post('/api/article/edit/preload/'+articleEditCode.value, data)
		.then(response =>
		{
			if(response.data.title)
			{
				fetchedData.value = response.data;
				statistics = computed(() => 
				{
					const statisticsTemp : Statistics = {}
					let statisticName;
					(langData.value['statistics'] as JsonData[]).forEach((statistic: JsonData) => 
					{
						statisticName = statistic['statisticName'] as string;
						statisticsTemp[statisticName] = 
						{
							count: fetchedData.value['statistics'][statisticName],
							title: new StringWithEnds(((statistic['data'] as JsonData)["titleWithEnds"]) as JsonData)
						}
					});
					return statisticsTemp;
				});

				if(fetchedData.value['tags'] == null)
				{
					fetchedData.value['tags'] = [];
				}
				tags.value = fetchedData.value['tags'];
				

				if(fetchedData.value['approvededitorially_status'] == 2)
				{
					previewState.text = fetchedData.value['text'];
				}
				else
				{
					editorState.text = fetchedData.value['text'];
				}
				
				

				viewLink.value = "localhost:8000/#/article/" + fetchedData.value['view_code'];

				loaded.value = true;
			}
			else
			{
				if(response.data.Warning)
				{
					loaded.value = true;
					fetchedData.value = null;
					return null;
				}
				else if(response.data.Error)
				{
					loaded.value = true;
					fetchedData.value = null;
					return null;
				}
				else if(response.data.Critical)
				{
					loaded.value = true;
					fetchedData.value = null;
					return null;
				}
				else
				{
					loaded.value = true;
					fetchedData.value = null;
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
				loaded.value = true;
				fetchedData.value = null;
				return null;
			}
			else if(error.response.data.Critical)
			{
				loaded.value = true;
				fetchedData.value = null;
				return null;
			}
			else
			{
				loaded.value = true;
				fetchedData.value = null;
				return null;
			}
		});
	}
	
	const textInput = ref();

    const copyToClipboard = () => 
    {
        navigator.clipboard.writeText(viewLink.value)
        textInput.value.select();
    }

	const onUploadImg = async (files: File[], callback: (urls: string[]) => void) => 
	{
		if(files.length > 0)
		{
			openModal(LoaderModal);
			const promises = files.map((file) => 
				{
					return new Promise<{ data: { fileName: string } }>(resolve => 
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
	}

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
	}
	const removeTag = (index: number) => 
	{
		tags.value.splice(index, 1);
	}

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
							}

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
											openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleForEditNotFound']});
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
										openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['articleForEditNotFound']})
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

	const onRequestApproveArticle = async () => 
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
        }

        await axios.post('/api/article/edit/requestApprove/' + articleEditCode.value, data)
        .then(async response =>
        {
            if(response.data.success)
            {
				fetchedData.value['canRequestApprove'] = false;
				fetchedData.value['approvededitorially_status'] = 1;
                openModal(InfoModal, {status: true, text: langData.value['articleRequestApproveSuccessfully']});
            }
            else
            {
                if(response.data.Warning)
                {
                    openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
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
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
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

	const onRejectApproveWithChanges = async () =>
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
			status: 0
		}

		await axios.post('/api/article/edit/approveWithChanges/' + articleEditCode.value, data)
		.then(async response =>
		{
			if(response.data.success)
            {
                openModal(InfoModal, {status: true, text: langData.value['articleRejectApproveWithChangesSuccessfully']});
				await fetchData();
            }
            else
            {
                if(response.data.Warning)
                {
                    openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
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
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
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

	const onAcceptApproveWithChanges = async () =>
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
			status: 1
		}

		await axios.post('/api/article/edit/approveWithChanges/' + articleEditCode.value, data)
		.then(async response =>
		{
			if(response.data.success)
            {
                openModal(InfoModal, {status: true, text: langData.value['articleAcceptApproveWithChangesSuccessfully']});
				await fetchData();
            }
            else
            {
                if(response.data.Warning)
                {
                    openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
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
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
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

	onMounted(async function()
	{
		adminStatusReCheck();
		await fetchData();
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
							<div>
								<img :src="`/src/assets/img/article/statuses/${fetchedData['premoderation_status']}.svg`" alt="premoderationStatus">
								<p>{{ ((langData['statuses'] as JsonData)['premoderationStatus'] as JsonData)[fetchedData['premoderation_status'].toString()] }}</p>
							</div>
						</div>
						<div class="main__article__info__statusesContainer__status">
							<p>{{ (langData['statuses'] as JsonData)['approvedEditoriallyStatusText'] }}</p>
							<div>
								<img :src="`/src/assets/img/article/statuses/${fetchedData['approvededitorially_status']}.svg`" alt="approvedEditoriallyStatus">
								<p>{{ ((langData['statuses'] as JsonData)['approvedEditoriallyStatus'] as JsonData)[fetchedData['approvededitorially_status'].toString()] }}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div v-if="viewLink != ''" class="main__article__block">
				<p class="main__article__block__title linkForViewArticle">{{ langData['linkForViewArticle'] }}</p>
				<div class="main__article__block__link">
					<input v-model="viewLink" ref="textInput" type="text" class="main__article__block__link__input"></input>
					<button class="main__article__block__link__copyButton" @click="copyToClipboard">
						<img src="./../../assets/img/modals/CopyButton.svg" alt="Copy" class="main__article__block__link__copyButton__icon">
					</button>
				</div>
			</div>
			<div v-if="fetchedData['canRequestApprove']" class="main__article__block">
				<p class="main__article__block__title">{{ langData['requestApproveTitle'] }}</p>
				<button @click="onRequestApproveArticle" class="main__article__block__button requestApproveArticleButton">{{ langData['requestApproveButtonTitle'] }}</button>
			</div>
			<div v-if="fetchedData['approvededitorially_status'] == 3" class="main__article__block">
				<p class="main__article__block__title">{{ langData['articleApprovedWithChangesTitle'] }}</p>
				<div class="main__article__block__subblock">
					<button @click="onAcceptApproveWithChanges" class="main__article__block__button acceptArticleApprovedWithChangesButton">{{ langData['acceptArticleApprovedWithChangesButtonTitle'] }}</button>
					<button @click="onRejectApproveWithChanges" class="main__article__block__button rejectArticleApprovedWithChangesButton">{{ langData['rejectArticleApprovedWithChangesButtonTitle'] }}</button>
				</div>
			</div>
			<div v-if="fetchedData['approvededitorially_status'] == 2 && fetchedData['editorially_status'] != 1 && !adminStatus" class="main__article__previewContainer">
				<MdPreview class="main__article__previewContainer__preview" :modelValue="editorState.text" :language="LangDataHandler.currentLanguage.value"/>
			</div>
			
			<div v-if="fetchedData['approvededitorially_status'] != 2 || fetchedData['editorially_status'] == 1 || adminStatus" class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="previewState.text" @onUploadImg="onUploadImg" :language="LangDataHandler.currentLanguage.value" :preview="false" noIconfont/>
				<button class="main__article__editorContainer__sendButton" @click="onSendButton">{{ langData['sendButton'] }}</button>	
			</div>

			<div v-if="fetchedData['approvededitorially_status'] != 2 || fetchedData['editorially_status'] == 1 || adminStatus" class="main__article__editTags">
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

<style lang="scss" scoped src="./scss/ArticleEdit.scss"></style>