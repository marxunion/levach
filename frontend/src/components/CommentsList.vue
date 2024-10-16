<script setup lang="ts">
	import { ref, reactive, watch, Ref, onMounted, defineProps, defineEmits } from 'vue';
	import axios from 'axios';
	import { MdEditor, MdPreview, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { ThemeHandler } from '../ts/handlers/ThemeHandler';

	import { adminStatus, adminStatusReCheck } from '../ts/handlers/AdminHandler';

	import { padNumberWithZeroes, abbreviateNumber } from '../ts/helpers/NumberHelper';
	
	import { LangDataHandler } from "../ts/handlers/LangDataHandler";
	import langsData from "./locales/CommentsList.json";
	import Captcha from './Captcha.vue';

	import LinkAttr from 'markdown-it-link-attributes';

	import { timestampToLocaleFormatedTime } from '../ts/helpers/DateTimeHelper';

	import Loader from './Loader.vue';
	import ShareWith from './modals/ShareWith.vue';

	import InfoModal from './modals/InfoModal.vue';
	import { closeModal, openModal } from 'jenesius-vue-modal';
	import { JsonData } from '../ts/interfaces/JsonData';
	import LoaderModal from './modals/LoaderModal.vue';

	import { csrfTokenInput, getNewCsrfToken } from '../ts/handlers/CSRFTokenHandler';

	import { lastLoadedComment } from '../ts/handlers/CommentsHandler';


	import mainConfig from '../configs/main.json';


	const props = defineProps(['articleViewCode', 'articleTitle', 'scrollToCommentId', 'comment', 'level']);

	const emits = defineEmits(["onDeletedSubcomment", "onCreatedNewSubcomment"]);

	const langData = LangDataHandler.initLangDataHandler("CommentsList", langsData).langData;

	const captcha : Ref<{ execute: () => void } | null> = ref(null);

	let captchaVerifyCallback : (token: string) => void;

	const targetComment : Ref<HTMLElement | null> = ref(null);
	const commentText : Ref<HTMLElement | null> = ref(null);
	const commentTextHeight : Ref<number> = ref(0);

	const loading : Ref<boolean> = ref(false);

	const collapsed : Ref<boolean> = ref(true);

	const answerStatus : Ref<number> = ref(0);

	

	// Preview

	config(
	{
		markdownItPlugins(plugins) 
		{
			return [
				...plugins,
				{
					type: 'linkAttr',
					plugin: LinkAttr,
					options: 
					{
						attrs: 
						{
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

	let newSubcommentEditorState = reactive(
	{
		text: "",
		language: LangDataHandler.currentLanguage.value
	});

	// Subcomments 
	const refetchComment = async () =>
	{
		props.comment.subcomments = [];

		let params = {
			commentId: props.comment.id
		}
		await axios.get('api/article/comment/get/'+props.articleViewCode, 
		{
			params: params
		})
		.then(response => 
		{
			if(response.data)
			{
				props.comment.id = response.data.id;
				props.comment.created_date = response.data.created_date;
				props.comment.rating = response.data.rating;
				props.comment.subcomments = response.data.subcomments;
			}
			else
			{
				props.comment.id = null;
				props.comment.created_date = null;
				props.comment.rating = null;
				props.comment.subcomments = null;
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
		loading.value = false;
	}

	// NewSubcomment

	const currentSubcommentReaction : Ref<number> = ref(0);
	
	const onLikeReaction = () =>
	{
		if(currentSubcommentReaction.value === 1)
		{
			currentSubcommentReaction.value = 0;
		}
		else
		{
			currentSubcommentReaction.value = 1;
		}
	}

	const onDislikeReaction = () =>
	{
		if(currentSubcommentReaction.value === 2)
		{
			currentSubcommentReaction.value = 0;
		}
		else
		{
			currentSubcommentReaction.value = 2;
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
				})
				.catch((error) => 
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
							}
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
		});

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
			if(mainConfig.captchaEnabled)
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

	watch(langData, () =>
	{
		previewState.language = LangDataHandler.currentLanguage.value;
		newSubcommentEditorState.language = LangDataHandler.currentLanguage.value;
	});

	const onCreateNewSubcommentRequest = async (captchaToken : string) => 
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
			commentId: props.comment.id,
			parent_comment_id: props.comment.id,
			text: newSubcommentEditorState.text,
			rating_influence: currentSubcommentReaction.value
		}

		await axios.post('/api/article/comment/subcomment/new/'+props.articleViewCode, data)
		.then(async response => 
		{
			if(response.data.success)
			{	
				answerStatus.value = 0;
				currentSubcommentReaction.value = 0;
				newSubcommentEditorState.text = '';
				loading.value = true;
				emits('onCreatedNewSubcomment');
				await refetchComment();
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
						openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
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
					openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
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

	const onCreateNewSubcommentValidate = async () => 
	{
		if(newSubcommentEditorState.text.length > 0)
		{
			if(mainConfig.captchaEnabled)
			{
				captchaVerifyCallback = onCreateNewSubcommentRequest;
				captcha.value?.execute();
			}
			else
			{
				onCreateNewSubcommentRequest('token');
			}
		}
	}

	const onCommentDelete = async () => 
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
			commentId: props.comment.id
		}

		await axios.post('/api/admin/article/comment/delete/'+props.articleViewCode, data)
		.then(async response => 
		{
			if(response.data.success)
			{
				if(props.comment.parent_comment_id == null)
				{
					lastLoadedComment.value = lastLoadedComment.value - 1;
				}

				openModal(InfoModal, {status: true, text: langData.value['commentDeletedSuccessfully']});
				emits('onDeletedSubcomment');
				await refetchComment();
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

	const onCreateAnswer = () => 
	{
		if(answerStatus.value == 1)
		{
			answerStatus.value = 0;
		}
		else
		{
			answerStatus.value = 1;
		}
	}
	
	onMounted(() => 
	{
		adminStatusReCheck();

		let height : number | undefined = targetComment.value?.querySelector('.comment__text')?.clientHeight;
		if(height)
		{
			commentTextHeight.value = height;
		}
		setTimeout(() => 
		{
			if(props.scrollToCommentId === props.comment.id)
			{
				if(targetComment.value) 
				{
					targetComment.value.classList.add('scrollToComment');
					targetComment.value.scrollIntoView({ block: "center" });
				}
			}
		}, 300);
	});

	const onCreatedNewSubcomment =  async () => 
	{
		emits('onCreatedNewSubcomment');
	}

	const onDeletedSubcomment =  async () => 
	{
		loading.value = true;
		await refetchComment();
		emits('onDeletedSubcomment');
	}

	const onCaptchaVerify = (token: string) => 
    {
        captchaVerifyCallback(token);
    };

    const onCaptchaError = () =>
    {
        openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['captcha']});
    }

	const onShare = () => 
	{
		openModal(ShareWith, { link: "https://" + mainConfig['domainName'] + "/#/article/" + props.articleViewCode + "/" + props.comment.id, text: props.articleTitle })
	}
</script>

<template>
	<div v-if="comment.id != null">
		<div ref="targetComment" class="comment" :style="{ marginLeft: `${5 * level}%` }">
			<div class="comment__header">
				<p class="comment__header__title id">#{{ padNumberWithZeroes(comment.view_id) }}</p>
				<p class="comment__header__title time">{{ timestampToLocaleFormatedTime(comment.created_date) }}</p>
			</div>
			<MdPreview ref="commentText" :class='"comment__text " + (collapsed ? "collapsed" : "")' :modelValue="comment.text" :language="previewState.language"/>
			<p v-if="commentTextHeight > 250 && collapsed" class="comment__collapse" @click="collapsed = false">{{ langData['readMore'] }}</p>
			<p v-else-if="commentTextHeight > 250" class="comment__collapse" @click="collapsed = true">{{ langData['collapse'] }}</p>
			<div class="comment__bar">
				<div class="comment__bar__actions">
					<p @click="onCreateAnswer()" class="comment__bar__actions__action">{{ langData['titleAnswer'] }}</p>
					<p @click="onCommentDelete()" v-if="adminStatus" class="comment__bar__actions__action">{{ langData['titleDelete'] }}</p>
				</div>
				<div class="comment__bar__reactions">
					<img src="../assets/img/article/rating.svg" alt="Rating: " class="comment__bar__reactions__icon ratingIcon">
					<p class="comment__bar__reactions__title">{{ abbreviateNumber(comment.rating) }}</p>
					<img @click="onShare" src="../assets/img/article/share.svg" alt="Share..." class="comment__bar__reactions__icon shareIcon">
					<p v-if="comment.rating_influence > 0" class="comment__bar__reactions__title ratingInfluenceUp"> {{ comment.rating_influence }}</p>
					<p v-else-if="comment.rating_influence < 0" class="comment__bar__reactions__title ratingInfluenceDown"> {{ comment.rating_influence }}</p>
				</div>
			</div>
		</div>
		<div v-if="answerStatus" class="comment__newSubcomment">
			<MdEditor class="comment__newSubcomment__editor" v-model="(newSubcommentEditorState.text as string)" @onUploadImg="onNewCommentUploadImgValidate" :language="newSubcommentEditorState.language" :theme="ThemeHandler.instance.getCurrentThemeGrayscale.value" noIconfont :preview="false"/>
			<img @click="onCreateNewSubcommentValidate()" src="./../assets/img/article/sendCommentButton.svg" alt="Send" class="comment__newSubcomment__sendButton">
			<div class="comment__newSubcomment__reactions">
				<img v-if="currentSubcommentReaction === 1" @click="onLikeReaction()" src="./../assets/img/article/comments/likeSelected.svg" alt="Like Selected" class="comment__newSubcomment__reactions__reaction">
				<img v-else @click="onLikeReaction()" src="./../assets/img/article/comments/like.svg" alt="Like" class="comment__newSubcomment__reactions__reaction">
							
				<img v-if="currentSubcommentReaction === 2" @click="onDislikeReaction()" src="./../assets/img/article/comments/dislikeSelected.svg" alt="Dislike Selected" class="comment__newSubcomment__reactions__reaction">
				<img v-else @click="onDislikeReaction()" src="./../assets/img/article/comments/dislike.svg" alt="Dislike" class="comment__newSubcomment__reactions__reaction">
			</div>
			<Captcha v-if="mainConfig.captchaEnabled" @on-verify="onCaptchaVerify" @on-error="onCaptchaError" ref="captcha" class="main__article__captcha"/>
		</div>
	</div>
	
	<CommentsList @onCreatedNewSubcomment="onCreatedNewSubcomment()" @onDeletedSubcomment="onDeletedSubcomment()" v-if="!loading" v-for="subcomment in comment.subcomments" :key="subcomment.id" :comment="subcomment" :level="level + 1" :articleViewCode="articleViewCode" :articleTitle="articleTitle" :scrollToCommentId="scrollToCommentId"/>
	<Loader v-else />
</template>

<style scoped lang="scss">
:root[data-theme="light"]
{
	.comment__newSubcomment__editor
    {
        --md-bk-color: #f1f1f1;
    }
}
:root[data-theme="dark"]
{
	.comment__newSubcomment__editor
    {
        --md-bk-color: #3A3A40;
    }
}
</style>

<style lang="css">
	.comment__text *
	{
		overflow-y: hidden;
	}
	.comment__newSubcomment__editor .md-editor-footer
    {
		display: none;
	}
</style>

<style lang="scss" scoped src="./scss/CommentsList.scss"></style>