<script setup lang="ts">
	import { ref, Ref, computed, ComputedRef, reactive, watch, onMounted, onBeforeUnmount } from 'vue';
	import { useRoute, useRouter } from 'vue-router';
	import axios from 'axios';

	import { timestampToLocaleFormatedTime } from './../../ts/DateTimeHelper';
	import { tagsArrayToString } from './../../ts/TagsHelper'
	import { JsonData } from './../../ts/JsonHandler';

	import Loader from "./../../components/Loader.vue";

	import DropDown from '../../components/DropDown.vue';
	import DropDownVersion from "./../../components/DropDownVersion.vue";

	import CommentsList from "./../../components/CommentsList.vue";

	import { MdPreview, MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { openModal, closeModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
    import InfoModal from "./../../components/modals/InfoModal.vue";
	import ShareWith from "./../../components/modals/ShareWith.vue";
	

    import { adminStatus, adminStatusReCheck } from './../../ts/AdminHandler'

	import { abbreviateNumber } from './../../ts/AbbreviateNumberHelper';

	import langsData from "./locales/ArticleView.json";
	import { LangDataHandler } from "./../../ts/LangDataHandler";
	
	import './../../libs/font_2605852_prouiefeic';
	
	import { csrfTokenInput, getNewCsrfToken } from '../../ts/csrfTokenHelper';

	import { comments } from '../../ts/CommentsHelper';

	const langData = LangDataHandler.initLangDataHandler("ArticleView", langsData).langData;

	adminStatusReCheck();

	const lastLoadedComment : Ref<number> = ref(0);
	const fetchedData : Ref<any> = ref();
	const loading : Ref<boolean> = ref(true);
	const commentsLoading : Ref<boolean> = ref(true);
	const commentsReloading : Ref<boolean> = ref(true);

	let currentVersion : Ref<number> = ref(1);

	let scrollTarget = ref(null);

	// Comments
	const route = useRoute();
	const router = useRouter();
	const articleViewCode : Ref<string | null> = ref<string | null>(null);
 
	articleViewCode.value = route.params.articleViewCode as string;

	async function fetchArticleData()
	{
		return await axios.get('/api/article/view/'+articleViewCode.value)
		.then(response =>
		{
			if(response.data.versions)
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
					if(response.data.Error.message == "Article not found")
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
				if(error.response.data.Error.message == "Article not found")
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

	// Preview
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

	let previewState = reactive(
	{
		language: LangDataHandler.currentLanguage.value
	});


	// Comments 

	const fetchNewComments = async (count : number = 8) =>
	{
		let params = {
			count: count,
			lastLoaded: lastLoadedComment.value
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
					response.data.forEach(comment => {
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

	// Comments sort
	const currentSortType : Ref<number> = ref(0);

	const sortTypesNames : ComputedRef<string[]> = computed(() => langData.value['sortTypesNames'] as string[]);

	const onChangeSortType = async (sortType : number) => 
	{
		commentsLoading.value = true;
		lastLoadedComment.value = 0;
		comments.value = [];
		currentSortType.value = sortType;
		await fetchNewComments();
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

	const onNewCommentUploadImg = async (files: File[], callback: (urls: string[]) => void) => 
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
									openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["unknown"]});
								}
							}
							else
							{
								openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"]});

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
										openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]});
									}
									else if(error.response.data.Warning.message == "UploadImage File size exceeds the maximum allowable file size")
									{
										openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)["imageMaxSize"] as string).replace('{size}', error.response.data.Warning.params.max_upload_filesize_mb)});
									}
									else if(error.response.data.Warning.message == "UploadImage Invalid image type")
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
									openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
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
			callback(successfulResults.map((item) => '/api/media/img/'+item.data.fileName));
		}
	}

	const onCreateNewComment = async () =>
	{
		await getNewCsrfToken();

		if(csrfTokenInput.value == null)
		{
			openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
			return;
		}

		const data = {
			type: 'comment',
			text: newCommentEditorState.text,
			rating_influence: currentCommentReaction.value,
			csrfToken: (csrfTokenInput.value as HTMLInputElement).value
		}

		await axios.post('/api/article/comments/new/'+articleViewCode.value, data)
		.then(response => 
		{
			if(response.data.success)
			{
			 	openModal(InfoModal, {status: true, text: langData.value['commentCreatedSuccessfully']});
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
	onMounted(async function() {
		try 
		{
			fetchedData.value = await fetchArticleData();
			if (fetchedData.value != null) 
			{
				currentVersion.value = fetchedData.value.versions.length;

				comments.value = [];
				let ps = document.querySelector('.ps');
				if(ps != null)
				{
					ps.addEventListener('scroll', handleCommentsScroll)
				}

				commentsReloading.value = true;
				commentsLoading.value = true;

				await fetchNewComments();

				intervalId = setInterval(async () => 
				{
					fetchedData.value = await fetchArticleData();
				}, 10000);
			}
			loading.value = false;
		} 
		catch 
		{
			loading.value = false;
			fetchedData.value = null;
		}
	});

    onBeforeUnmount(() => 
    {
		if(intervalId != null)
		{
			clearInterval(intervalId);
		}

        let ps = document.querySelector('.ps');
        if(ps != null)
        {
            ps.removeEventListener('scroll', handleCommentsScroll)
        }
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
					router.push('/admin/articles/waitingApprove');
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

	const onRejectPremoderateArticle = async () =>
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

	const onAcceptPremoderateArticle = async () =>
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
			csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
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

	const onShare = () => 
	{
		openModal(ShareWith, { link: 'http://localhost:8000/#/article/'+articleViewCode.value})
	}

	watch(langData, () =>
	{
		previewState.language = LangDataHandler.currentLanguage.value;
		newCommentEditorState.language = LangDataHandler.currentLanguage.value;
	});
</script>

<template>
	<main v-if="!loading" class="main">
		<article v-if="fetchedData" class="main__article">
			<div class="main__article__previewContainer">
				<p class="main__article__previewContainer__titleTime">{{ timestampToLocaleFormatedTime(fetchedData.versions[currentVersion-1].created_date) }}</p>
				<MdPreview class="main__article__previewContainer__preview" :modelValue="fetchedData.versions[currentVersion-1].text" :language="previewState.language"/>
				<p class="main__article__previewContainer__tags">{{ tagsArrayToString(fetchedData.versions[currentVersion-1].tags) }}</p>
				
				<div v-if="adminStatus && fetchedData['approvededitorially_status'] == 1" class="main__article__previewContainer__buttons">
					<a @click="onDeleteArticle()" class="main__article__previewContainer__buttons__button deleteArticleButton">{{ langData['deleteArticleButton'] }}</a>
					<a @click="onRejectApproveArticle()" class="main__article__previewContainer__buttons__button rejectApproveArticleButton">{{ langData['rejectApproveArticleButton'] }}</a>
					<a :href="'#/admin/article/approve/'+articleViewCode" class="main__article__previewContainer__buttons__button acceptApproveArticleButton">{{ langData['acceptApproveArticleButton'] }}</a>
				</div>
				<div v-else-if="adminStatus && fetchedData['premoderation_status'] == 1" class="main__article__previewContainer__buttons">
					<a @click="onRejectPremoderateArticle()" class="main__article__previewContainer__buttons__button rejectPremoderateArticleButton">{{ langData['rejectPremoderateArticleButton'] }}</a>
					<a @click="onAcceptPremoderateArticle()" class="main__article__previewContainer__buttons__button acceptPremoderateArticleButton">{{ langData['acceptPremoderateArticleButton'] }}</a>
				</div>
				<div v-else-if="adminStatus" class="main__article__previewContainer__buttons oneButton">
					<a @click="onDeleteArticle()" class="main__article__previewContainer__buttons__button deleteArticleButton">{{ langData['deleteArticleButton'] }}</a>
				</div>
				<div class="main__article__previewContainer__reactions">
					<div class="main__article__previewContainer__reactions__statistics">
						<img src="../../assets/img/article/rating.png" alt="Rating: " class="main__article__previewContainer__reactions__statistics__icon ratingIcon">
						<p class="main__article__previewContainer__reactions__statistics__title likeCounter">{{ abbreviateNumber(fetchedData.statistics.rating) }}</p>
						<img @click="onShare" src="../../assets/img/article/share.svg" alt="Share..." class="main__article__previewContainer__reactions__statistics__icon shareIcon">
					</div>
					<div class="main__article__previewContainer__reactions__comments">
						<img src="../../assets/img/article/comment.svg" alt="Comments: " class="main__article__previewContainer__reactions__comments__icon commentIcon">
						<p class="main__article__previewContainer__reactions__comments__title commentsCounter">{{ abbreviateNumber(fetchedData.statistics.comments) }}</p>
					</div>
				</div>
				
				<DropDownVersion
				:max-version="fetchedData.versions.length"
				class="main__article__previewContainer__selectVersion" 
				@input="(version : number) => currentVersion = version" />
			</div>
			<div class="main__article__comments">
				<div class="main__article__comments__header">
					<p class="main__article__comments__header__title">{{ langData['commentsTitle'] }}</p>
					<div class="main__article__comments__header__sort">
						<p class="main__article__comments__header__sort__title">{{ langData['sortTitle'] }}</p>
						<DropDown :options="sortTypesNames" :default="sortTypesNames[currentSortType]" class="main__article__comments__header__sort__selectSortType" @inputIndex="onChangeSortType" />
					</div>
				</div>

				<div class="main__article__comments__newComment">
					<MdEditor class="main__article__comments__newComment__editor" v-model="(newCommentEditorState.text as string)" @onUploadImg="onNewCommentUploadImg" :language="newCommentEditorState.language" noIconfont :preview="false"/>
					<img @click="onCreateNewComment()" src="./../../assets/img/article/sendCommentButton.svg" alt="Send" class="main__article__comments__newComment__sendButton">
					<div class="main__article__comments__newComment__reactions">
						<img v-if="currentCommentReaction === 1" @click="onLikeReaction()" src="./../../assets/img/article/comments/likeSelected.svg" alt="Like Selected" class="main__article__comments__newComment__reactions__reaction">
						<img v-else @click="onLikeReaction()" src="./../../assets/img/article/comments/like.svg" alt="Like" class="main__article__comments__newComment__reactions__reaction">
						
						<img v-if="currentCommentReaction === 2" @click="onDislikeReaction()" src="./../../assets/img/article/comments/dislikeSelected.svg" alt="Dislike Selected" class="main__article__comments__newComment__reactions__reaction">
						<img v-else @click="onDislikeReaction()" src="./../../assets/img/article/comments/dislike.svg" alt="Dislike" class="main__article__comments__newComment__reactions__reaction">
					</div>
				</div>
				
				<div v-if="!commentsLoading" class="main__article__comments__commentsList">
					<CommentsList v-for="comment in comments" :key="comment.id" :comment="comment" :level="0" :articleViewCode="articleViewCode"/>
					<div ref="scrollTarget" style="height: 10px;"></div>
					<div v-if="commentsReloading" class="main__article__comments__commentsList__reloader">
						<Loader/>
					</div>
					
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