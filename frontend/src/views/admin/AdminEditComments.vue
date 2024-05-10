<script setup lang="ts">
	import { ref, reactive } from 'vue';
	// import axios from 'axios';

    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

	import VueNumberInput from '@chenfengyuan/vue-number-input';

	import CommentsList from "./../../components/CommentsList.vue";

	import langsData from "./locales/AdminEditComments.json";
	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";

    const langData = LangDataHandler.initLangDataHandler("AdminEditComments", langsData).langData;

    // Filters
    const dateFilter = reactive(
    {
        before: null,
        after: null
    });

    const dateFilter2 = reactive(
    {
        before: null,
        after: null
    });
	const dateFormat = (date : Date) => 
	{
		const day = ("0" + date.getDate()).slice(-2);
		const month = ("0" + (date.getMonth() + 1)).slice(-2);
		const year = date.getFullYear();

        const hours = ("0" + (date.getHours())).slice(-2);
		const minutes = ("0" + (date.getMinutes())).slice(-2);

		return `${hours}:${minutes} ${day}.${month}.${year}`;
	}

	// Comments
	const articles = ref(
        [
            {
                title: "Test Article 1",
                comments: 
                [
                    {
                        id: "00000001",
                        time: '11:06 19.09.2022',
                        text: 'Test Comment1',
                        statistics: 
                        {
                            rating: 44
                        },
                        subcomments: [
                            {
                                id: "00000002",
                                time: '12:00 19.09.2022',
                                text: 'Test Subcomment1',
                                statistics: 
                                {
                                    rating: 44
                                },
                                subcomments: [
                                    {
                                        id: "00000003",
                                        time: '13:30 19.09.2022',
                                        text: 'Test Subsubcomment1',
                                        statistics: 
                                        {
                                            rating: 44
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
                                    rating: 44
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
                            rating: 44
                        },
                        subcomments: []
                    }
                ]
            },
            {
                title: "Test Article 2",
                comments: 
                [
                    {
                        id: "00000001",
                        time: '11:06 19.09.2022',
                        text: 'Test Comment1',
                        statistics: 
                        {
                            rating: 44
                        },
                        subcomments: [
                            {
                                id: "00000002",
                                time: '12:00 19.09.2022',
                                text: 'Test Subcomment1',
                                statistics: 
                                {
                                    rating: 44
                                },
                                subcomments: [
                                    {
                                        id: "00000003",
                                        time: '13:30 19.09.2022',
                                        text: 'Test Subsubcomment1',
                                        statistics: 
                                        {
                                            rating: 44
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
                                    rating: 44
                                },
                                subcomments: []
                            }
                        ]
                    }
                ]
            },
            {
            title: "Test Article 3",
            comments: 
            [
                {
                    id: "00000001",
                    time: '11:06 19.09.2022',
                    text: 'Test Comment1',
                    statistics: 
                    {
                        rating: 44
                    },
                    subcomments: [
                        {
                            id: "00000002",
                            time: '12:00 19.09.2022',
                            text: 'Test Subcomment1',
                            statistics: 
                            {
                                rating: 44
                            },
                            subcomments: [
                                {
                                    id: "00000003",
                                    time: '13:30 19.09.2022',
                                    text: 'Test Subsubcomment1',
                                    statistics: 
                                    {
                                        rating: 44
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
                                rating: 44
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
                        rating: 44
                    },
                    subcomments: []
                },
                {
                    id: "00000006",
                    time: '14:00 19.09.2022',
                    text: 'Test Comment2',
                    statistics: 
                    {
                        rating: 44
                    },
                    subcomments: []
                }
	        ]
            }
        ]
    );
	const value = ref(1);
    const value2 = ref(1);
</script>

<template>
	<main class="main">
		<div class="main__filters">
            <div class="main__filters__title">{{ langData['filtersArticleTitle'] }}</div>
            <div class="main__filters__blocks">
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['articlesCountTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['countTitle1'] }}</p>
						<VueNumberInput v-model="value" :min="1" class="main__filters__blocks__block__content__input number" controls></VueNumberInput>
                        <p class="main__filters__blocks__block__content__text">{{ langData['countTitle2'] }}</p>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['dateArticlePublishTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle1'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText'] as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="dateFilter.before" teleport-center ></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle2'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText']as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="dateFilter.after" teleport-center ></VueDatePicker>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['articleContentTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['containingTitle1'] }}</p>
                        <input type="text" class="main__filters__blocks__block__content__input text">
                    </div>
                </div>
            </div>
            <div class="main__filters__title">{{ langData['filtersCommentsTitle'] }}</div>
            <div class="main__filters__blocks">
				
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['commentsCountTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['countTitle1'] }}</p>
						<VueNumberInput v-model="value2" :min="1" class="main__filters__blocks__block__content__input number" controls></VueNumberInput>
                        <p class="main__filters__blocks__block__content__text">{{ langData['countTitle2'] }}</p>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['dateCommentSendTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle1'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText'] as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="dateFilter2.before" teleport-center ></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle2'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText']as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="dateFilter2.after" teleport-center ></VueDatePicker>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['commentContentTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['containingTitle1'] }}</p>
                        <input type="text" class="main__filters__blocks__block__content__input text">
                    </div>
                </div>
            </div>
            <div class="main__filters__buttons">
				<a class="main__filters__buttons__button">{{ langData['applyFiltersButton'] }}</a>
				<a class="main__filters__buttons__button delete">{{ langData['deleteSelectedButton'] }}</a>
			</div>
		</div>
		<div class="main__comments">
            <p class="main__comments__title">{{ langData['commentsTitle'] }}</p>
            <article class="main__comments__articles" v-for="article in articles">
                <p class="main__comments__articles__title">{{ article.title }}</p>
                <div class="main__comments__articles__articleComments">
                    <CommentsList  v-for="comment in article.comments" :key="comment.id" :comment="comment" :level="0"/>
                </div>
            </article>
		</div>
	</main>
    
</template>

<style lang="scss" src="./scss/AdminEditComments.scss"></style>