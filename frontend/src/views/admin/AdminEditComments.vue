<script setup lang="ts">
	import { ref, Ref, ComputedRef, onMounted, onUnmounted } from 'vue';
	import axios from 'axios';

    import { JsonData } from '../../ts/interfaces/JsonData';

    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

	import VueNumberInput from '@chenfengyuan/vue-number-input';

	import Loader from '../../components/Loader.vue';
	import CommentsList from "./../../components/CommentsList.vue";

	import langsData from "./locales/AdminEditComments.json";
	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
    import { articles } from '../../ts/handlers/ArticlesHandler';
    import { csrfTokenInput, getNewCsrfToken } from '../../ts/handlers/CSRFTokenHandler';
    
    import { dateFormat } from '../../ts/helpers/DateTimeHelper';

    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("AdminEditComments", langsData).langData;

    const loading : Ref<boolean> = ref(true);

    // Filters
    const articlesCountToFetch = ref(10);
    const commentsCountToFetch = ref(20);
    
    const articlesDateBefore : Ref<number> = ref(Date.now() - (1000 * 60 * 60 * 24 * 30));
    const articlesDateAfter : Ref<number> = ref(Date.now());

    const commentsDateBefore : Ref<number> = ref(Date.now() - (1000 * 60 * 60 * 24 * 30));
    const commentsDateAfter : Ref<number> = ref(Date.now());

    const articleRegexPattern : Ref <string> = ref('');
    const commentRegexPattern : Ref <string> = ref('');

    const fetchArticleComments = async () => 
	{
		await getNewCsrfToken();

		if(csrfTokenInput.value == null)
		{
			return;
		}

		const data = {
			csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
			articlesCount: articlesCountToFetch.value,
            commentsCount: commentsCountToFetch.value,
			articlesDateBefore: Math.round(articlesDateBefore.value / 1000),
            articlesDateAfter: Math.round(articlesDateAfter.value / 1000),
			commentsDateBefore: Math.round(commentsDateBefore.value / 1000),
            commentsDateAfter: Math.round(commentsDateAfter.value / 1000),
			articleRegexPattern: articleRegexPattern.value,
            commentRegexPattern: commentRegexPattern.value
		}
		await axios.post('/api/admin/articles/comments/get', data)
		.then(response => 
		{
			if(response.data)
			{
				console.log(response.data);
				
				for (let index = 0; index < response.data.length; index++) 
				{
					articles.value.push(response.data[index]);
				}
			}
		})
		.catch(error =>
		{

		});
		loading.value = false;
	}

	const deleteArticleComments = async () => 
	{
		await getNewCsrfToken();

		if(csrfTokenInput.value == null)
		{
			return;
		}

		const data = {
			csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
			articlesCount: articlesCountToFetch.value,
            commentsCount: commentsCountToFetch.value,
			articlesDateBefore: Math.round(articlesDateBefore.value / 1000),
            articlesDateAfter: Math.round(articlesDateAfter.value / 1000),
			commentsDateBefore: Math.round(commentsDateBefore.value / 1000),
            commentsDateAfter: Math.round(commentsDateAfter.value / 1000),
			articleRegexPattern: articleRegexPattern.value,
            commentRegexPattern: commentRegexPattern.value
		}
		await axios.post('/api/admin/articles/comments/delete/', data)
		.then(response => 
		{
			articles.value = [];
		})
		.catch(error =>
		{

		});
		loading.value = false;
	}

    onMounted(() => 
    {
        loading.value = true;

    });

    onUnmounted(() => 
    {
        articles.value = [];
    });

    const onDeleteSelected = async () => 
	{
		loading.value = true;
		await deleteArticleComments();
	}

	const onApplyFilters = async () => 
	{
		loading.value = true;
		articles.value = [];
		await fetchArticleComments();
	}

    
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
						<VueNumberInput v-model="articlesCountToFetch" :min="1" class="main__filters__blocks__block__content__input number" controls></VueNumberInput>
                        <p class="main__filters__blocks__block__content__text">{{ langData['countTitle2'] }}</p>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['dateArticlePublishTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle1'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText'] as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="articlesDateBefore" teleport-center ></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle2'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText']as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="articlesDateAfter" teleport-center ></VueDatePicker>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['articleContentTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['containingTitle1'] }}</p>
                        <input v-model="articleRegexPattern" type="text" class="main__filters__blocks__block__content__input text">
                    </div>
                </div>
            </div>
            <div class="main__filters__title">{{ langData['filtersCommentsTitle'] }}</div>
            <div class="main__filters__blocks">
				
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['commentsCountTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['countTitle1'] }}</p>
						<VueNumberInput v-model="commentsCountToFetch" :min="1" class="main__filters__blocks__block__content__input number" controls></VueNumberInput>
                        <p class="main__filters__blocks__block__content__text">{{ langData['countTitle2'] }}</p>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['dateCommentSendTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle1'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText'] as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="commentsDateBefore" teleport-center ></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle2'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText']as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="commentsDateAfter" teleport-center ></VueDatePicker>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['commentContentTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['containingTitle1'] }}</p>
                        <input v-model="commentRegexPattern" type="text" class="main__filters__blocks__block__content__input text">
                    </div>
                </div>
            </div>
            <div class="main__filters__buttons">
				<a class="main__filters__buttons__button">{{ langData['applyFiltersButton'] }}</a>
				<a class="main__filters__buttons__button delete">{{ langData['deleteSelectedButton'] }}</a>
			</div>
		</div>
		<div v-if="!loading" class="main__comments">
            <p v-if="articles.length > 0" class="main__comments__title">{{ langData['commentsTitle'] }}</p>
            <p v-else class="main__comments__title">{{ langData['commentsNotFoundTitle'] }}</p>
            <article v-if="articles.length > 0" class="main__comments__articles" v-for="article in articles">
                <p class="main__comments__articles__title">{{ article.statistics.current_title }}</p>
                <div class="main__comments__articles__articleComments">
                    <CommentsList v-for="comment in article.comments" :key="comment.id" :comment="comment" :articleViewCode="article.view_code" :level="0"/>
                </div>
            </article>
		</div>
        <div v-else class="main__comments">
            <Loader />
        </div>
	</main>
    
</template>

<style lang="scss" src="./scss/AdminEditComments.scss"></style>