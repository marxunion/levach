<script setup lang="ts">
	import { ref, reactive } from 'vue';
	// import axios from 'axios';

    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

	import CommentsList from "./../../components/CommentsList.vue";

	import langsData from "./locales/articleAdminEdit.json";
	import { LangDataHandler } from "./../../ts/LangDataHandler";

    const langData = LangDataHandler.initLangDataHandler("articleAdminEdit", langsData).langData;

    // Filters
    const dateFilter = reactive(
    {
        before: null,
        after: null
    });


	// Comments
	const comments = ref(
	[
		{
			id: "00000001",
			time: '11:06 19.09.2022',
			text: 'Test Comment1',
			statistics: 
			{
				likes: 48,
				dislikes: 6
			},
			subcomments: [
				{
					id: "00000002",
					time: '12:00 19.09.2022',
					text: 'Test Subcomment1',
					statistics: 
					{
						likes: 32,
						dislikes: 3
					},
					subcomments: [
						{
							id: "00000003",
							time: '13:30 19.09.2022',
							text: 'Test Subsubcomment1',
							statistics: 
							{
								likes: 45,
								dislikes: 2
							},
							subcomments: []
						}
					]
				},
				{
					id: "00000004",
					time: '12:15 19.09.2022',
					text: 'Test Subcomment2',
					statistics: 
					{
						likes: 33,
						dislikes: 3
					},
					subcomments: []
				}
			]
		},
		{
			id: "00000005",
			time: '14:00 19.09.2022',
			text: 'Test Comment2',
			statistics: 
			{
				likes: 48,
				dislikes: 6
			},
			subcomments: []
		}
	]);
</script>

<template>
	<main class="main">
		<div class="main__filters">
            <div class="main__filters__title">{{ langData['filtersTitle'] }}</div>
            <div class="main__filters__blocks">
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['commentsCountTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['commentsCountTitle1'] }}</p>
                        <input type="text" class="main__filters__blocks__block__content__inputNumber">
                        <p class="main__filters__blocks__block__content__text">{{ langData['commentsCountTitle2'] }}</p>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['dateCommentSendTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateCommentSendTitle1'] }}</p>
                        <VueDatePicker :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__inputdate" v-model="dateFilter.before" teleport-center></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateCommentSendTitle2'] }}</p>
                        <VueDatePicker :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__inputdate" v-model="dateFilter.after" teleport-center></VueDatePicker>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['commentContentTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['commentContentTitle1'] }}</p>
                        <input type="text" class="main__filters__blocks__block__content__input">
                    </div>
                </div>
            </div>
		</div>
		<div class="main__comments">
            <p class="main__comments__title">{{ langData['commentsTitle'] }}</p>
			<div class="main__comments__commentsList">
				<CommentsList  v-for="comment in comments" :key="comment.id" :comment="comment" :level="0"/>
			</div>
		</div>
	</main>
</template>

<style lang="scss" scoped src="./scss/articleAdminEdit.scss"></style>