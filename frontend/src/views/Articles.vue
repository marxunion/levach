<script setup lang="ts">
    import { ref, computed, reactive, watch, onMounted, onBeforeUnmount } from 'vue';
    import { useRoute } from 'vue-router';

    import axios from 'axios';

    import Loader from "./../components/Loader.vue";
    import { timestampToLocaleFormatedTime } from './../ts/DateTimeHelper';
    import { tagsArrayToString } from './../ts/TagsHelper'
    import { JsonData } from './../ts/JsonHandler';

    import { Article } from './../ts/ArticleHelper';

    import DropDown from './../components/DropDown.vue';
    import DropDownVersion from './../components/DropDownVersion.vue';

    import { MdPreview, config } from 'md-editor-v3';
    import 'md-editor-v3/lib/style.css';

    import { adminStatus, adminStatusReCheck } from './../ts/AdminHandler';

    import { abbreviateNumber } from './../ts/AbbreviateNumberHelper';

	import { openModal } from "jenesius-vue-modal";
    import InfoModal from "./../components/modals/InfoModal.vue";

    import langsData from "./locales/Articles.json";
    import { LangDataHandler } from "./../ts/LangDataHandler";

    import { searchText } from "../ts/searchHelper";

    import './../libs/font_2605852_prouiefeic';

    const route = useRoute();
    const props = defineProps(['currentRoute']);

	const langData = LangDataHandler.initLangDataHandler("articlesEditorially", langsData).langData;

    // Sort
	const currentSortType = ref(0);

    const sortTypes = computed(() => langData.value['sortTypes'] as string[]);

    if(props.currentRoute == 'articlesSearch')
    {
        const searchData : string = route.params.searchData as string;

        if(searchData != null)
        {
            if(searchData.length > 0)
            {
                searchText.value = searchData;
            }
        }
    }

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

    const lastLoadedArticleId = ref(2147483645);
    const lastLoadedArticleCreatedAt = ref(2147483645);
    const lastLoadedArticleRate = ref(2147483645);

    const loading = ref(false);
    const scrollTarget = ref(null);
    let articles : Array<Article> = reactive([]);

    const onChangeSortType = async (newSortType : number) => 
    {
        articles = reactive([]);
        lastLoadedArticleId.value = 2147483645;
        
        if(currentSortType.value === 0)
        {
            lastLoadedArticleCreatedAt.value = 2147483645;
        }
        else
        {
            lastLoadedArticleRate.value = 2147483645;
        }
        currentSortType.value = newSortType;
        await fetchNewArticles();
    };

    adminStatusReCheck();

    const fetchNewArticles = async () => 
    {
        loading.value = true;
        if(currentSortType.value === 0)
        {
            if(props.currentRoute == 'articlesSearch')
            {
                const searchData : string = route.params.searchData as string;
                
                if(searchData != null)
                {
                    if(searchData.length > 0)
                    {
                        const searchParts : string[] = searchData.split('#');
                        const searchTitle : string = searchParts[0].trim();
                        const searchTags : string[] = searchParts.slice(1).map(tag => `${tag.trim()}`);
                        
                        let data;

                        console.log(props.currentRoute);
                        
                        if(searchTags.length > 0)
                        {
                            data = {
                                sortType: (langData.value['sortTypesNames'] as JsonData)[currentSortType.value],
                                category: props.currentRoute,
                                count: 4,
                                lastLoadedArticleId: lastLoadedArticleId.value,
                                lastLoadedArticleRate: lastLoadedArticleRate.value,
                                searchTitle: searchTitle,
                                searchTags: searchTags
                            }
                        }
                        else
                        {
                            data = {
                                sortType: (langData.value['sortTypesNames'] as JsonData)[currentSortType.value],
                                category: props.currentRoute,
                                count: 4,
                                lastLoadedArticleId: lastLoadedArticleId.value,
                                lastLoadedArticleRate: lastLoadedArticleRate.value,
                                searchTitle: searchTitle
                            }
                        }

                        await axios.get('/api/articles', 
                        {
                            params: data
                        })
                        .then(response => 
                        {
                            if(response.data !== null)
                            {
                                if(Array.isArray(response.data))
                                {
                                    response.data.forEach((article : Article) => 
                                    {
                                        if(lastLoadedArticleId.value > article.id)
                                        {
                                            lastLoadedArticleId.value = article.id;
                                        }
                                        lastLoadedArticleRate.value = article.statistics.rating;
                                        article.currentSelectedVersion = article.versions.length;
                                        articles.push(article as Article);
                                    });
                                }
                            }
                        })
                        .catch(response => 
                        {
                            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']})
                        });
                    }
                }
            }
            else
            {
                await axios.get('/api/articles', 
                {
                    params: 
                    {
                        sortType: (langData.value['sortTypesNames'] as JsonData)[currentSortType.value],
                        category: props.currentRoute,
                        count: 4,
                        lastLoadedArticleId: lastLoadedArticleId.value,
                        lastLoadedArticleRate: lastLoadedArticleRate.value
                    }
                })
                .then(response => 
                {
                    if(response.data !== null)
                    {
                        if(Array.isArray(response.data))
                        {
                            response.data.forEach((article : Article) => 
                            {
                                if(lastLoadedArticleId.value > article.id)
                                {
                                    lastLoadedArticleId.value = article.id;
                                }
                                lastLoadedArticleRate.value = article.statistics.rating;
                                article.currentSelectedVersion = article.versions.length;
                                articles.push(article as Article);
                            });
                        }
                    }
                })
                .catch(response => 
                {
                    openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']})
                });
            }
            
        }
        else
        {
            await axios.get('/api/articles', 
            {
                params: 
                {
                    sortType: (langData.value['sortTypesNames'] as JsonData)[currentSortType.value],
                    category: props.currentRoute,
                    count: 4,
                    lastLoadedArticleId: lastLoadedArticleId.value,
                    lastLoadedArticleCreatedAt: lastLoadedArticleCreatedAt.value
                }
            })
            .then(response => 
            {
                if(response.data !== null)
                {
                    if(Array.isArray(response.data))
                    {
                        response.data.forEach((article : Article) => 
                        {
                            if(lastLoadedArticleId.value > article.id)
                            {
                                lastLoadedArticleId.value = article.id;
                            }
                            lastLoadedArticleCreatedAt.value = article.statistics.created_at;
                            article.currentSelectedVersion = article.versions.length;
                            articles.push(article);
                        });
                    }
                }
            })
            .catch(response => 
            {
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']})
            });
        }
        loading.value = false;
    };

    const handleScroll = async () => 
    {
        const scrollElement = scrollTarget.value;
        if (scrollElement !== null && !loading.value) 
        {
            const bottomDistance = (scrollElement as HTMLElement).getBoundingClientRect().bottom - window.innerHeight;
            if (bottomDistance <= 0) 
            {
                loading.value = true;
                await fetchNewArticles();
            }
        }
    };
    
    onMounted(async () => 
    {
        let ps = document.querySelector('.ps');
        if(ps != null)
        {
            ps.addEventListener('scroll', handleScroll)
        }
        loading.value = true;
        await fetchNewArticles();
    });

    onBeforeUnmount(() => 
    {
        let ps = document.querySelector('.ps');
        if(ps != null)
        {
            ps.removeEventListener('scroll', handleScroll)
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
		<article class="main__article" v-if="articles.length > 0 && !loading" v-for="article in articles">
            <p class="main__article__titleTime">{{ timestampToLocaleFormatedTime(article.versions[article.currentSelectedVersion-1].date) }}</p>
            <MdPreview class="main__article__preview" :modelValue="article.versions[article.currentSelectedVersion-1].text" :language="previewState.language"/>
            <p class="main__article__tags">{{ tagsArrayToString(article.versions[article.currentSelectedVersion-1].tags) }}</p>

            <div v-if="adminStatus && currentRoute == 'articlesWaitingPremoderate'" class="main__article__buttons">
                <a class="main__article__buttons__button premoderateArticleButton">{{ langData['premoderateArticleButton'] }}</a>
                <a class="main__article__buttons__button rejectPremoderateArticleButton">{{ langData['rejectPremoderateArticleButton'] }}</a>
            </div>
            <div v-else-if="adminStatus && currentRoute == 'articlesWaitingApproval'" class="main__article__buttons">
                <a class="main__article__buttons__button approveArticleButton">{{ langData['approveArticleButton'] }}</a>
                <a class="main__article__buttons__button disapproveArticleButton">{{ langData['disapproveArticleButton'] }}</a>
                <a class="main__article__buttons__button readAllButton">{{ langData['readAllButton'] }}</a>
            </div>
            <div v-else class="main__article__buttons oneButton">
                <a :href="'#/article/'+article.view_code" target="_blank" class="main__article__buttons__button readAllButton">{{ langData['readAllButton'] }}</a>
            </div>
            <div class="main__article__reactions">
                <div class="main__article__reactions__statistics">
                    <img src="../assets/img/article/rating.png" alt="Rating: " class="main__article__reactions__statistics__icon ratingIcon">
                    <p class="main__article__reactions__statistics__title ratingCounter">{{ abbreviateNumber(article.statistics.rating) }}</p>
                    <img src="../assets/img/article/share.svg" alt="Share..." class="main__article__reactions__statistics__icon shareIcon">
                </div>
                <div class="main__article__reactions__comments">
                    <img src="../assets/img/article/comment.svg" alt="Comments: " class="main__article__reactions__comments__icon commentIcon">
                    <p class="main__article__reactions__comments__title commentsCounter">{{ abbreviateNumber(article.statistics.comments) }}</p>
                </div>
            </div>
            <DropDownVersion
                :max-version="(article as Article).versions.length"
                class="main__article__select" 
                @inputIndex="(version : number) => (article as Article).currentSelectedVersion = version" />
        </article>
        <h1 v-if="articles.length == 0 && !loading" class="main__article__title">{{ (langData['warnings'] as JsonData)["articlesNotFound"] }}</h1>
        
        <div ref="scrollTarget" style="height: 100px;"></div>
        <Loader v-if="loading" />
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