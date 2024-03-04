<script setup lang="ts">
    import { ref, computed, reactive, watch } from 'vue';
    
    import DropDown from "./../../components/DropDown.vue";

    import { MdPreview, config } from 'md-editor-v3';
    import 'md-editor-v3/lib/style.css';

    import { abbreviateNumber } from './../../ts/AbbreviateNumberHelper';

    import langsData from "./locales/articlesApprovedEditorially.json";
    import { LangDataHandler } from "./../../ts/LangDataHandler";


    import './../../libs/font_2605852_prouiefeic';

	const langData = LangDataHandler.initLangDataHandler("articlesApprovedEditorially", langsData).langData;

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
            title: "Test Approved Editorially Article",

            time: "10:36  19.09.2022",
            tags: "#технологии #айфон #ios",

            versionsIds: 
            [
                5,
                4,
                3,
                2,
                1
            ],
            statistics: 
            {   
                likes: 60,
                dislikes: 11,
                comments: 20
            },
            currentVersionIdIndex: 5,
            currentVersionText: "# Test Approved Editorially Article \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n"
        },
        {
            title: "Test Approved Editorially Article 2",

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
                likes: 48,
                dislikes: 6,
                comments: 4
            },
            currentVersionIdIndex: 3,
            currentVersionText: "# Test Approved Editorially Article 2\n## 🤗 Code\n\n```vue\n<template>\n  <MdEditor v-model=\"text\" />\n</template>\n\n\<script setup\>\nimport { ref } from 'vue';\nimport { MdEditor } from 'md-editor-v3';\nimport 'md-editor-v3/lib/style.css';\n\nconst text = ref('Hello Editor!');\n\</script\>\n```"
        },
        {
            title: "Test Approved Editorially Article 2",

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
                likes: 39,
                dislikes: 12,
                comments: 5
            },
            currentVersionIdIndex: 7,
            currentVersionText: "# Test Approved Editorially Article 3 \n 🖨 Text\n\nThe Old Man and the Sea served to reinvigorate Hemingway's literary reputation and prompted a reexamination of his entire body of work.\n\n## 📈 Table\n\n| nickname | from             |\n| -------- | ---------------- |\n| zhijian  | ChongQing, China |\n\n## 📏 Formula\n\nInline: $x+y^{2x}$\n\n$$\n\\sqrt[3]{x}\n$$\n\n## 🧬 Diagram\n\n```mermaid\nflowchart TD\n  Start --> Stop\n```\n\n## 🪄 Alert\n\n!!! note Supported Types\n\nnote、abstract、info、tip、success、question、warning、failure、danger、bug、example、quote、hint、caution、error、attention\n\n!!!\n\n## ☘️ em..."
        },
        {
            title: "Test Approved Editorially Article 4",

            time: "14:31  20.09.2022",
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
                likes: 39,
                dislikes: 12,
                comments: 5
            },
            currentVersionIdIndex: 7,
            currentVersionText: "# Test Approved Editorially Article 4 \n ![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)"
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
			<p class="main__header__title">{{ langData['headerTitle'] }}</p>
			<div class="main__header__sort">
				<p class="main__header__sort__title">{{ langData['sortTitle'] }}</p>
				<DropDown :options="sortTypes" :default="sortTypes[currentSortType]" class="main__header__sort__select" @inputIndex="onChangeSortType" />
			</div>
		</div>
		<article class="main__article" v-for="article in articles">
            <p class="main__article__titleTime">{{ article['time'] }}</p>
            <MdPreview class="main__article__preview" :modelValue="article['currentVersionText']" :language="previewState.language"/>
            <p class="main__article__tags">{{ article['tags'] }}</p>
            <div class="main__article__buttons">
                <a href="#/article/testarticle" class="main__article__buttons__button">{{ langData['readAllButton'] }}</a>
            </div>
            <div class="main__article__reactions">
                <div class="main__article__reactions__statistics">
                    <img src="../../assets/img/article/like.svg" alt="Likes: " class="main__article__reactions__statistics__icon likeIcon">
                    <p class="main__article__reactions__statistics__title likeCounter">{{ abbreviateNumber(article['statistics']['likes']) }}</p>
                    <img src="../../assets/img/article/dislike.svg" alt="Dislikes: " class="main__article__reactions__statistics__icon dislikeIcon">
                    <p class="main__article__reactions__statistics__title dislikeCounter">{{ abbreviateNumber(article['statistics']['dislikes']) }}</p>
                    <img src="../../assets/img/article/Share.svg" alt="Share..." class="main__article__reactions__statistics__icon shareIcon">
                </div>
                <div class="main__article__reactions__comments">
                    <img src="../../assets/img/article/comment.svg" alt="Comments: " class="main__article__reactions__comments__icon commentIcon">
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

<style lang="scss" scoped src="./scss/articles.scss"></style>
<style lang="scss" scoped src="./scss/articlesApprovedEditorially.scss"></style>