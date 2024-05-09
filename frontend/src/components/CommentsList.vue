<script setup lang="ts">
	import { ref, Ref, reactive, defineProps } from 'vue';

	import { MdPreview, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { adminStatus, adminStatusReCheck } from './../ts/AdminHandler'

	import { abbreviateNumber } from './../ts/AbbreviateNumberHelper';
	
	import { LangDataHandler } from "./../ts/LangDataHandler";
	import langsData from "./locales/CommentsList.json";

	import { timestampToLocaleFormatedTime } from '../ts/DateTimeHelper';

	import { Comment } from '../ts/CommentsHelper';

	const props = defineProps(['articleViewCode', 'comment', 'level']);

	const langData = LangDataHandler.initLangDataHandler("CommentsList", langsData).langData;

	const loading : Ref<boolean> = ref(true);

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

	const onNewSubComment = () => 
	{

	}

	const onCommentDelete = (commentId : number) => 
	{

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
				<p class="comment__bar__actions__action">{{ langData['titleAnswer'] }}</p>
				<p v-if="adminStatus" class="comment__bar__actions__action">{{ langData['titleDelete'] }}</p>
			</div>
			<div class="comment__bar__reactions">
				<img src="../assets/img/article/rating.png" alt="Rating: " class="comment__bar__reactions__icon ratingIcon">
				<p class="comment__bar__reactions__title likeCounter">{{ abbreviateNumber(comment['statistics']['rating']) }}</p>
			</div>
		</div>
	</div>
	<CommentsList v-for="subcomment in comment.subcomments" :key="subcomment.id" :comment="subcomment" :level="level + 1" :articleViewCode="articleViewCode" />
</template>

<style lang="scss" scoped src="./scss/CommentsList.scss"></style>