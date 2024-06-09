<script setup lang="ts">
    import { ref, Ref, computed, ComputedRef, reactive, watch, onMounted, onBeforeUnmount, onUnmounted } from 'vue';
    import axios from 'axios';

    import Loader from "./../../components/Loader.vue";

    import { timestampToLocaleFormatedTime } from '../../ts/helpers/DateTimeHelper';
    import { tagsArrayToString } from '../../ts/helpers/TagsHelper';

    import { JsonData } from '../../ts/interfaces/JsonData';
    import { Article } from '../../ts/interfaces/Article';

    import { articleReloading, articles } from '../../ts/handlers/ArticlesHandler';

    import ShareWith from '../../components/modals/ShareWith.vue';
    import DropDown from './../../components/DropDown.vue';
    import DropDownVersion from './../../components/DropDownVersion.vue';

    import { MdPreview, config } from 'md-editor-v3';
    import 'md-editor-v3/lib/style.css';

    import { adminStatus, adminStatusReCheck } from '../../ts/handlers/AdminHandler';

    import { abbreviateNumber, padNumberWithZeroes } from '../../ts/helpers/NumberHelper';

	import { openModal } from "jenesius-vue-modal";
    import InfoModal from "./../../components/modals/InfoModal.vue";

    import langsData from "./locales/Articles.json";
    import { LangDataHandler } from "../../ts/handlers/LangDataHandler";

    import { searchText, searchQuery } from "../../ts/handlers/SearchHandler";

    import './../../libs/font_2605852_prouiefeic';

	import { csrfTokenInput, getNewCsrfToken } from '../../ts/handlers/CSRFTokenHandler';

    import settings from '../../configs/main.json';

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("ArticlesWaitingApprove", langsData).langData;
    
    adminStatusReCheck();

    const lastLoaded : Ref<number> = ref(0);
    const currentSelectedArticleIndex : Ref<number> = ref(0);

    const loading : Ref<boolean> = ref(true);
    const reloading : Ref<boolean> = ref(false);

    const scrollTarget : Ref<HTMLElement | null> = ref(null);

    let searchTitle : string = '';
    let searchTags : string[] = [];

    // Sort
	const currentSortType : Ref<number> = ref(0);

    const sortTypesNames : ComputedRef<string[]>  = computed(() => langData.value['sortTypesNames'] as string[]);


    const onChangeSortType = async (newSortType : number) => 
    {
        loading.value = true;
        articles.value = [];
        lastLoaded.value = 0;

        currentSortType.value = newSortType;

        await fetchNewArticles();
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
        }
    );

	let previewState = reactive(
	{
		language: LangDataHandler.currentLanguage.value
	});

    watch(langData, () =>
	{
		previewState.language = LangDataHandler.currentLanguage.value;
	});

    const parseSearchData = (searchData : string) => 
    {
        if(searchData.length > 0)
        {
            const searchParts : string[] = searchData.split('#');
            searchTitle = searchParts[0].trim();
            searchTags = searchParts.slice(1).map(tag => `${tag.trim()}`);
        }
        else
        {
            searchTitle = '';
            searchTags = [];
        }
    } 

    const fetchNewArticles = async (count : number = 4) => 
    {
        let params;

        if(searchTitle.length > 0 && searchTags.length > 0)
        {
            params = {
                sortType: (langData.value['sortTypes'] as JsonData)[currentSortType.value],
                category: 'ArticlesWaitingApprove',
                count: count,
                lastLoaded: lastLoaded.value,
                searchTitle: searchTitle,
                searchTags: searchTags,
            }
        }
        else
        {
            if(searchTitle.length > 0)
            {
                params = {
                    sortType: (langData.value['sortTypes'] as JsonData)[currentSortType.value],
                    category: 'ArticlesWaitingApprove',
                    count: count,
                    lastLoaded: lastLoaded.value,
                    searchTitle: searchTitle,
                }
            }
            else if(searchTags.length > 0)
            {
                params = {
                    sortType: (langData.value['sortTypes'] as JsonData)[currentSortType.value],
                    category: 'ArticlesWaitingApprove',
                    count: count,
                    lastLoaded: lastLoaded.value,
                    searchTags: searchTags,
                }
            }
            else
            {
                params = {
                    sortType: (langData.value['sortTypes'] as JsonData)[currentSortType.value],
                    category: 'ArticlesWaitingApprove',
                    count: count,
                    lastLoaded: lastLoaded.value
                }
            }
        }

        await axios.get('/api/articles', 
        {
            params: params
        })
        .then(response => 
        {
            if(response.data !== null)
            {
                if(Array.isArray(response.data))
                {
                    response.data.forEach((article : Article) => 
                    {
                        article.currentSelectedVersion = 0;
                        articles.value.push(article);
                        lastLoaded.value++;
                    });
                }
            }
        })
        .catch(error => 
        {
            if(error.response.data.Warning)
            {
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            }
            else if(error.response.data.Error)
            {
				openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
			}
			else if(error.response.data.Critical)
			{
				openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
			}
			else
			{
                openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
			}
        });

        loading.value = false;
        reloading.value = false;
    }

    const handleScroll = async () => 
    {
        const scrollElement : HTMLElement | null = scrollTarget.value;
        if (scrollElement !== null && !loading.value && !reloading.value) 
        {
            const bottomDistance = (scrollElement as HTMLElement).getBoundingClientRect().bottom - window.innerHeight;
            if (bottomDistance <= 0) 
            {
                reloading.value = true;
                await fetchNewArticles();
            }
        }
    }

    watch(articleReloading, async () => 
    {
        loading.value = true;
        articles.value = [];
        lastLoaded.value = 0;
        searchQuery.value = false;
        articleReloading.value = false;

        parseSearchData(searchText.value);
        await fetchNewArticles();
    });

    watch(searchText, async () => 
    {
        if(searchText.value.length == 0)
        {
            loading.value = true;
            articles.value = [];
            lastLoaded.value = 0;
            searchQuery.value = false;

            parseSearchData(searchText.value);
            await fetchNewArticles();
        }
    });

    watch(searchQuery, async () =>
    {
        if(searchQuery.value)
        {
            searchQuery.value = false;
            if(!loading.value)
            {
                loading.value = true;
                articles.value = [];
                lastLoaded.value = 0;

                parseSearchData(searchText.value);
                await fetchNewArticles();
            }
        }
    });
    
    onMounted(async () => 
    {
        articles.value = [];
        let app = document.querySelector('#app');
        if(app != null)
        {
            app.addEventListener('scroll', handleScroll)
        }

        let ps = document.querySelector('.ps');
        if(ps != null)
        {
            ps.addEventListener('scroll', handleScroll)
        }

        loading.value = true;
        parseSearchData(searchText.value);
        await fetchNewArticles();
    });

    onBeforeUnmount(() => 
    {
        let app = document.querySelector('#app');
        if(app != null)
        {
            app.removeEventListener('scroll', handleScroll)
        }
        let ps = document.querySelector('.ps');
        if(ps != null)
        {
            ps.removeEventListener('scroll', handleScroll)
        }
    });
    
    onUnmounted(() => 
    {
        articles.value = [];
    });

    const rejectApproveArticle = async (articleViewCode : string) => 
    {
        if(adminStatus.value)
        {
            await getNewCsrfToken();

            if(csrfTokenInput.value == null)
            {
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                return;
            }

            const data = 
            {
                csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
                status: 0,
            }

            await axios.post('/api/admin/article/approve/' + articleViewCode, data)
            .then(async response =>
            {
                if(response.data.success)
                {
                    openModal(InfoModal, {status: true, text: langData.value['articleRejectApproveSuccessfully']});

                    articles.value = reactive(articles.value.slice(0, currentSelectedArticleIndex.value).concat(articles.value.slice(currentSelectedArticleIndex.value + 1)));
                    if(articles.value.length == 0)
                    {
                        loading.value = true;
                    }
                    else
                    {
                        reloading.value = true;
                    }
                        
                    await fetchNewArticles();
                }
                else
                {
                    if(response.data.Warning)
                    {
                        openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                    }
                    else if(response.data.Error)
                    {
                        openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                    }
                    else if(response.data.Critical)
                    {
                        openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                    }
                    else
                    {
                        openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                    }
                }
            })
            .catch(error => 
            {
                if(error.response.data.Warning)
                {
                    openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                }
                else if(error.response.data.Error)
                {
                    openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
                else if(error.response.data.Critical)
                {
                    openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
                else
                {
                    openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
            });
        }
        else
        {
            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']})
        }
    }

    const onShare = (articleTitle : string, articleViewCode : string) => 
	{
		openModal(ShareWith, { link: "https://" + settings['domainName'] + "/#/article/" + articleViewCode, text: articleTitle })
	}
</script>

<template>
    <main class="main">
        <div class="main__header">
			<p class="main__header__title">{{ (langData['headerTitle'] as JsonData)['ArticlesWaitingApprove'] }}</p>
			<div class="main__header__sort">
				<p class="main__header__sort__title">{{ langData['sortTitle'] }}</p>
				<DropDown :options="sortTypesNames" :default="sortTypesNames[currentSortType]" class="main__header__sort__select" @inputIndex="onChangeSortType" />
			</div>
		</div>
		<article class="main__article" v-if="articles.length > 0 && !loading" v-for="(article, index) in articles" :key="article.id">
            <div class="main__article__block" v-if="article.versions[article.currentSelectedVersion]">
                <a :href="'#/article/>'+article.view_id" target="_blank" class="main__article__titleId">#{{ padNumberWithZeroes(article.view_id) }}</a>
                <p class="main__article__titleTime">{{ timestampToLocaleFormatedTime(article.versions[article.currentSelectedVersion].created_date) }}</p>
                <MdPreview class="main__article__preview" :modelValue="article.versions[article.currentSelectedVersion].text" :language="previewState.language"/>
                <p class="main__article__tags">{{ tagsArrayToString(article.versions[article.currentSelectedVersion].tags) }}</p>

                <div class="main__article__buttons">
                    <a :href="'#/admin/article/approve/'+article.view_code" class="main__article__buttons__button acceptApproveArticleButton">{{ langData['acceptApproveArticleButton'] }}</a>
                    <a @click="currentSelectedArticleIndex = index;rejectApproveArticle(article.view_code)" class="main__article__buttons__button rejectApproveArticleButton">{{ langData['rejectApproveArticleButton'] }}</a>
                    <a :href="'#/article/'+article.view_code" class="main__article__buttons__button readAllButton">{{ langData['readAllButton'] }}</a>
                </div>

                <div class="main__article__reactions">
                    <div class="main__article__reactions__statistics">
                        <img src="../../assets/img/article/rating.png" alt="Rating: " class="main__article__reactions__statistics__icon ratingIcon">
                        <p class="main__article__reactions__statistics__title ratingCounter">{{ abbreviateNumber(article.rating) }}</p>
                        <img @click="onShare(article.versions[article.currentSelectedVersion].title, article.view_code)" src="../../assets/img/article/share.svg" alt="Share..." class="main__article__reactions__statistics__icon shareIcon">
                    </div>
                    <a :href="'#/article/'+article.view_code" class="main__article__reactions__comments">
                        <img src="../../assets/img/article/comment.svg" alt="Comments: " class="main__article__reactions__comments__icon commentIcon">
                        <p class="main__article__reactions__comments__title commentsCounter">{{ abbreviateNumber(article.comments_count) }}</p>
                    </a>
                </div>
                <DropDownVersion
                    :versions="(article as Article).versions"
                    class="main__article__select" 
                    @input="(version : number) => (article as Article).currentSelectedVersion = version" />
            </div>
        </article>
        <h1 v-if="articles.length == 0 && !loading" class="main__article__title">{{ (langData['warnings'] as JsonData)["articlesNotFound"] }}</h1>
        
        <div ref="scrollTarget" style="height: 10px;"></div>
        <div v-if="reloading" class="main__reloader">
            <Loader/>
        </div>
        <Loader v-if="loading" />
    </main>
</template>

<style lang="scss">
.main__article__block
{
    border-radius: 15px;
}
.main__article__preview
{
    max-height: 600px;
    overflow: hidden;
    
    border-radius: 15px;
    * 
    {
        overflow: hidden;
    }
}
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