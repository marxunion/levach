<script setup lang="ts">
	import { ref, reactive, computed, watch, Ref, ComputedRef, onMounted, onBeforeUnmount, onUnmounted } from 'vue';
	import { useRoute, useRouter, RouteLocationNormalizedLoaded, Router } from 'vue-router';
	import axios from 'axios';

	import { timestampToLocaleFormatedTime } from '../../ts/helpers/DateTimeHelper';
	import { tagsArrayToString } from '../../ts/helpers/TagsHelper'
	import { JsonData } from '../../ts/interfaces/JsonData';

	import Loader from "./../../components/Loader.vue";
	import Captcha from '../../components/Captcha.vue';

	import DropDown from '../../components/DropDown.vue';
	import DropDownVersion from "./../../components/DropDownVersion.vue";

	import CommentsList from "./../../components/CommentsList.vue";

	import { MdPreview, MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import LinkAttr from 'markdown-it-link-attributes';
	
	import { openModal, closeModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
    import InfoModal from "./../../components/modals/InfoModal.vue";
	import ShareWith from "./../../components/modals/ShareWith.vue";

    import { adminStatus, adminStatusReCheck } from '../../ts/handlers/AdminHandler'

	import { abbreviateNumber, padNumberWithZeroes } from '../../ts/helpers/NumberHelper';

	import langsData from "./locales/ArticleView.json";
	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
	
	import './../../libs/font_2605852_prouiefeic';
	
	import { csrfTokenInput, getNewCsrfToken } from '../../ts/handlers/CSRFTokenHandler';

		
	import { comments, lastLoadedComment } from '../../ts/handlers/CommentsHandler';

	import { Article } from '../../ts/interfaces/Article';

	import settings from '../../configs/main.json';

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("ArticleView", langsData).langData;

	const captcha : Ref<{ execute: () => void } | null> = ref(null);

	let captchaVerifyCallback : (token: string) => void;

	adminStatusReCheck();

	const fetchedArticleData : Ref<Article | null> = ref(null);
	
	const loading : Ref<boolean> = ref(true);
	const dropDownReloading : Ref<boolean> = ref(false);

	const commentsLoading : Ref<boolean> = ref(true);
	const commentsReloading : Ref<boolean> = ref(true);

	let currentVersion : Ref<number> = ref(0);

	let scrollTarget : Ref<HTMLElement | null> = ref(null);

	const route : RouteLocationNormalizedLoaded = useRoute();
	const router : Router = useRouter();

	const commentId : Ref<number | null> = ref(null);
	const articleViewCode : Ref<string | null> = ref(null);
 
	articleViewCode.value = route.params.articleViewCode as string;
	if(route.params.commentId)
	{
		commentId.value = Number(route.params.commentId as string);
	}

	async function fetchArticleData()
	{
		await axios.get('api/article/view/'+articleViewCode.value)
		.then(async response =>
		{
			if(response.data.versions)
			{
				fetchedArticleData.value = response.data;
				if(fetchedArticleData.value != null) 
				{
					if(fetchedArticleData.value.scrollToCommentId)
					{
						if(commentId.value == null)
						{
							commentId.value = fetchedArticleData.value.scrollToCommentId;
						}
					}
					if(currentVersion.value != 0)
					{
						dropDownReloading.value = true;
						await setTimeout(() => {}, 1000);
						currentVersion.value = 0;
						dropDownReloading.value = false;
					}
				}
			}
		})
		.catch(() =>
		{
			fetchedArticleData.value = null;
		});
	}

	// Preview
	config(
	{
		markdownItPlugins(plugins) {
			return [
			...plugins,
			{
				type: 'linkAttr',
				plugin: LinkAttr,
				options: {
				attrs: {
					target: '_blank'
				}
				}
			},
			];
		},
		editorConfig: 
		{
			languageUserDefined: 
			{
				'RU': langsData['RU'],
				'EN': langsData['EN']
			}
		}
	});

	let previewState = reactive(
	{
		language: LangDataHandler.currentLanguage.value
	});


	// Comments 

	// Comments sort
	const currentCommentsSortType : Ref<number> = ref(0);

	const sortTypesNames : ComputedRef<string[]> = computed(() => langData.value['sortTypesNames'] as string[]);

	const onChangeSortType = async (sortType : number) => 
	{
		commentsLoading.value = true;
		lastLoadedComment.value = 0;
		comments.value = [];
		currentCommentsSortType.value = sortType;

		await fetchNewComments();
	}

	const fetchNewComments = async (count : number = 8) =>
	{
		let params = {
			count: count,
			lastLoaded: lastLoadedComment.value,
			sortType: (langData.value['sortTypes'] as JsonData)[currentCommentsSortType.value]
		}
		await axios.get('api/article/comments/get/'+articleViewCode.value, 
		{
			params: params
		})
		.then(response => 
		{
			if(response.data !== null)
			{
			 	if(Array.isArray(response.data))
				{
					response.data.forEach(comment => 
					{
						comments.value.push(comment);
						lastLoadedComment.value++;
					});
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
		commentsReloading.value = false;
		commentsLoading.value = false;
	}

	const fetchCommentsBeforeId = async () =>
	{
		let params = {
			sortType: (langData.value['sortTypes'] as JsonData)[currentCommentsSortType.value]
		}
		await axios.get('api/article/comments/get/'+articleViewCode.value+'/'+commentId.value, 
		{
			params: params
		})
		.then(async response => 
		{
			if(response.data !== null)
			{
			 	if(Array.isArray(response.data))
				{
					if(response.data.length > 0)
					{
						response.data.forEach(comment => 
						{
							comments.value.push(comment);
							lastLoadedComment.value++;
						});
					}
					else
					{
						await fetchNewComments();
					}
				}
				else
				{
					await fetchNewComments();
				}
			}
			else
			{
				await fetchNewComments();
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
		commentsReloading.value = false;
		commentsLoading.value = false;
	}

	

	const handleCommentsScroll = async () => 
    {
        const scrollElement = scrollTarget.value;
        if (scrollElement !== null && !loading.value && !commentsReloading.value) 
        {
            const bottomDistance = (scrollElement as HTMLElement).getBoundingClientRect().bottom - window.innerHeight;
            if (bottomDistance <= 0) 
            {
                commentsReloading.value = true;
                await fetchNewComments();
            }
        }
    }

	// NewComment

	const currentCommentReaction : Ref<number> = ref(0);
	let newCommentEditorState = reactive(
	{
		text: "",
		language: LangDataHandler.currentLanguage.value
	});

	const onLikeReaction = () =>
	{
		if(currentCommentReaction.value === 1)
		{
			currentCommentReaction.value = 0;
		}
		else
		{
			currentCommentReaction.value = 1;
		}
	}

	const onDislikeReaction = () =>
	{
		if(currentCommentReaction.value === 2)
		{
			currentCommentReaction.value = 0;
		}
		else
		{
			currentCommentReaction.value = 2;
		}
	}

	let uploadedFiles : File[];
	let uploadedCallback : (urls: string[]) => void;

	const onNewCommentUploadImgRequest = async (captchaToken : string) => 
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
								openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["unknown"]});
							}
						}
						else
						{
							if (response.data) 
							{
								if(response.data.Warning)
								{
									if(response.data.Warning.message == "Invalid image type")
									{
										openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]});
									}
									else if(response.data.Warning.message == "File size exceeds the maximum allowable file size")
									{
										openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)["imageMaxSize"] as string).replace('{size}', response.data.Warning.params.max_upload_filesize_mb)});
									}
									else if(response.data.Warning.message == "Invalid image type")
									{
										openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["imageUnallowedType"]});
									}
									else
									{
										openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["unknown"] });
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
									openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
								}
								else 
								{
									openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
								}
							}
							else
							{
								openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
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
									openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]});
								}
								else if(error.response.data.Warning.message == "File size exceeds the maximum allowable file size")
								{
									openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)["imageMaxSize"] as string).replace('{size}', error.response.data.Warning.params.max_upload_filesize_mb)});
								}
								else if(error.response.data.Warning.message == "Invalid image type")
								{
									openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["imageUnallowedType"]});
								}
								else
								{
									openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["unknown"] });
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
								};
							}
							else if(error.response.data.Critical)
							{
								openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
							}
							else 
							{
								openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
							}
						}
						else
						{
							openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
						}
					});
				});
			}
		);

		const res = await Promise.all(promises);
			
		const successfulResults = res.filter(item => item !== null);

		closeModal();
		uploadedCallback(successfulResults.map((item) => '/api/media/img/'+item.data.fileName));
	}

	const onNewCommentUploadImgValidate = async (files: File[], callback: (urls: string[]) => void) => 
	{
		if(files.length > 0)
		{
			uploadedFiles = files;
			uploadedCallback = callback;
			if(settings.captchaEnabled)
			{
				captchaVerifyCallback = onNewCommentUploadImgRequest;
				captcha.value?.execute();
			}
			else
			{
				onNewCommentUploadImgRequest('token');
			}
		}
	}

	const onCreateNewCommentRequest = async (captchaToken : string) =>
	{
		await getNewCsrfToken();

		if(csrfTokenInput.value == null)
		{
			openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
			return;
		}

		const data = {
			csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
			captchaToken: captchaToken,
			type: 'comment',
			text: newCommentEditorState.text,
			rating_influence: currentCommentReaction.value
		}
	
		await axios.post('/api/article/comment/new/'+articleViewCode.value, data)
		.then(async response => 
		{
			if(response.data.success)
			{
				newCommentEditorState.text = '';
				await fetchArticleData();
				commentsLoading.value = true;
				lastLoadedComment.value = 0;
				currentCommentReaction.value = 0;
				comments.value = [];
				await fetchNewComments();
			}
			else
			{
				if(response.data.Warning)
				{
					openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
				}
				else if(response.data.Error)
				{
					if(response.data.Error.message == "Invalid captcha solving")
					{
						openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["captcha"]});
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
				openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
			}
			else if(error.response.data.Error)
			{
				if(error.response.data.Error.message == "Invalid captcha solving")
				{
					openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["captcha"]});
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

	const onCreateNewCommentValidate = async () =>
	{
		if(newCommentEditorState.text.length > 0)
		{
			if(settings.captchaEnabled)
			{
				captchaVerifyCallback = onCreateNewCommentRequest;
				captcha.value?.execute();
			}
			else
			{
				onCreateNewCommentRequest('token');
			}
		}
	}

	let intervalId : NodeJS.Timeout | null = null;
	onMounted(async () => 
	{
		if(articleViewCode.value)
		{
			articleViewCode.value = encodeURIComponent(articleViewCode.value.replace('>','#'));

			await fetchArticleData();
			if (fetchedArticleData.value != null) 
			{
				currentVersion.value = 0;

				comments.value = [];

				let app = document.querySelector('#app');
				if(app != null)
				{
					app.addEventListener('scroll', handleCommentsScroll)
				}

				let ps = document.querySelector('.ps');
				if(ps != null)
				{
					ps.addEventListener('scroll', handleCommentsScroll)
				}

				commentsReloading.value = true;
				commentsLoading.value = true;
				lastLoadedComment.value = 0;
				currentCommentReaction.value = 0;

				if(commentId.value != null)
				{
					currentCommentsSortType.value = 1;
					await fetchCommentsBeforeId();
				}
				else
				{
					await fetchNewComments();
				}

				intervalId = setInterval(async () => 
				{
					await fetchArticleData();
				}, 10000);
			}
		}
		
		loading.value = false;
	});

    onBeforeUnmount(() => 
    {
		if(intervalId != null)
		{
			clearInterval(intervalId);
		}

		let app = document.querySelector('#app');
        if(app != null)
        {
            app.removeEventListener('scroll', handleCommentsScroll)
        }

        let ps = document.querySelector('.ps');
        if(ps != null)
        {
            ps.removeEventListener('scroll', handleCommentsScroll)
        }
    });
	
	onUnmounted(() =>
	{
		commentsReloading.value = true;
		commentsLoading.value = true;
		lastLoadedComment.value = 0;
		currentCommentReaction.value = 0;
		comments.value = [];
	});

	const onRejectApproveArticle = async () =>
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
		axios.post('/api/admin/article/approve/' + articleViewCode.value, data)
		.then(async response =>
		{
			if(response.data.success)
            {
                const modal = await openModal(InfoModal, {status: true, text: langData.value['articleRejectApproveSuccessfully']});
				modal.onclose = function()
				{
					router.push('/admin/articles/waitingApproval');
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

	const onRejectPremoderateArticle = async (versionId : number) =>
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
			version_id: versionId,
			status: 0
		}

		axios.post('/api/admin/article/premoderate/' + articleViewCode.value, data)
		.then(async response =>
		{
			if(response.data.success)
            {
                const modal = await openModal(InfoModal, {status: true, text: langData.value['articleRejectPremoderateSuccessfully']});
				modal.onclose = function()
				{
					router.push('/admin/articles/waitingPremoderate');
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

	const onAcceptPremoderateArticle = async (versionId : number) =>
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
			version_id: versionId,
			status: 1
		}

		axios.post('/api/admin/article/premoderate/' + articleViewCode.value, data)
		.then(async response =>
		{
			if(response.data.success)
            {
                const modal = await openModal(InfoModal, {status: true, text: langData.value['articleAcceptPremoderateSuccessfully']});
				modal.onclose = function()
				{
					router.push('/admin/articles/waitingPremoderate');
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

	const onDeleteArticle = async () =>
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

		axios.post('/api/admin/article/delete/' + articleViewCode.value, data)
		.then(async response =>
        {
            if(response.data.success)
            {
                const modal = await openModal(InfoModal, {status: true, text: langData.value['articleDeleteSuccessfully']});
				modal.onclose = function()
				{
					router.push('/');
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

	watch(langData, () =>
	{
		previewState.language = LangDataHandler.currentLanguage.value;
		newCommentEditorState.language = LangDataHandler.currentLanguage.value;
	});

	const onCreatedNewSubcomment =  async () => 
	{
		await fetchArticleData();
	}

	const onDeletedSubcomment =  async () => 
	{
		await fetchArticleData();
	}

    const onCaptchaVerify = (token: string) => 
    {
		captchaVerifyCallback(token);
    };

    const onCaptchaError = () =>
    {
        openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['captcha']});
    }

	const onShare = (articleTitle : string) => 
	{
		openModal(ShareWith, { link: "https://" + settings['domainName'] + "/#/article/" + articleViewCode.value, text: articleTitle })
	}
</script>

<template>
	<main v-if="!loading" class="main">
		<article v-if="fetchedArticleData" class="main__article">
			<div class="main__article__previewContainer">
				<p class="main__article__previewContainer__titleId">#{{ padNumberWithZeroes(fetchedArticleData.view_id) }}</p>
				<p class="main__article__previewContainer__titleTime">{{ timestampToLocaleFormatedTime(fetchedArticleData.versions[currentVersion].created_date) }}</p>
				<MdPreview class="main__article__previewContainer__preview" :modelValue="fetchedArticleData.versions[currentVersion].text" :language="previewState.language"/>
				<p class="main__article__previewContainer__tags">{{ tagsArrayToString(fetchedArticleData.versions[currentVersion].tags) }}</p>
				
				<div v-if="adminStatus && fetchedArticleData.approvededitorially_status == 1" class="main__article__previewContainer__buttons">
					<a @click="onDeleteArticle()" class="main__article__previewContainer__buttons__button deleteArticleButton">{{ langData['deleteArticleButton'] }}</a>
					<a @click="onRejectApproveArticle()" class="main__article__previewContainer__buttons__button rejectApproveArticleButton">{{ langData['rejectApproveArticleButton'] }}</a>
					<a :href="'#/admin/article/approve/'+articleViewCode" class="main__article__previewContainer__buttons__button acceptApproveArticleButton">{{ langData['acceptApproveArticleButton'] }}</a>
				</div>
				<div v-else-if="adminStatus && (fetchedArticleData.versions[currentVersion].premoderation_status == 1)" class="main__article__previewContainer__buttons">
					<a @click="onRejectPremoderateArticle(fetchedArticleData.versions[currentVersion].version_id)" class="main__article__previewContainer__buttons__button rejectPremoderateArticleButton">{{ langData['rejectPremoderateArticleButton'] }}</a>
					<a @click="onAcceptPremoderateArticle(fetchedArticleData.versions[currentVersion].version_id)" class="main__article__previewContainer__buttons__button acceptPremoderateArticleButton">{{ langData['acceptPremoderateArticleButton'] }}</a>
				</div>
				<div v-else-if="adminStatus" class="main__article__previewContainer__buttons oneButton">
					<a @click="onDeleteArticle()" class="main__article__previewContainer__buttons__button deleteArticleButton">{{ langData['deleteArticleButton'] }}</a>
				</div>
				<div class="main__article__previewContainer__reactions">
					<div class="main__article__previewContainer__reactions__statistics">
						<img src="../../assets/img/article/rating.svg" alt="Rating: " class="main__article__previewContainer__reactions__statistics__icon ratingIcon">
						<p class="main__article__previewContainer__reactions__statistics__title likeCounter">{{ abbreviateNumber(fetchedArticleData.rating) }}</p>
						<img @click="onShare(fetchedArticleData.versions[currentVersion].title)" src="../../assets/img/article/share.svg" alt="Share..." class="main__article__previewContainer__reactions__statistics__icon shareIcon">
					</div>
					<div class="main__article__previewContainer__reactions__comments">
						<img src="../../assets/img/article/comment.svg" alt="Comments: " class="main__article__previewContainer__reactions__comments__icon commentIcon">
						<p class="main__article__previewContainer__reactions__comments__title commentsCounter">{{ abbreviateNumber(fetchedArticleData.comments_count) }}</p>
					</div>
				</div>
				
				<DropDownVersion
				v-if="!dropDownReloading"
				:versions="fetchedArticleData.versions"
				class="main__article__previewContainer__selectVersion" 
				@input="(version : number) => currentVersion = version" />
			</div>
			<div class="main__article__comments">
				<div class="main__article__comments__header">
					<p class="main__article__comments__header__title">{{ langData['commentsTitle'] }}</p>
					<div class="main__article__comments__header__sort">
						<p class="main__article__comments__header__sort__title">{{ langData['sortTitle'] }}</p>
						<DropDown :options="sortTypesNames" :default="sortTypesNames[currentCommentsSortType]" class="main__article__comments__header__sort__selectSortType" @inputIndex="onChangeSortType" />
					</div>
				</div>

				<div class="main__article__comments__newComment">
					<MdEditor class="main__article__comments__newComment__editor" v-model="(newCommentEditorState.text as string)" @onUploadImg="onNewCommentUploadImgValidate" :language="newCommentEditorState.language" noIconfont :preview="false"/>
					<img @click="onCreateNewCommentValidate()" src="./../../assets/img/article/sendCommentButton.svg" alt="Send" class="main__article__comments__newComment__sendButton">
					<div class="main__article__comments__newComment__reactions">
						<img v-if="currentCommentReaction === 1" @click="onLikeReaction()" src="./../../assets/img/article/comments/likeSelected.svg" alt="Like Selected" class="main__article__comments__newComment__reactions__reaction">
						<img v-else @click="onLikeReaction()" src="./../../assets/img/article/comments/like.svg" alt="Like" class="main__article__comments__newComment__reactions__reaction">
						
						<img v-if="currentCommentReaction === 2" @click="onDislikeReaction()" src="./../../assets/img/article/comments/dislikeSelected.svg" alt="Dislike Selected" class="main__article__comments__newComment__reactions__reaction">
						<img v-else @click="onDislikeReaction()" src="./../../assets/img/article/comments/dislike.svg" alt="Dislike" class="main__article__comments__newComment__reactions__reaction">
					</div>
					<Captcha v-if="settings.captchaEnabled" @on-verify="onCaptchaVerify" @on-error="onCaptchaError" ref="captcha" class="main__article__captcha"/>
				</div>
			
				<div v-if="!commentsLoading" class="main__article__comments__commentsList">
					<CommentsList @onCreatedNewSubcomment="onCreatedNewSubcomment()" @onDeletedSubcomment="onDeletedSubcomment()" v-for="comment in comments" :key="comment.id" :comment="comment" :level="0" :articleViewCode="articleViewCode" :articleTitle="fetchedArticleData.versions[currentVersion].title" :scrollToCommentId="commentId"/>
					<div v-if="commentsReloading" class="main__article__comments__commentsList__reloader">
						<Loader/>
					</div>
					<div ref="scrollTarget" style="height: 10px;"></div>
				</div>
				<div v-else class="main__article__comments__commentsList load">
					<Loader/>
				</div>
			</div>
		</article>
		<article v-else class="main__article">
			<h1 class="main__article__title">{{ (langData['errors'] as JsonData)['articleNotFound'] }}</h1>
		</article>
	</main>
	<main v-else class="main">
		<Loader/>
	</main>
</template>

<style lang="css">
	.main__article__comments__newComment__editor .md-editor-footer
    {
		display: none;
	}
</style>
<style lang="scss" scoped src="./scss/ArticleView.scss"></style>