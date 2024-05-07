<script setup lang="ts">
    import { ref, computed, reactive, watch, onMounted, onBeforeUnmount } from 'vue';
    import axios from 'axios';

    import Loader from "./../../components/Loader.vue";
    import { timestampToLocaleFormatedTime } from './../../ts/DateTimeHelper';
    import { tagsArrayToString } from './../../ts/TagsHelper'
    import { JsonData } from './../../ts/JsonHandler';

    import { Article, articles } from '../../ts/ArticlesHelper';

    import DropDown from './../../components/DropDown.vue';
    import DropDownVersion from './../../components/DropDownVersion.vue';

    import { MdPreview, config } from 'md-editor-v3';
    import 'md-editor-v3/lib/style.css';
    
    import { adminStatus, adminStatusReCheck } from './../../ts/AdminHandler';

    import { abbreviateNumber } from './../../ts/AbbreviateNumberHelper';

	import { openModal } from "jenesius-vue-modal";
    import InfoModal from "./../../components/modals/InfoModal.vue";

    import langsData from "./locales/Articles.json";
    import { LangDataHandler } from "./../../ts/LangDataHandler";
    
    import './../../libs/font_2605852_prouiefeic';

    import { csrfTokenInput, getNewCsrfToken } from '../../ts/csrfTokenHelper';

	const langData = LangDataHandler.initLangDataHandler("EditoriallyArticles", langsData).langData;

    // Sort
	const currentSortType = ref(0);
    const currentSelectedArticleIndex = ref(0);

    const sortTypesNames = computed(() => langData.value['sortTypesNames'] as string[]);

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
    const lastLoadedArticleCreatedDate = ref(2147483645);
    const lastLoadedArticleRate = ref(2147483645);

    const reloading = ref(false);
    const loading = ref(true);
    const scrollTarget = ref(null);

    const onChangeSortType = async (newSortType : number) => 
    {
        articles.value = reactive([]);
        lastLoadedArticleId.value = 2147483645;
        
        if(currentSortType.value === 0)
        {
            lastLoadedArticleCreatedDate.value = 2147483645;
        }
        else
        {
            lastLoadedArticleRate.value = 2147483645;
        }
        currentSortType.value = newSortType;

        loading.value = false;
        await fetchNewArticles();
    }

    adminStatusReCheck();

    const fetchNewArticles = async () => 
    {
        if(currentSortType.value === 0)
        {
            await axios.get('/api/articles', 
            {
                params: 
                {
                    sortType: (langData.value['sortTypes'] as JsonData)[currentSortType.value],
                    category: 'EditoriallyArticles',
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
                            articles.value.push(article as Article);
                        });
                    }
                }
            })
            .catch(error => 
            {
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']})
            });
        }
        else
        {
            await axios.get('/api/articles', 
            {
                params: 
                {
                    sortType: (langData.value['sortTypes'] as JsonData)[currentSortType.value],
                    category: 'EditoriallyArticles',
                    count: 4,
                    lastLoadedArticleId: lastLoadedArticleId.value,
                    lastLoadedArticleCreatedDate: lastLoadedArticleCreatedDate.value
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
                            lastLoadedArticleCreatedDate.value = article.statistics.created_date;
                            article.currentSelectedVersion = article.versions.length;
                            articles.value.push(article);
                        });
                    }
                }
            })
            .catch(error => 
            {
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']})
            });
        }
        loading.value = false;
        reloading.value = false;
    }

    const handleScroll = async () => 
    {
        const scrollElement = scrollTarget.value;
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
    
    const deleteArticle = async (articleViewCode : string) => 
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

            await axios.post('/api/admin/article/delete/' + articleViewCode, data)
            .then(async response =>
            {
                if(response.data.success)
                {
                    await openModal(InfoModal, {status: true, text: langData.value['articleDeletedSuccessfully']});

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

    onMounted(async () => 
    {
        articles.value = [];
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
			<p class="main__header__title">{{ (langData['headerTitle'] as JsonData)['EditoriallyArticles'] }}</p>
			<div class="main__header__sort">
				<p class="main__header__sort__title">{{ langData['sortTitle'] }}</p>
				<DropDown :options="sortTypesNames" :default="sortTypesNames[currentSortType]" class="main__header__sort__select" @inputIndex="onChangeSortType" />
			</div>
		</div>
		<article class="main__article" v-if="articles.length > 0 && !loading" v-for="(article, index) in articles" :key="article.id">
            <div class="main__article__block" v-if="article.versions[article.currentSelectedVersion-1]">
                <p class="main__article__titleTime">{{ timestampToLocaleFormatedTime(article.versions[article.currentSelectedVersion-1].created_date) }}</p>
                <MdPreview class="main__article__preview" :modelValue="article.versions[article.currentSelectedVersion-1].text" :language="previewState.language"/>
                <p class="main__article__tags">{{ tagsArrayToString(article.versions[article.currentSelectedVersion-1].tags) }}</p>

                <div v-if="adminStatus" class="main__article__buttons">
                    <a @click="currentSelectedArticleIndex = index;deleteArticle(article.view_code)" class="main__article__buttons__button deleteArticleButton">{{ langData['deleteArticleButton'] }}</a>
                    <a :href="'#/article/'+article.view_code" class="main__article__buttons__button readAllButton">{{ langData['readAllButton'] }}</a>
                </div>
                <div v-else class="main__article__buttons oneButton">
                    <a :href="'#/article/'+article.view_code" target="_blank" class="main__article__buttons__button readAllButton">{{ langData['readAllButton'] }}</a>
                </div>
                
                <div class="main__article__reactions">
                    <div class="main__article__reactions__statistics">
                        <img src="../../assets/img/article/rating.png" alt="Rating: " class="main__article__reactions__statistics__icon ratingIcon">
                        <p class="main__article__reactions__statistics__title ratingCounter">{{ abbreviateNumber(article.statistics.rating) }}</p>
                        <img src="../../assets/img/article/share.svg" alt="Share..." class="main__article__reactions__statistics__icon shareIcon">
                    </div>
                    <div class="main__article__reactions__comments">
                        <img src="../../assets/img/article/comment.svg" alt="Comments: " class="main__article__reactions__comments__icon commentIcon">
                        <p class="main__article__reactions__comments__title commentsCounter">{{ abbreviateNumber(article.statistics.comments) }}</p>
                    </div>
                </div>
                <DropDownVersion
                    :max-version="(article as Article).versions.length"
                    class="main__article__select" 
                    @input="(version : number) => (article as Article).currentSelectedVersion = version" />
            </div>
        </article>
        <h1 v-if="articles.length == 0 && !loading" class="main__article__title">{{ (langData['warnings'] as JsonData)["articlesNotFound"] }}</h1>
        
        <div ref="scrollTarget" style="height: 100px;"></div>
        <Loader v-if="loading || reloading" />
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