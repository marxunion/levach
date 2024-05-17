<script setup lang="ts">
	import { ref, computed, watch, Ref, ComputedRef, onMounted, onBeforeUnmount } from 'vue';
	import { useRoute, RouteLocationNormalizedLoaded } from 'vue-router';
	import axios from 'axios';

	import { MdEditor, MdPreview, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { JsonData } from '../../ts/interfaces/JsonData';

	import Loader from "./../../components/Loader.vue";

	import { closeModal, openModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
	import InfoModal from "./../../components/modals/InfoModal.vue";
    import InfoModalWithLink from "./../../components/modals/InfoModalWithLink.vue";

	import Captcha from '../../components/Captcha.vue';

	import { abbreviateNumber } from '../../ts/helpers/NumberHelper';

	import langsData from "./locales/ArticleEdit.json";

	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";

	import { StringWithEnds } from "../../ts/interfaces/StringWithEnds";

    import { adminStatus, adminStatusReCheck } from '../../ts/handlers/AdminHandler'

	import './../../libs/font_2605852_prouiefeic';

	import { timestampToLocaleFormatedTime } from '../../ts/helpers/DateTimeHelper';

	import { csrfTokenInput, getNewCsrfToken } from '../../ts/handlers/CSRFTokenHandler';

	import { arraysAreEqual } from '../../ts/helpers/ArrayHelper';
	
	import { Article } from '../../ts/interfaces/Article';
	
	interface Statistic 
	{
		count: number;
		title: StringWithEnds;
	}

	interface Statistics
	{
		[statisticName: string]: Statistic;
	}

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("ArticleEdit", langsData).langData;

	const captcha : Ref<{ execute: () => void } | null> = ref(null);

	let captchaVerifyCallback : (token: string) => void;

	const route : RouteLocationNormalizedLoaded = useRoute();
	const articleEditCode : Ref<string | null> = ref(null);

	const currentChangesStatus : Ref<number> = ref(0);

	articleEditCode.value = route.params.articleEditCode as string;

	const viewLink : Ref<string> = ref('');

	let fetchedArticleData : Ref<Article | null> = ref(null);
	let loading : Ref<boolean> = ref(true);

	let reloading : Ref<boolean> = ref(false);
	
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

	let articleText : Ref<string> = ref('');

	let newTag = ref('');
	let tags : Ref<string[]> = ref([]);

	async function fetchArticleData()
	{
		await getNewCsrfToken();

        if(csrfTokenInput.value == null)
        {
            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            return;
        }

        const data = 
        {
            csrfToken: (csrfTokenInput.value as HTMLInputElement).value
        }
		
		await axios.post('/api/article/edit/preload/'+articleEditCode.value, data)
		.then(response =>
		{	
			if(response.data)
			{
				
				fetchedArticleData.value = response.data;
				if(fetchedArticleData.value)
				{
					statistics = computed(() => 
					{
						const statisticsTemp : Statistics = {}
						let statisticName : string;
						(langData.value['statistics'] as JsonData[]).forEach((statistic: JsonData) => 
						{
							statisticName = statistic['statisticName'] as string;
							if(fetchedArticleData.value)
							{
								if(fetchedArticleData.value.statistics[statisticName])
								{
									statisticsTemp[statisticName] = 
									{
										count: fetchedArticleData.value.statistics[statisticName],
										title: new StringWithEnds(((statistic['data'] as JsonData)["titleWithEnds"]) as JsonData)
									}
								}
								
							}
							
						});
						
						console.log(statisticsTemp);
						
						return statisticsTemp;
					});

					if(fetchedArticleData.value.statistics.current_tags == null)
					{
						fetchedArticleData.value.statistics.current_tags = [];
					}
					Object.assign(tags.value, fetchedArticleData.value.statistics.current_tags);

					articleText.value = fetchedArticleData.value.statistics.current_text;
					
					viewLink.value = "localhost:8000/#/article/" + fetchedArticleData.value.view_code;
				}
			}
			else
			{
				if(response.data.Warning)
				{
					fetchedArticleData.value = null;
				}
				else if(response.data.Error)
				{
					fetchedArticleData.value = null;
				}
				else if(response.data.Critical)
				{
					fetchedArticleData.value = null;
				}
				else
				{
					fetchedArticleData.value = null;
				}
			}
		})
		.catch(error =>
		{
			if(error.response.data.Warning)
			{
				fetchedArticleData.value = null;
			}
			else if(error.response.data.Error)
			{
				fetchedArticleData.value = null;
			}
			else if(error.response.data.Critical)
			{
				fetchedArticleData.value = null;
			}
			else
			{
				fetchedArticleData.value = null;
			}
		});
		loading.value = false;
		reloading.value = false;
	}

	async function refetchArticleData()
	{
		await getNewCsrfToken();

        if(csrfTokenInput.value == null)
        {
            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            return;
        }

        const data = 
        {
            csrfToken: (csrfTokenInput.value as HTMLInputElement).value
        }
		
		await axios.post('/api/article/edit/preload/'+articleEditCode.value, data)
		.then(response =>
		{
			if(response.data)
			{
				fetchedArticleData.value = response.data;
				if(fetchedArticleData.value)
				{
					statistics = computed(() => 
					{
						const statisticsTemp : Statistics = {}
						let statisticName;
						(langData.value['statistics'] as JsonData[]).forEach((statistic: JsonData) => 
						{
							if(fetchedArticleData.value)
							{
								statisticName = statistic['statisticName'] as string;
								statisticsTemp[statisticName] = 
								{
									count: fetchedArticleData.value.statistics[statisticName],
									title: new StringWithEnds(((statistic['data'] as JsonData)["titleWithEnds"]) as JsonData)
								}
							}
						});
						return statisticsTemp;
					});
					
					
					viewLink.value = "localhost:8000/#/article/" + fetchedArticleData.value.view_code;
					if(fetchedArticleData.value.statistics.approvededitorially_status > 0 && fetchedArticleData.value.statistics.editorially_status == 0 && !adminStatus.value)
					{
						Object.assign(tags.value, fetchedArticleData.value.statistics.current_tags);

						articleText.value = fetchedArticleData.value.statistics.current_text;
					}
				}
			}
		})
		loading.value = false;
		reloading.value = false;
	}

	const checkChanges = async () =>
    {
        await setTimeout(() => 
        {
            
        }, 1500);

		if(fetchedArticleData.value)
		{
			if(articleText.value != fetchedArticleData.value.statistics.current_text || !arraysAreEqual(tags.value, fetchedArticleData.value.statistics.current_tags))
			{
				currentChangesStatus.value = 1;
				return true;
			}
			else
			{
				currentChangesStatus.value = 0;
				return false;
			}
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
				checkChanges();
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
		checkChanges();
	}

	const viewLinkTextInput = ref();

    const copyToClipboard = () => 
    {
        navigator.clipboard.writeText(viewLink.value)
        viewLinkTextInput.value.select();
    }

	const onSendButtonRequest = async (captchaToken: string) => 
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
			captchaToken: captchaToken,
			text: articleText.value, 
			tags: tags.value
		}

		await axios.post('/api/article/edit/' + articleEditCode.value, data)
		.then(response => 
		{
			if (response.data.success) 
			{
				if(fetchedArticleData.value)
				{
					fetchedArticleData.value.statistics.current_text = articleText.value;
					Object.assign(fetchedArticleData.value.statistics.current_tags, tags.value);
					checkChanges();

					openModal(InfoModalWithLink, { status: true, text: langData.value['articleEditedSuccessfully'], link: window.location.hostname + "/article/edit/" + articleEditCode.value, text2: (langData.value['warnings'] as JsonData)['articleEditLinkCopyWarning'] });
				}
				
			} 
			else 
			{
				if (response.data.Warning) 
				{
					if (response.data.Warning.message == "Please add a title for the article") 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle'] });
					} 
					else if (response.data.Warning.message == 'Article has duplicated tags') {
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleDuplicatedTags'] });
					}
					else if (response.data.Warning.message == "Title must contain between 5 and 120 characters") 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols'] });
					} 
					else if (response.data.Warning.message == "Please add content for the article") 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent'] });
					} 
					else if (response.data.Warning.message == "Article content must contain between 25 and 10000 characters") 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols'] });
					} 
					else 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['unknown'] });
					}
				} 
				else if (response.data.Error)
				{
					if (response.data.Error.message == "Article for editing not found") 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleForEditNotFound'] });
					} 
					else if (response.data.Error.message == "Please make changes for edit") 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleNeedChanges'] });
					}
					else if(response.data.Error.message == "Invalid captcha solving")
					{
						openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["captcha"]});
					}
					else 
					{
						openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
					}
				}
				else if (response.data.Critical) 
				{
					openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
				} 
				else 
				{
					openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
				}
			}
		})
		.catch(error => 
		{
			if (error.response.data.Warning)
			 {
				if (error.response.data.Warning.message == "Please add a title for the article") 
				{
					openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle'] })
				} 
				else if (error.response.data.Warning.message == "Wait for a timeout to re-edit the article") 
				{
					openModal(InfoModal, { status: false, text: ((langData.value['warnings'] as JsonData)['editTimeoutToDate'] as string).replace('{date}', timestampToLocaleFormatedTime(error.response.data.Warning.params['edit_timeout_to_date'])) })
				} 
				else if (error.response.data.Warning.message == "Title must contain between 5 and 120 characters") 
				{
					openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols'] })
				} 
				else if (error.response.data.Warning.message == "Please add content for the article") 
				{
					openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent'] })
				} 
				else if (error.response.data.Warning.message == "Article content must contain between 25 and 10000 characters") 
				{
					openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols'] })
				} 
				else 
				{
					openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['unknown'] });
				}
			} 
			else if (error.response.data.Error) 
			{
				if (error.response.data.Error.message == "Article for editing not found") 
				{
					openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['articleForEditNotFound'] })
				} 
				else if (error.response.data.Error.message == "Please make changes for edit") 
				{
					openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['articleNeedChanges'] });
				}
				if(error.response.data.Error.message == "Invalid captcha solving")
				{
					openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["captcha"]});
				}
				else 
				{
					openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
				}
			} 
			else if (error.response.data.Critical) 
			{
				openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
			} 
			else 
			{
				openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
			}
		});	
	}
	const onSendButtonValidate = async () =>
	{
		const contentParts = articleText.value.split('\n');

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
							captchaVerifyCallback = onSendButtonRequest;

							captcha.value?.execute();
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

	let uploadedFiles : File[];
	let uploadedCallback : (urls: string[]) => void;

	const onUploadImgRequest = async (captchaToken : string) => 
	{
		openModal(LoaderModal);
		const promises = uploadedFiles.map((file) => 
		{
			return new Promise<{ data: { fileName: string } }>(resolve => 
			{
				const form = new FormData();

				form.append('file', file);
				form.append('captchaToken', captchaToken);

				axios.post('/api/media/img/upload', form, 
				{
					headers: 
					{
						'Content-Type': 'multipart/form-data'
					}
				})
				.then(response => 
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
						if(response.data.Warning)
						{
							if(response.data.Warning.message == "Invalid image type")
							{
								openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]});
							}
							else if(response.data.Warning.message == "File size exceeds the maximum allowable file size")
							{
								openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)["imageMaxSize"] as string).replace('{size}', response.data.Warning.params.max_upload_filesize_mb)});
							}
							else if(response.data.Warning.message == "Invalid image type")
							{
								openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageUnallowedType"]});
							}
							else
							{
								openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["unknown"]});
							}
						}
						else if(response.data.Error)
						{
							if(response.data.Error.message == "Invalid captcha solving")
							{
								openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["captcha"]});
							}
							else
							{
								openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
							}
						}
						else if(response.data.Critical)
						{
							openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
						}
						else 
						{
							openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
						}
					}
				})
				.catch(error => 
				{
					if (error.response.data) 
					{
						if(error.response.data.Warning)
						{
							if(error.response.data.Warning.message == "Invalid image type")
							{
								openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]});
							}
							else if(error.response.data.Warning.message == "File size exceeds the maximum allowable file size")
							{
								openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)["imageMaxSize"] as string).replace('{size}', error.response.data.Warning.params.max_upload_filesize_mb)});
							}
							else if(error.response.data.Warning.message == "Invalid image type")
							{
								openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageUnallowedType"]});
							}
							else
							{
								openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["unknown"]});
							}
						}
						else if(error.response.data.Error)
						{
							if(error.response.data.Error.message == "Invalid captcha solving")
							{
								openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["captcha"]});
							}
							else
							{
								openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
							}
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
		});

		const res = await Promise.all(promises);
			
		const successfulResults = res.filter(item => item !== null);

		closeModal();
		uploadedCallback(successfulResults.map((item) => '/api/media/img/'+item.data.fileName));
	}

	const onUploadImgValidate = async (files: File[], callback: (urls: string[]) => void) => 
	{
		if(files.length > 0)
		{
			uploadedFiles = files;
			uploadedCallback = callback;
			captchaVerifyCallback = onUploadImgRequest;
			captcha.value?.execute();
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
        const data = 
        {
            csrfToken: (csrfTokenInput.value as HTMLInputElement).value
        }

        await axios.post('/api/article/edit/requestApprove/' + articleEditCode.value, data)
        .then(async response =>
        {
            if(response.data.success)
            {
				if(fetchedArticleData.value != null)
				{
					fetchedArticleData.value.statistics['canRequestApprove'] = false;
					fetchedArticleData.value.statistics['approvededitorially_status'] = 1;
                	openModal(InfoModal, {status: true, text: langData.value['articleRequestApproveSuccessfully']});
				}
				
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
				loading.value = true;
				await fetchArticleData();
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
				loading.value = true;
				await fetchArticleData();
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

	let intervalId : NodeJS.Timeout | null = null;
	onMounted(async () =>
	{
		loading.value = true;
		adminStatusReCheck();
		await fetchArticleData();
		intervalId = setInterval(async () => 
		{
			await refetchArticleData();
		}, 10000);
	});

	onBeforeUnmount(() => 
    {
		if(intervalId != null)
		{
			clearInterval(intervalId);
		}
	});

	watch(() => articleText.value, () => 
    {
        checkChanges();
    });

	const onCaptchaVerify = (token: string) => 
    {
        captchaVerifyCallback(token);
    };

    const onCaptchaError = () =>
    {
        openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['captcha']});
    }
</script>

<template>
	<main v-if="!loading" class="main">
		<article v-if="fetchedArticleData" class="main__article">
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
								<img :src="`/src/assets/img/article/statuses/${fetchedArticleData.statistics.premoderation_status}.svg`" alt="premoderationStatus">
								<p>{{ ((langData['statuses'] as JsonData)['premoderationStatus'] as JsonData)[fetchedArticleData.statistics.premoderation_status.toString()] }}</p>
							</div>
						</div>
						<div class="main__article__info__statusesContainer__status">
							<p>{{ (langData['statuses'] as JsonData)['approvedEditoriallyStatusText'] }}</p>
							<div>
								<img :src="`/src/assets/img/article/statuses/${fetchedArticleData.statistics.approvededitorially_status}.svg`" alt="approvedEditoriallyStatus">
								<p>{{ ((langData['statuses'] as JsonData)['approvedEditoriallyStatus'] as JsonData)[fetchedArticleData.statistics.approvededitorially_status.toString()] }}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div v-if="viewLink != ''" class="main__article__block">
				<p class="main__article__block__title linkForViewArticle">{{ langData['linkForViewArticle'] }}</p>
				<div class="main__article__block__link">
					<input v-model="viewLink" ref="viewLinkTextInput" type="text" class="main__article__block__link__input"></input>
					<button class="main__article__block__link__copyButton" @click="copyToClipboard">
						<img src="./../../assets/img/modals/CopyButton.svg" alt="Copy" class="main__article__block__link__copyButton__icon">
					</button>
				</div>
			</div>
			<div v-if="fetchedArticleData.canRequestApprove" class="main__article__block">
				<p class="main__article__block__title">{{ langData['requestApproveTitle'] }}</p>
				<button @click="onRequestApproveArticle" class="main__article__block__button requestApproveArticleButton">{{ langData['requestApproveButtonTitle'] }}</button>
			</div>
			<div v-if="fetchedArticleData.statistics.approvededitorially_status == 3">
				<p class="main__article__block__title">{{ langData['articleApprovedWithChangesTitle'] }}</p>
				<div class="main__article__block__subblock">
					<button @click="onAcceptApproveWithChanges" class="main__article__block__button acceptArticleApprovedWithChangesButton">{{ langData['acceptArticleApprovedWithChangesButtonTitle'] }}</button>
					<button @click="onRejectApproveWithChanges" class="main__article__block__button rejectArticleApprovedWithChangesButton">{{ langData['rejectArticleApprovedWithChangesButtonTitle'] }}</button>
				</div>
			</div>
			<div v-if="fetchedArticleData.statistics.approvededitorially_status > 0 && fetchedArticleData.statistics.editorially_status != 1 && !adminStatus" class="main__article__previewContainer">
				<MdPreview class="main__article__previewContainer__preview" :modelValue="articleText" :language="LangDataHandler.currentLanguage.value"/>
			</div>
			
			<div v-if="fetchedArticleData.statistics.approvededitorially_status == 0 || fetchedArticleData.statistics.editorially_status == 1 || adminStatus" class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="articleText" @onUploadImg="onUploadImgValidate" :language="LangDataHandler.currentLanguage.value" :preview="false" noIconfont/>
				<button v-if="currentChangesStatus" class="main__article__editorContainer__sendButton" @click="onSendButtonValidate">{{ langData['sendButton'] }}</button>	
			</div>

			<div v-if="fetchedArticleData.statistics.approvededitorially_status != 2 || fetchedArticleData.statistics.editorially_status == 1 || adminStatus" class="main__article__editTags">
				<div class="main__article__editTags__tags__tag" v-for="(tag, index) in tags" :key="index">
					<p class="main__article__editTags__tags__tag__title">{{ tag }}</p>
					<button class="main__article__editTags__tags__tag__button" @click="removeTag(index)"><p>+</p></button>
				</div>
				<div class="main__article__editTags__addTag">
					<input v-model="newTag" class="main__article__editTags__addTag__input" type="text" :placeholder="(langData['addTagPlaceholder'] as string)">
					<button @click="addTag" class="main__article__editTags__addTag__button">+</button>
				</div>
			</div>
			<Captcha @on-verify="onCaptchaVerify" @on-error="onCaptchaError" ref="captcha" class="main__article__captcha"/>
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