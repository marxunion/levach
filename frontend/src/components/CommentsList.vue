<script setup lang="ts">
	import { watch, ref, Ref, reactive, defineProps } from 'vue';

	import axios from 'axios';

	import { MdPreview, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { adminStatus, adminStatusReCheck } from './../ts/AdminHandler'

	import { abbreviateNumber } from './../ts/AbbreviateNumberHelper';
	
	import { LangDataHandler } from "./../ts/LangDataHandler";
	import langsData from "./locales/CommentsList.json";

	import { timestampToLocaleFormatedTime } from '../ts/DateTimeHelper';

	import { Comment } from '../ts/CommentsHelper';

	import InfoModal from './modals/InfoModal.vue';
	import { closeModal, openModal } from 'jenesius-vue-modal';
	import { JsonData } from '../ts/JsonHandler';
	import LoaderModal from './modals/LoaderModal.vue';

	import { csrfTokenInput, getNewCsrfToken } from '../ts/csrfTokenHelper';

	const props = defineProps(['articleViewCode', 'comment', 'level']);

	const langData = LangDataHandler.initLangDataHandler("CommentsList", langsData).langData;

	const loading : Ref<boolean> = ref(true);

	const answerStatus = ref(0);

	adminStatusReCheck();

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

	let newSubcommentEditorState = reactive(
	{
		text: "",
		language: LangDataHandler.currentLanguage.value
	});

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

	watch(langData, () =>
	{
		previewState.language = LangDataHandler.currentLanguage.value;
		newSubcommentEditorState.language = LangDataHandler.currentLanguage.value;
	});

	const onCreateNewSubcomment = async () => 
	{
		await getNewCsrfToken();

		if(csrfTokenInput.value == null)
		{
			openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
			return;
		}

		const data = {
			type: 'subcomment',
			parent_comment_id: props.comment.comment_id,
			text: newSubcommentEditorState.text,
			rating_influence: currentSubcommentReaction.value,
			csrfToken: (csrfTokenInput.value as HTMLInputElement).value
		}

		await axios.post('/api/article/comments/new/'+props.articleViewCode, data)
		.then(response => 
		{
			if(response.data.success)
			{

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

	const onCommentDelete = async () => 
	{
		await getNewCsrfToken();

		if(csrfTokenInput.value == null)
		{
			openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
			return;
		}

		const data = {
			'comment_id': props.comment.comment_id,
			csrfToken: (csrfTokenInput.value as HTMLInputElement).value
		}
		await axios.post('/api/admin/article/comments/delete'+props.articleViewCode)
		.then(response => 
		{

		})
		.catch(error => 
		{

		});
	}

	const onCreateAnswer = () => 
	{
		answerStatus.value = 1;
	}
</script>

<template>
	<div class="comment" :style="{ marginLeft: `${5 * level}%` }">
		<div class="comment__header">
			<p class="comment__header__title id">#{{ comment.comment_id }}</p>
			<p class="comment__header__title time">{{ timestampToLocaleFormatedTime(comment.created_date) }}</p>
		</div>
		<MdPreview class="comment__text" :modelValue="comment.text" :language="previewState.language"/>
		<div class="comment__bar">
			<div class="comment__bar__actions">
				<p @click="onCreateAnswer()" class="comment__bar__actions__action">{{ langData['titleAnswer'] }}</p>
				<p @click="onCommentDelete()" v-if="adminStatus" class="comment__bar__actions__action">{{ langData['titleDelete'] }}</p>
			</div>
			<div class="comment__bar__reactions">
				<img src="../assets/img/article/rating.png" alt="Rating: " class="comment__bar__reactions__icon ratingIcon">
				<p class="comment__bar__reactions__title likeCounter">{{ abbreviateNumber(comment['statistics']['rating']) }}</p>
			</div>
		</div>
	</div>
	<div v-if="answerStatus" class="main__article__comments__newSubcomment">
		<MdEditor class="main__article__comments__newSubcomment__editor" v-model="(newSubcommentEditorState.text as string)" @onUploadImg="onNewCommentUploadImg" :language="newSubcommentEditorState.language" noIconfont :preview="false"/>
		<img @click="onCreateNewSubcomment()" src="./../../assets/img/article/sendCommentButton.svg" alt="Send" class="main__article__comments__newSubcomment__sendButton">
		<div class="main__article__comments__newSubcomment__reactions">
			<img v-if="currentSubcommentReaction === 1" @click="onLikeReaction()" src="./../../assets/img/article/comments/likeSelected.svg" alt="Like Selected" class="main__article__comments__newSubcomment__reactions__reaction">
			<img v-else @click="onLikeReaction()" src="./../../assets/img/article/comments/like.svg" alt="Like" class="main__article__comments__newSubcomment__reactions__reaction">
						
			<img v-if="currentSubcommentReaction === 2" @click="onDislikeReaction()" src="./../../assets/img/article/comments/dislikeSelected.svg" alt="Dislike Selected" class="main__article__comments__newSubcomment__reactions__reaction">
			<img v-else @click="onDislikeReaction()" src="./../../assets/img/article/comments/dislike.svg" alt="Dislike" class="main__article__comments__newSubcomment__reactions__reaction">
		</div>
	</div>
	
	<CommentsList v-if="loading" v-for="subcomment in comment.subcomments" :key="subcomment.id" :comment="subcomment" :level="level + 1" :articleViewCode="articleViewCode" />
</template>

<style lang="scss" scoped src="./scss/CommentsList.scss"></style>