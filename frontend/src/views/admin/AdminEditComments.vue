<script setup lang="ts">
	import { ref, Ref, ComputedRef, onMounted, onUnmounted } from 'vue';
	import axios from 'axios';

    import { JsonData } from '../../ts/interfaces/JsonData';
    import { Article } from '../../ts/interfaces/Article';

    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

	import VueNumberInput from '@chenfengyuan/vue-number-input';

	import Loader from '../../components/Loader.vue';
	import CommentsList from "./../../components/CommentsList.vue";

	import langsData from "./locales/AdminEditComments.json";
	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
    import { articles } from '../../ts/handlers/ArticlesHandler';
    import { csrfTokenInput, getNewCsrfToken } from '../../ts/handlers/CSRFTokenHandler';
    
    import { dateFormat, timestampToLocaleFormatedTime } from '../../ts/helpers/DateTimeHelper';


    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("AdminEditComments", langsData).langData;

    const loading : Ref<boolean> = ref(true);

    // Filters
    const articlesCountToFetch = ref(10);
    const commentsCountToFetch = ref(20);
    
    const articleDateBefore : Ref<number> = ref(Date.now() - (1000 * 60 * 60 * 24 * 30));
    const articleDateAfter : Ref<number> = ref(Date.now());

    const commentDateBefore : Ref<number> = ref(Date.now() - (1000 * 60 * 60 * 24 * 30));
    const commentDateAfter : Ref<number> = ref(Date.now());

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
			articleDateBefore: Math.round(articleDateBefore.value / 1000),
            articleDateAfter: Math.round(articleDateAfter.value / 1000),
			commentDateBefore: Math.round(commentDateBefore.value / 1000),
            commentDateAfter: Math.round(commentDateAfter.value / 1000),
			articleRegexPattern: articleRegexPattern.value,
            commentRegexPattern: commentRegexPattern.value
		}
		await axios.post('/api/admin/articles/comments/get', data)
		.then(response => 
		{
			if(response.data)
			{
                if(Array.isArray(articles.value))
                {
                    response.data.forEach((article : Article) => 
                    {
                        articles.value.push(article);
                    });
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
			articleDateBefore: Math.round(articleDateBefore.value / 1000),
            articleDateAfter: Math.round(articleDateAfter.value / 1000),
			commentDateBefore: Math.round(commentDateBefore.value / 1000),
            commentDateAfter: Math.round(commentDateAfter.value / 1000),
			articleRegexPattern: articleRegexPattern.value,
            commentRegexPattern: commentRegexPattern.value
		}
		await axios.post('/api/admin/articles/comments/delete', data)
		.then(response => 
		{
			articles.value = [];
		})
		.catch(error =>
		{

		});
		loading.value = false;
	}

    onMounted(async () => 
    {
        loading.value = true;
        articles.value = [];
        await fetchArticleComments();
    });

    onUnmounted(() => 
    {
        articles.value = [];
    });

    const onApplyFilters = async () => 
	{
		loading.value = true;
		articles.value = [];
		await fetchArticleComments();
	}

    const onDeleteSelected = async () => 
	{
		loading.value = true;
		await deleteArticleComments();
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
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText'] as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="articleDateBefore" teleport-center ></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle2'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText']as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="articleDateAfter" teleport-center ></VueDatePicker>
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
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText'] as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="commentDateBefore" teleport-center ></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle2'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText']as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="commentDateAfter" teleport-center ></VueDatePicker>
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
				<a @click="onApplyFilters()" class="main__filters__buttons__button">{{ langData['applyFiltersButton'] }}</a>
				<a @click="onDeleteSelected()" class="main__filters__buttons__button delete">{{ langData['deleteSelectedButton'] }}</a>
			</div>
		</div>
		<div v-if="!loading" class="main__comments">
            <p v-if="articles.length > 0" class="main__comments__title">{{ langData['commentsTitle'] }}</p>
            <p v-else class="main__comments__title">{{ langData['commentsNotFoundTitle'] }}</p>
            <article v-if="articles.length > 0" class="main__comments__articles" v-for="article in articles">
                <p class="main__comments__articles__title time"> {{ timestampToLocaleFormatedTime(article.statistics.created_date) }}</p>
                <a :href="'#/article/'+article.view_code" target="_blank" class="main__comments__articles__title text">{{ article.statistics.current_title }}</a>

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