<script setup lang="ts">
	import { ref, defineProps } from 'vue';
	
	const props = defineProps(['comment', 'level']);

	import { abbreviateNumber } from './../ts/AbbreviateNumberHelper';

	import { LangDataHandler } from "./../ts/LangDataHandler";
	import langsData from "./locales/CommentsList.json";

	const langData = LangDataHandler.initLangDataHandler("CommentsList", langsData).langData;
</script>

<template>
	<div class="comment" :style="{ marginLeft: `${5 * level}%` }">
		<p class="comment__titleTime">{{ comment.time }}</p>
		<p class="comment__text">{{ comment.text }}</p>
		<div class="comment__bar">
			<div class="comment__bar__actions">
				<p class="comment__bar__actions__action">{{ langData['titleAnswer'] }}</p>
				<p class="comment__bar__actions__action">{{ langData['titleReport'] }}</p>
			</div>
			<div class="comment__bar__reactions">
				<img src="../assets/img/article/like.svg" alt="Likes: " class="comment__bar__reactions__icon likeIcon">
				<p class="comment__bar__reactions__title likeCounter">{{ abbreviateNumber(comment['statistics']['likes']) }}</p>
				<img src="../assets/img/article/dislike.svg" alt="Dislikes: " class="comment__bar__reactions__icon dislikeIcon">
				<p class="comment__bar__reactions__title dislikeCounter">{{ abbreviateNumber(comment['statistics']['dislikes']) }}</p>
			</div>
		</div>
	</div>
	<CommentsList v-for="subcomment in comment.subcomments" :key="subcomment.id" :comment="subcomment" :level="level + 1" />
</template>

<style lang="scss" scoped src="./scss/CommentsList.scss"></style>