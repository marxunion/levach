<script setup lang="ts">
	import { ref, computed, reactive, watch } from 'vue';
	import axios from 'axios';

	import { JsonData } from './../../ts/JsonHandler';

	import DropDown from "./../../components/DropDown.vue";

	import CommentsList from "./../../components/CommentsList.vue";

	import { MdPreview, MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { openModal, closeModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
    import InfoModal from "./../../components/modals/InfoModal.vue";

    import { isAdmin } from './../../ts/AdminHandler'

	import { abbreviateNumber } from './../../ts/AbbreviateNumberHelper';

	import langsData from "./locales/articleView.json";
	import { LangDataHandler } from "./../../ts/LangDataHandler";
	
	import './../../libs/font_2605852_prouiefeic';

	const langData = LangDataHandler.initLangDataHandler("articleView", langsData).langData;

	const articleInfo = 
	reactive({
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
		currentVersionIdIndex: 1,
		statistics: 
        {
            rating: 0,
            comments: 4
        },
		texts: [
			"# Test Editorially Article \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n###",
			
			"# Test Editorially Article \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n### 🤖 Base\n\n**bold**, <u>underline</u>, _italic_, ~~line-through~~, superscript<sup>26</sup>, subscript<sub>1</sub>, `inline code`, [link](https://github.com/imzbf)\n\n> quote: I Have a Dream\n\n1. So even though we face the difficulties of today and tomorrow, I still have a dream.\n2. It is a dream deeply rooted in the American dream.\n3. I have a dream that one day this nation will rise up.\n\n- [ ] Friday\n- [ ] Saturday\n- [x] Sunday\n\n![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)\n\n",
			
			"# Test Editorially Article \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n### 🤖 Base\n\n**bold**, <u>underline</u>, _italic_, ~~line-through~~, superscript<sup>26</sup>, subscript<sub>1</sub>, `inline code`, [link](https://github.com/imzbf)\n\n> quote: I Have a Dream\n\n1. So even though we face the difficulties of today and tomorrow, I still have a dream.\n2. It is a dream deeply rooted in the American dream.\n3. I have a dream that one day this nation will rise up.\n\n- [ ] Friday\n- [ ] Saturday\n- [x] Sunday\n\n![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)\n\n🤗 Code\n\n```vue\n<template>\n  <MdEditor v-model=\"text\" />\n</template>\n\n\<script setup\>\nimport { ref } from 'vue';\nimport { MdEditor } from 'md-editor-v3';\nimport 'md-editor-v3/lib/style.css';\n\nconst text = ref('Hello Editor!');\n\</script\>\n```\n\n## 🖨 Text\n\nThe Old Man and the Sea served to reinvigorate Hemingway's literary reputation and prompted a reexamination of his entire body of work.\n\n## ",
			
			"# Test Editorially Article \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n### 🤖 Base\n\n**bold**, <u>underline</u>, _italic_, ~~line-through~~, superscript<sup>26</sup>, subscript<sub>1</sub>, `inline code`, [link](https://github.com/imzbf)\n\n> quote: I Have a Dream\n\n1. So even though we face the difficulties of today and tomorrow, I still have a dream.\n2. It is a dream deeply rooted in the American dream.\n3. I have a dream that one day this nation will rise up.\n\n- [ ] Friday\n- [ ] Saturday\n- [x] Sunday\n\n![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)\n\n🤗 Code\n\n```vue\n<template>\n  <MdEditor v-model=\"text\" />\n</template>\n\n\<script setup\>\nimport { ref } from 'vue';\nimport { MdEditor } from 'md-editor-v3';\nimport 'md-editor-v3/lib/style.css';\n\nconst text = ref('Hello Editor!');\n\</script\>\n```\n\n## 🖨 Text\n\nThe Old Man and the Sea served to reinvigorate Hemingway's literary reputation and prompted a reexamination of his entire body of work.\n\n## 📈 Table\n\n| nickname | from             |\n| -------- | ---------------- |\n| zhijian  | ChongQing, China |\n\n## 📏 Formula\n\nInline: $x+y^{2x}$\n\n$$\n\\sqrt[3]{x}\n$$\n\n## 🧬 Diagram\n\n```mermaid\nflowchart TD\n  Start --> Stop\n```\n\n## 🪄 Alert\n\n!!! note Supported Types\n\nnote、abstract、info、tip、success、question、warning、failure、danger、bug、example、quote、hint、caution、error、attention\n\n!!!\n\n## ☘️ em..."
		]
	});
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

	
	// Comments 

	// Sort
	const currentSortType = ref();

	const sortTypes = computed(() => langData.value['sortTypes'] as string[]);

	const onChangeSortType = (version : number) => 
	{
		currentSortType.value = version;
	};

	// NewComment
	let newCommentEditorState = reactive(
	{
		text: "",
		language: LangDataHandler.currentLanguage.value
	});

	//TODO Replace this watch to computed? later
	watch(langData, () =>
	{
		previewState.language = LangDataHandler.currentLanguage.value;
		newCommentEditorState.language = LangDataHandler.currentLanguage.value;
	});

	const onNewCommentUploadImg = async (files: File[], callback: (urls: string[]) => void) => 
	{
		if(files.length > 0)
		{
			openModal(LoaderModal);
			const promises = files.map((file) => 
				{
					return new Promise<{ data: { fileName: string } }>((resolve, reject) => 
					{
						const form = new FormData();
						form.append('file', file);

						console.log(form);

						axios.post('/api/media/img/upload', form, 
						{
							headers: 
							{
								'Content-Type': 'multipart/form-data'
							}
						})
						.then((response) => 
						{
							let modalInfoProps;
						
							if (response.data) 
							{
								if(response.data.fileName)
								{
									resolve(response);
								}
								else 
								{
									modalInfoProps = {
										status: false, text: (langData.value['warnings'] as JsonData)["unknown"]
									}
									openModal(InfoModal, modalInfoProps);
									reject(new Error("UnknownError"));
								}
							}
							else
							{
								modalInfoProps = {
									status: false, text: (langData.value['errors'] as JsonData)["unknown"]
								}
								openModal(InfoModal, modalInfoProps);
								reject(new Error("UnknownError"))
							}
						})
						.catch((error) => 
						{
							let modalInfoProps;

							if (error.response.data) 
							{
								if(error.response.data.Warning)
								{
									if(error.response.data.Warning.message == "UploadImage Invalid image type")
									{
										modalInfoProps = {
											status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]
										}
									}
									else if(error.response.data.Warning.message == "UploadImage File size exceeds the maximum allowable file size")
									{
										modalInfoProps = {
											status: false, text: (langData.value['warnings'] as JsonData)["imageMaxSize"]
										}
									}
									else if(error.response.data.Warning.message == "UploadImage Invalid image type")
									{
										modalInfoProps = {
											status: false, text: (langData.value['warnings'] as JsonData)["imageUnallowedType"]
										}
									}
									else
									{
										modalInfoProps = {
											status: false, text: (langData.value['warnings'] as JsonData)["unknown"]
										}
									}
									openModal(InfoModal, modalInfoProps);
								}
								else if(error.response.data.Error)
								{
									modalInfoProps = {
										status: false, text: (langData.value['errors'] as JsonData)["unknown"]
									}
									openModal(InfoModal, modalInfoProps);
									reject(new Error("UnknownError"));
								}
								else if(error.response.data.Critical)
								{
									modalInfoProps = {
										status: false, text: (langData.value['errors'] as JsonData)["unknown"]
									}
									openModal(InfoModal, modalInfoProps);
									reject(new Error("UnknownError"));
								}
								else 
								{
									modalInfoProps = {
										status: false, text: (langData.value['errors'] as JsonData)["unknown"]
									}
									openModal(InfoModal, modalInfoProps);
									reject(new Error("UnknownError"));
								}
							}
							else
							{
								modalInfoProps = {
									status: false, text: (langData.value['errors'] as JsonData)["unknown"]
								}
								openModal(InfoModal, modalInfoProps);
								reject(new Error("UnknownError"))
							}
						});
					});
				}
			);

			const res = await Promise.all(promises);
			
			const successfulResults = res.filter(item => item !== null);

			closeModal();
			callback(successfulResults.map((item) => '/api/media/img/'+item.data.fileName));
		}
	};



	// Comments
	const comments = ref(
	[
		{
			id: "00000001",
			time: '11:06 19.09.2022',
			text: 'Test Comment1',
			statistics: 
			{
				rating: 0
			},
			subcomments: [
				{
					id: "00000002",
					time: '12:00 19.09.2022',
					text: 'Test Subcomment1',
					statistics: 
					{
						rating: 0
					},
					subcomments: [
						{
							id: "00000003",
							time: '13:30 19.09.2022',
							text: 'Test Subsubcomment1',
							statistics: 
							{
								rating: 0
							},
							subcomments: []
						}
					]
				},
				{
					id: "00000004",
					time: '12:15 19.09.2022',
					text: '# Test Subcomment2\n',
					statistics: 
					{
						rating: 0
					},
					subcomments: []
				}
			]
		},
		{
			id: "00000005",
			time: '14:00 19.09.2022',
			text: 'Test Comment2',
			statistics: 
			{
				rating: 0
			},
			subcomments: []
		}
	]);
</script>

<template>
	<main class="main">
		<article class="main__article">
			<div class="main__article__previewContainer">
				<p class="main__article__previewContainer__titleTime">{{ articleInfo['time'] }}</p>
				<MdPreview class="main__article__previewContainer__preview" :modelValue="articleInfo.texts[articleInfo.versionsIds[articleInfo.currentVersionIdIndex]-1]" :language="previewState.language"/>
				<p class="main__article__previewContainer__tags">{{ articleInfo['tags'] }}</p>
				<div v-if="isAdmin" class="main__article__previewContainer__buttons oneButton">
					<a class="main__article__previewContainer__buttons__button deleteArticleButton">{{ langData['deleteArticleButton'] }}</a>
				</div>
				<div class="main__article__previewContainer__reactions">
					<div class="main__article__previewContainer__reactions__statistics">
						<img src="../../assets/img/article/like.svg" alt="Rating: " class="main__article__previewContainer__reactions__statistics__icon likeIcon">
						<p class="main__article__previewContainer__reactions__statistics__title likeCounter">{{ abbreviateNumber(articleInfo['statistics']['rating']) }}</p>
						<img src="../../assets/img/article/share.svg" alt="Share..." class="main__article__previewContainer__reactions__statistics__icon shareIcon">
					</div>
					<div class="main__article__previewContainer__reactions__comments">
						<img src="../../assets/img/article/comment.svg" alt="Comments: " class="main__article__previewContainer__reactions__comments__icon commentIcon">
						<p class="main__article__previewContainer__reactions__comments__title commentsCounter">{{ abbreviateNumber(articleInfo['statistics']['comments']) }}</p>
					</div>
				</div>
				
				<DropDown 
				:options="articleInfo.versionsIds.map((versionsId) => (langData['versionText'] as string) + versionsId)" 
				:default="(langData['versionText'] as string) + articleInfo.versionsIds[articleInfo.currentVersionIdIndex]" 
				class="main__article__previewContainer__selectVersion" 
				@inputIndex="(version : number) => articleInfo['currentVersionIdIndex'] = version" />
			</div>
			<div class="main__article__comments">
				<div class="main__article__comments__header">
					<p class="main__article__comments__header__title">{{ langData['commentsTitle'] }}</p>
					<div class="main__article__comments__header__sort">
						<p class="main__article__comments__header__sort__title">{{ langData['sortTitle'] }}</p>
						<DropDown :options="sortTypes" :default="sortTypes[currentSortType]" class="main__article__comments__header__sort__selectSortType" @inputIndex="onChangeSortType" />
					</div>
				</div>

				<div class="main__article__comments__newComment">
					<MdEditor class="main__article__comments__newComment__editor" v-model="(newCommentEditorState.text as string)" @onUploadImg="onNewCommentUploadImg" :language="newCommentEditorState.language" noIconfont :preview="false"/>
					<img src="./../../assets/img/article/sendCommentButton.svg" alt="Send Button" class="main__article__comments__newComment__sendButton">
				</div>
				
				<div class="main__article__comments__commentsList">
					<CommentsList  v-for="comment in comments" :key="comment.id" :comment="comment" :level="0"/>
				</div>
			</div>
		</article>
	</main>
</template>


<style lang="css">
	.main__article__comments__newComment__editor .md-editor-footer
    {
		display: none;
	}
</style>
<style lang="scss" scoped src="./scss/articleView.scss"></style>