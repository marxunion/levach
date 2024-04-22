<script setup lang="ts">
    import { ref, computed, reactive, watch, onMounted } from 'vue';
    import axios from 'axios';

    import DropDown from "./../components/DropDown.vue";

    import { MdPreview, config } from 'md-editor-v3';
    import 'md-editor-v3/lib/style.css';

    import { JsonData } from './../ts/JsonHandler';

    import { isAdmin } from './../ts/AdminHandler'

    import { abbreviateNumber } from './../ts/AbbreviateNumberHelper';

    import langsData from "./locales/Articles.json";
    import { LangDataHandler } from "./../ts/LangDataHandler";

    import './../libs/font_2605852_prouiefeic';

    defineProps(['currentRoute']);

	const langData = LangDataHandler.initLangDataHandler("articlesEditorially", langsData).langData;

    // Sort
	const currentSortType = ref();

    const sortTypes = computed(() => langData.value['sortTypes'] as string[]);

    const onChangeSortType = (newSortType : number) => 
    {
        currentSortType.value = newSortType;
    };

    // Articles
    const articles = ref([]);

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

    watch(langData, () =>
	{
		previewState.language = LangDataHandler.currentLanguage.value;
	});


    let lastLoadedArticleId = ref(0);
    let lastLoadedArticleTime = ref(0);
    let lastLoadedArticleRate = ref(0);

    onMounted(() => 
    {
        if(currentSortType.value == 0)
        {
            axios.get('/api/articles', {
                params: {
                    sortType: 'time',
                    count: 6,
                    lastLoadedArticleId: lastLoadedArticleId.value,
                    lastLoadedArticleTime: lastLoadedArticleTime.value
                }
            })
            .then(response => 
            {
                
            })
            .catch(response => 
            {

            });
        }
        else
        {
            axios.get('/api/articles', {
                params: {
                    sortType: 'rate',
                    count: 6,
                    lastLoadedArticleId: lastLoadedArticleId.value,
                    lastLoadedArticleRate: lastLoadedArticleRate.value
                }
            })
            .then(response => 
            {
                
            })
            .catch(response => 
            {

            });
        }
    });

</script>

<template>
    <main class="main">
        <div class="main__header">
			<p class="main__header__title">{{ (langData['headerTitle'] as JsonData)[currentRoute || 'editoriallyArticles'] }}</p>
			<div class="main__header__sort">
				<p class="main__header__sort__title">{{ langData['sortTitle'] }}</p>
				<DropDown :options="sortTypes" :default="sortTypes[currentSortType]" class="main__header__sort__select" @inputIndex="onChangeSortType" />
			</div>
		</div>
		<article class="main__article" v-for="article in articles">
            <p class="main__article__titleTime">{{ article['time'] }}</p>
            <MdPreview class="main__article__preview" :modelValue="article['currentVersionText']" :language="previewState.language"/>
            <p class="main__article__tags">{{ article['tags'] }}</p>

            <div v-if="isAdmin && currentRoute == 'articlesWaitingPremoderate'" class="main__article__buttons">
                <a class="main__article__buttons__button premoderateArticleButton">{{ langData['premoderateArticleButton'] }}</a>
                <a class="main__article__buttons__button rejectPremoderateArticleButton">{{ langData['rejectPremoderateArticleButton'] }}</a>
            </div>
            <div v-else-if="isAdmin && currentRoute == 'articlesWaitingApproval'" class="main__article__buttons">
                <a class="main__article__buttons__button approveArticleButton">{{ langData['approveArticleButton'] }}</a>
                <a class="main__article__buttons__button disapproveArticleButton">{{ langData['disapproveArticleButton'] }}</a>
                <a class="main__article__buttons__button readAllButton">{{ langData['readAllButton'] }}</a>
            </div>
            <div v-else class="main__article__buttons oneButton">
                <a href="#/article/testarticle" class="main__article__buttons__button readAllButton">{{ langData['readAllButton'] }}</a>
            </div>
            <div class="main__article__reactions">
                <div class="main__article__reactions__statistics">
                    <img src="../assets/img/article/rating.png" alt="Likes: " class="main__article__reactions__statistics__icon ratingIcon">
                    <p class="main__article__reactions__statistics__title likeCounter">{{ abbreviateNumber(article['statistics']['rating']) }}</p>
                    <img src="../assets/img/article/share.svg" alt="Share..." class="main__article__reactions__statistics__icon shareIcon">
                </div>
                <div class="main__article__reactions__comments">
                    <img src="../assets/img/article/comment.svg" alt="Comments: " class="main__article__reactions__comments__icon commentIcon">
                    <p class="main__article__reactions__comments__title commentsCounter">{{ abbreviateNumber(article['statistics']['comments']) }}</p>
                </div>
            </div>
            <DropDown 
                :options="article['versionsIds'].map((versionsId) => (langData['versionText'] as string) + versionsId)" 
                :default="article['versionsIds'][article['currentVersionIdIndex']]" 
                class="main__article__select" 
                @inputIndex="(version : number) => article['currentVersionIdIndex'] = version" />
        </article>
    </main>
</template>


<style lang="scss">
@media(max-width: 1050px)
{
    .main
    {
        &__header
        {
            &__sort
            {
                &__select
                {
                    & .customSelect__selected
                    {
                        font-size: 18px;
                    }
                    & .customSelect__items
                    {
                        font-size: 18px;
                    }
                }
            }
        }
        
    }
}

@media(max-width: 650px)
{
    .main
    {
        &__header
        {
            &__sort
            {
                &__select
                {
                    & .customSelect__selected
                    {
                        font-size: 17px;
                    }
                    & .customSelect__items
                    {
                        font-size: 17px;
                    }
                }
            }
        }

        &__article
        {
            &__select
            {
                & .customSelect__selected
                {
                    font-size: 17px;
                }
                & .customSelect__items
                {
                    font-size: 17px;
                }
            }
        }
    }
}

@media(max-width: 500px)
{
    .main
    {
        &__header
        {
            &__sort
            {
                &__select
                {
                    & .customSelect__selected
                    {
                        font-size: 16px;
                    }
                    & .customSelect__items
                    {
                        font-size: 16px;
                    }
                }
            }
        }
    }
}
</style>
<style lang="scss" scoped src="./scss/Articles.scss"></style>