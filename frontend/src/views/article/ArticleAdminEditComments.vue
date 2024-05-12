<script setup lang="ts">
	import { ref, reactive, Ref, ComputedRef, onMounted } from 'vue';
	// import axios from 'axios';

	import { JsonData } from '../../ts/interfaces/JsonData';

    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

	import VueNumberInput from '@chenfengyuan/vue-number-input';

	import Loader from '../../components/Loader.vue';
	import CommentsList from "./../../components/CommentsList.vue";

	import langsData from "./locales/ArticleAdminEditComments.json";
	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";

	import { comments } from '../../ts/handlers/CommentsHandler';

	import axios from 'axios';

    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("ArticleAdminEditComments", langsData).langData;

	const loading : Ref<boolean> = ref(true);
	const articleViewCode : Ref<string | null> = ref(null);

	const fetchedArticleData = ref();


	async function fetchArticleData()
	{
		return await axios.get('/api/article/view/'+articleViewCode.value)
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
	}

    const dateFilter = reactive(
    {
        before: Date.now() - (1000 * 60 * 60 * 24 * 30),
        after: Date.now()
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
	const articlesCountToFetch : Ref<number> = ref(100);

	const fetchComments = async () => 
	{
		await axios.post('api/admin/article/comments/get/'+articleViewCode.value)
		.then(response => 
		{
			if(response.data)
			{
				if(Array.isArray(response.data))
				{
					response.data.forEach(comment => 
					{
						comments.value.push(comment);
					});
				}
			}
		})
		loading.value = false;
	}

	onMounted(async () => 
	{
		loading.value = true;
		await fetchArticleData();
		dateFilter.before = fetchedArticleData.value.created_date;
		comments.value = [];
		await fetchComments()
	});
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
						<VueNumberInput v-model="articlesCountToFetch" :min="1" class="main__filters__blocks__block__content__input number" controls></VueNumberInput>
                        <p class="main__filters__blocks__block__content__text">{{ langData['commentsCountTitle2'] }}</p>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['dateCommentSendTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateCommentSendTitle1'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText'] as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="dateFilter.before" teleport-center ></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateCommentSendTitle2'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText']as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="dateFilter.after" teleport-center ></VueDatePicker>
                    </div>
                </div>
                <div class="main__filters__blocks__block">
                    <p class="main__filters__blocks__block__title">{{ langData['commentContentTitle'] }}</p>
                    <div class="main__filters__blocks__block__content">
                        <p class="main__filters__blocks__block__content__text">{{ langData['commentContentTitle1'] }}</p>
                        <input type="text" class="main__filters__blocks__block__content__input text">
                    </div>
                </div>
            </div>
			<div class="main__filters__buttons">
				<a class="main__filters__buttons__button">{{ langData['applyFiltersButton'] }}</a>
				<a class="main__filters__buttons__button delete">{{ langData['deleteSelectedButton'] }}</a>
			</div>
		</div>
		<div v-if="!loading" class="main__comments">
            <p v-if="comments.length > 0" class="main__comments__title">{{ langData['commentsTitle'] }}</p>
			<p v-else class="main__comments__title">{{ langData['commentsNotFoundTitle'] }}</p>
			<div v-if="comments.length > 0" class="main__comments__commentsList">
				<CommentsList v-for="comment in comments" :key="comment.id" :comment="comment" :level="0" :articleViewCode="articleViewCode"/>
			</div>
		</div>
		<Loader v-else/>
	</main>
</template>

<style lang="scss" src="./scss/ArticleAdminEditComments.scss"></style>