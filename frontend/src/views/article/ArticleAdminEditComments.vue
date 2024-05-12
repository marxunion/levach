<script setup lang="ts">
	import { ref, reactive, Ref, ComputedRef, onMounted } from 'vue';
	import { useRoute, RouteLocationNormalizedLoaded } from 'vue-router';
	import axios from 'axios';

	import { JsonData } from '../../ts/interfaces/JsonData';
	import { Article } from '../../ts/interfaces/Article';

	import { dateFormat } from '../../ts/helpers/DateTimeHelper';

    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

	import VueNumberInput from '@chenfengyuan/vue-number-input';

	import Loader from '../../components/Loader.vue';
	import CommentsList from "./../../components/CommentsList.vue";

	import langsData from "./locales/ArticleAdminEditComments.json";
	import { csrfTokenInput, getNewCsrfToken } from '../../ts/handlers/CSRFTokenHandler';
	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";

	import { comments } from '../../ts/handlers/CommentsHandler';


    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("ArticleAdminEditComments", langsData).langData;

	const route : RouteLocationNormalizedLoaded = useRoute();

	const loading : Ref<boolean> = ref(true);
	const commentsLoading : Ref<boolean> = ref(true);
	const articleViewCode : Ref<string | null> = ref(null);
	articleViewCode.value = route.params.articleViewCode as string;
	
	const fetchedArticleData : Ref<Article | null> = ref(null);

	async function fetchArticleData()
	{
		await axios.get('/api/article/view/'+articleViewCode.value)
		.then(response =>
		{
			if(response.data.versions)
			{
				fetchedArticleData.value = response.data;
			}
		})
		.catch(error =>
		{
			fetchedArticleData.value = null;
		});

		loading.value = false;
	}

    const dateBefore = ref(Date.now() - (1000 * 60 * 60 * 24 * 30));
	const dateAfter = ref(Date.now());

	// Comments
	const commentsCountToFetch : Ref<number> = ref(20);
	const articleCommentRegexPattern : Ref <string> = ref('');

	const fetchComments = async () => 
	{
		await getNewCsrfToken();

		if(csrfTokenInput.value == null)
		{
			return;
		}

		const data = {
			csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
			count: commentsCountToFetch.value,
			dateBefore: Math.round(dateBefore.value / 1000),
			dateAfter: Math.round(dateAfter.value / 1000),
			regexPattern: articleCommentRegexPattern.value
		}
		await axios.post('/api/admin/article/comments/get/'+articleViewCode.value, data)
		.then(response => 
		{
			if(response.data)
			{
				console.log(response.data);
				
				for (let index = 0; index < response.data.length; index++) 
				{
					comments.value.push(response.data[index]);
				}
			}
		})
		.catch(error =>
		{

		});
		commentsLoading.value = false;
	}

	const deleteComments = async () => 
	{
		await getNewCsrfToken();

		if(csrfTokenInput.value == null)
		{
			return;
		}

		const data = {
			csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
			count: commentsCountToFetch.value,
			dateBefore: Math.round(dateBefore.value / 1000),
			dateAfter: Math.round(dateAfter.value / 1000),
			regexPattern: articleCommentRegexPattern.value
		}
		await axios.post('/api/admin/article/comments/delete/'+articleViewCode.value, data)
		.then(response => 
		{
			comments.value = [];
		})
		.catch(error =>
		{

		});
		commentsLoading.value = false;
	}

	onMounted(async () => 
	{
		commentsLoading.value = true;
		await fetchArticleData();
		if(fetchedArticleData.value != null)
		{
			dateBefore.value = fetchedArticleData.value.versions[fetchedArticleData.value.versions.length-1].created_date * 1000;
			comments.value = [];
			commentsLoading.value = true;
			await fetchComments()
		}
	});

	const onDeleteSelected = async () => 
	{
		commentsLoading.value = true;
		await deleteComments();
	}

	const onApplyFilters = async () => 
	{
		commentsLoading.value = true;
		comments.value = [];
		await fetchComments();
	}
</script>

<template>
	<main v-if="!loading && fetchedArticleData" class="main">
		<div class="main__filters">
			<div class="main__filters__title">{{ langData['filtersTitle'] }}</div>
            <div class="main__filters__blocks">
				
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['commentsCountTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['commentsCountTitle1'] }}</p>
						<VueNumberInput v-model="commentsCountToFetch" :min="1" class="main__filters__blocks__block__content__input number" controls></VueNumberInput>
                        <p class="main__filters__blocks__block__content__text">{{ langData['commentsCountTitle2'] }}</p>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['dateCommentSendTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateCommentSendTitle1'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText'] as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="dateBefore" teleport-center ></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateCommentSendTitle2'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText']as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="dateAfter" teleport-center ></VueDatePicker>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['commentContentTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['commentContentTitle1'] }}</p>
                        <input v-model="articleCommentRegexPattern" type="text" class="main__filters__blocks__block__content__input text">
                    </div>
                </div>
            </div>
			<div class="main__filters__buttons">
				<a @click="onApplyFilters()" class="main__filters__buttons__button">{{ langData['applyFiltersButton'] }}</a>
				<a @click="onDeleteSelected()" class="main__filters__buttons__button delete">{{ langData['deleteSelectedButton'] }}</a>
			</div>
		</div>
		<div v-if="!commentsLoading" class="main__comments">
            <p v-if="comments.length > 0" class="main__comments__title">{{ langData['commentsTitle'] }}</p>
			<p v-else class="main__comments__title">{{ langData['commentsNotFoundTitle'] }}</p>
			<div v-if="comments.length > 0" class="main__comments__commentsList">
				<CommentsList v-for="comment in comments" :key="comment.id" :comment="comment" :level="0" :articleViewCode="articleViewCode"/>
			</div>
		</div>
		<Loader v-else/>
	</main>
	<main v-else-if="!loading" class="main">
		<h1 class="main__title">{{ langData['articleNotFoundTitle'] }}</h1>
	</main>
	<main v-else class="main">
		<Loader />
	</main>
</template>

<style lang="scss" src="./scss/ArticleAdminEditComments.scss"></style>