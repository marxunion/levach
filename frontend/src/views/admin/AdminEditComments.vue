<script setup lang="ts">
	import { ref, Ref, ComputedRef, onMounted, onUnmounted } from 'vue';
	import axios from 'axios';

    import { ThemeHandler } from '../../ts/handlers/ThemeHandler';

    import { JsonData } from '../../ts/interfaces/JsonData';
    import { Comment } from '../../ts/interfaces/Comment';

    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

	import VueNumberInput from '@chenfengyuan/vue-number-input';

	import Loader from '../../components/Loader.vue';
	import CommentsList from "./../../components/CommentsList.vue";

	import langsData from "./locales/AdminEditComments.json";
	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
    import { comments } from '../../ts/handlers/CommentsHandler';
    import { csrfTokenInput, getNewCsrfToken } from '../../ts/handlers/CSRFTokenHandler';
    
    import { dateFormat } from '../../ts/helpers/DateTimeHelper';


    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("AdminEditComments", langsData).langData;

    const loading : Ref<boolean> = ref(true);

    // Filters
    const articlesCountToFetch : Ref<number> = ref(10);
    const commentsCountToFetch : Ref<number> = ref(20);
    
    const articleDateBefore : Ref<number> = ref(Date.now() - (1000 * 60 * 60 * 24 * 30));
    const articleDateAfter : Ref<number> = ref(Date.now());

    const commentDateBefore : Ref<number> = ref(Date.now() - (1000 * 60 * 60 * 24 * 30));
    const commentDateAfter : Ref<number> = ref(Date.now());

    const articleRegexPattern : Ref <string> = ref('');
    const commentRegexPattern : Ref <string> = ref('');



    const fetchArticlesComments = async () => 
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
                if(Array.isArray(comments.value))
                {
                    response.data.forEach((comment : Comment) => 
                    {
                        comments.value.push(comment);
                    });
                }
			}
		});
		loading.value = false;
	}

    const refetchArticlesComments = async () =>
    {
        loading.value = true;
		comments.value = [];
		await fetchArticlesComments();
    }

	const deleteArticlesComments = async () => 
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
		.then(() => 
		{
			comments.value = [];
		})
		.catch(() =>
		{

		});
		loading.value = false;
	}

    onMounted(async () => 
    {
        await refetchArticlesComments();
    });

    onUnmounted(() => 
    {
        comments.value = [];
        LangDataHandler.destroyLangDataHandler('AdminEditComments');
    });

    const onCreatedNewSubcomment = async () => 
	{
		await refetchArticlesComments();
	}

	const onDeletedSubcomment = async () => 
	{
		await refetchArticlesComments();
	}

    const onApplyFilters = async () => 
	{
		await refetchArticlesComments();
	}

    const onDeleteSelected = async () => 
	{
		loading.value = true;
		await deleteArticlesComments();
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
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText'] as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="articleDateBefore" teleport-center :dark="ThemeHandler.instance.getCurrentTheme.value == 'dark'"></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle2'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText']as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="articleDateAfter" teleport-center :dark="ThemeHandler.instance.getCurrentTheme.value == 'dark'"></VueDatePicker>
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
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText'] as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="commentDateBefore" teleport-center :dark="ThemeHandler.instance.getCurrentTheme.value == 'dark'" ></VueDatePicker>
                        <p class="main__filters__blocks__block__content__text">{{ langData['dateTitle2'] }}</p>
                        <VueDatePicker :preview-format="dateFormat" :format="dateFormat" :select-text="(langData['dateSelectText']as string)" :cancel-text="(langData['dateCancelText'] as string)" :locale="LangDataHandler.currentLanguage.value.toLowerCase()" class="main__filters__blocks__block__content__input date" v-model="commentDateAfter" teleport-center :dark="ThemeHandler.instance.getCurrentTheme.value == 'dark'"></VueDatePicker>
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
            <p v-if="comments.length > 0" class="main__comments__title">{{ langData['commentsTitle'] }}</p>
			<p v-else class="main__comments__title">{{ langData['commentsNotFoundTitle'] }}</p>
			<div v-if="comments.length > 0" class="main__comments__commentsList">
				<CommentsList @onCreatedNewSubcomment="onCreatedNewSubcomment()" @onDeletedSubcomment="onDeletedSubcomment()" v-for="comment in comments" :key="comment.id" :comment="comment" :level="0" :articleViewCode="encodeURIComponent(`#${comment.id.toString()}`)"/>
			</div>
		</div>
        <Loader v-else/>
	</main>
    
</template>

<style lang="scss" src="./scss/AdminEditComments.scss"></style>