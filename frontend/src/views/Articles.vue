<script setup lang="ts">
    import { ref, computed, reactive, watch } from 'vue';
    
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

    const onChangeSortType = (version : number) => 
    {
        currentSortType.value = version;
    };

    // Articles
    const articles = ref([
        {
            title: "Test Editorially Article",

            time: "10:36  19.09.2022",
            tags: "#технологии #айфон #ios",
            versionsIds: 
            [
                4,
                3,
                2,
                1
            ],
            currentVersionIdIndex: 5,
            statistics: 
            {   
                likes: 1200,
                dislikes: 110,
                comments: 210
            },
            
            currentVersionText: "# Test Editorially Article \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n"
        },
        {
            title: "Test Editorially Article 2",

            time: "08:32  21.09.2022",
            tags: "#технологии #айфон #ios",
            versionsIds: 
            [
                3,
                2,
                1
            ],
            statistics: 
            {
                likes: 4800,
                dislikes: 600,
                comments: 40
            },
            currentVersionIdIndex: 3,
            currentVersionText: "# Test Editorially Article 2\n## 🤗 Code\n\n```vue\n<template>\n  <MdEditor v-model=\"text\" />\n</template>\n\n\<script setup\>\nimport { ref } from 'vue';\nimport { MdEditor } from 'md-editor-v3';\nimport 'md-editor-v3/lib/style.css';\n\nconst text = ref('Hello Editor!');\n\</script\>\n```"
        },
        {
            title: "Test Editorially Article 3",

            time: "01:33  20.09.2022",
            tags: "#технологии #айфон #ios",
            versionsIds: 
            [
                7,
                6,
                5,
                4,
                3,
                2,
                1
            ],
            statistics: 
            {
                likes: 39000000,
                dislikes: 120000,
                comments: 50000
            },
            currentVersionIdIndex: 7,
            currentVersionText: "# Test Editorially Article 3 \n 🖨 Text\n\nThe Old Man and the Sea served to reinvigorate Hemingway's literary reputation and prompted a reexamination of his entire body of work.\n\n## 📈 Table\n\n| nickname | from             |\n| -------- | ---------------- |\n| zhijian  | ChongQing, China |\n\n## 📏 Formula\n\nInline: $x+y^{2x}$\n\n$$\n\\sqrt[3]{x}\n$$\n\n## 🧬 Diagram\n\n```mermaid\nflowchart TD\n  Start --> Stop\n```\n\n## 🪄 Alert\n\n!!! note Supported Types\n\nnote、abstract、info、tip、success、question、warning、failure、danger、bug、example、quote、hint、caution、error、attention\n\n!!!\n\n## ☘️ em..."
        },
        {
            title: "Test Editorially Article 4",

            time: "14:31  20.09.2022",
            tags: "#технологии #айфон #ios",
            versionsIds: 
            [
                6,
                5,
                4,
                3,
                2,
                1
            ],
            statistics: 
            {
                likes: 39000,
                dislikes: 1200,
                comments: 500
            },
            currentVersionIdIndex: 7,
            currentVersionText: "# Test Editorially Article 4 \n ![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)"
        }
    ]);

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
                    <img src="../assets/img/article/like.svg" alt="Likes: " class="main__article__reactions__statistics__icon likeIcon">
                    <p class="main__article__reactions__statistics__title likeCounter">{{ abbreviateNumber(article['statistics']['likes']) }}</p>
                    <img src="../assets/img/article/dislike.svg" alt="Dislikes: " class="main__article__reactions__statistics__icon dislikeIcon">
                    <p class="main__article__reactions__statistics__title dislikeCounter">{{ abbreviateNumber(article['statistics']['dislikes']) }}</p>
                    <img src="../assets/img/article/Share.svg" alt="Share..." class="main__article__reactions__statistics__icon shareIcon">
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