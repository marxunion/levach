<script setup lang="ts">
	import { ref, computed, reactive, watch } from 'vue';
	import axios from 'axios';

	import DropDown from "./../../components/DropDown.vue";

	import { MdPreview, MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import langsData from "./locales/articleView.json";

	import { LangDataHandler } from "./../../ts/LangDataHandler";
	
	import './../../libs/font_2605852_prouiefeic';

	const langData = LangDataHandler.initLangDataHandler("articleView", langsData).langData;

	const texts = [
		"# Version 1 \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n###",
		
		"# Version 2 \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n### 🤖 Base\n\n**bold**, <u>underline</u>, _italic_, ~~line-through~~, superscript<sup>26</sup>, subscript<sub>1</sub>, `inline code`, [link](https://github.com/imzbf)\n\n> quote: I Have a Dream\n\n1. So even though we face the difficulties of today and tomorrow, I still have a dream.\n2. It is a dream deeply rooted in the American dream.\n3. I have a dream that one day this nation will rise up.\n\n- [ ] Friday\n- [ ] Saturday\n- [x] Sunday\n\n![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)\n\n",
		
		"# Version 3 \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n### 🤖 Base\n\n**bold**, <u>underline</u>, _italic_, ~~line-through~~, superscript<sup>26</sup>, subscript<sub>1</sub>, `inline code`, [link](https://github.com/imzbf)\n\n> quote: I Have a Dream\n\n1. So even though we face the difficulties of today and tomorrow, I still have a dream.\n2. It is a dream deeply rooted in the American dream.\n3. I have a dream that one day this nation will rise up.\n\n- [ ] Friday\n- [ ] Saturday\n- [x] Sunday\n\n![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)\n\n🤗 Code\n\n```vue\n<template>\n  <MdEditor v-model=\"text\" />\n</template>\n\n\<script setup\>\nimport { ref } from 'vue';\nimport { MdEditor } from 'md-editor-v3';\nimport 'md-editor-v3/lib/style.css';\n\nconst text = ref('Hello Editor!');\n\</script\>\n```\n\n## 🖨 Text\n\nThe Old Man and the Sea served to reinvigorate Hemingway's literary reputation and prompted a reexamination of his entire body of work.\n\n## ",
		
		"# Version 4 \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n### 🤖 Base\n\n**bold**, <u>underline</u>, _italic_, ~~line-through~~, superscript<sup>26</sup>, subscript<sub>1</sub>, `inline code`, [link](https://github.com/imzbf)\n\n> quote: I Have a Dream\n\n1. So even though we face the difficulties of today and tomorrow, I still have a dream.\n2. It is a dream deeply rooted in the American dream.\n3. I have a dream that one day this nation will rise up.\n\n- [ ] Friday\n- [ ] Saturday\n- [x] Sunday\n\n![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)\n\n🤗 Code\n\n```vue\n<template>\n  <MdEditor v-model=\"text\" />\n</template>\n\n\<script setup\>\nimport { ref } from 'vue';\nimport { MdEditor } from 'md-editor-v3';\nimport 'md-editor-v3/lib/style.css';\n\nconst text = ref('Hello Editor!');\n\</script\>\n```\n\n## 🖨 Text\n\nThe Old Man and the Sea served to reinvigorate Hemingway's literary reputation and prompted a reexamination of his entire body of work.\n\n## 📈 Table\n\n| nickname | from             |\n| -------- | ---------------- |\n| zhijian  | ChongQing, China |\n\n## 📏 Formula\n\nInline: $x+y^{2x}$\n\n$$\n\\sqrt[3]{x}\n$$\n\n## 🧬 Diagram\n\n```mermaid\nflowchart TD\n  Start --> Stop\n```\n\n## 🪄 Alert\n\n!!! note Supported Types\n\nnote、abstract、info、tip、success、question、warning、failure、danger、bug、example、quote、hint、caution、error、attention\n\n!!!\n\n##"
	]

	
	// Versions
	const currentVersionIdIndex = ref(0);

	let versionsIds = ref([
		1,
		2,
		3,
		4
	].reverse())

	const versionsTexts = computed(
		() => versionsIds.value.map((version) => langData.value['versionText'] as string + version)
	);
	
	const changeVersion = (version : number) => 
	{
		console.log(version);
		
		currentVersionIdIndex.value = version;
	};


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

	
	const text = computed(() => 
	{
		return texts[versionsIds.value[currentVersionIdIndex.value]-1];
	});
	
	// Comments 

	// Sort
	const currentSortType = ref();

	const sortTypes = computed(() => langData.value['sortTypes'] as string[]);

	const onChangeSortType = (version : number) => 
	{
		console.log(version);
		
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
		const res = await Promise.all(
			files.map((file) => 
			{
				return new Promise<{ data: { url: string } }>((rev, rej) => 
				{
					const form = new FormData();
					form.append('file', file);

					axios
					.post('/api/media/img/upload', form, 
					{
						headers: 
						{
							'Content-Type': 'multipart/form-data'
						}
					})
					.then((response) => rev(response))
					.catch((error) => rej(error));
				});
			})
		);

		callback(res.map((item) => item.data.url));
	};
</script>

<template>
	<main class="main">
		<article class="main__article">
			<div class="main__article__previewContainer">
				<p class="main__article__previewContainer__titleTime">10:36  19.09.2022</p>
				<MdPreview class="main__article__previewContainer__preview" :modelValue="text" :language="previewState.language"/>
				<DropDown :options="versionsTexts" :default="versionsTexts[currentVersionIdIndex]" class="main__article__previewContainer__selectVersion" @inputIndex="changeVersion" />
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
					<MdEditor class="main__article__comments__newComment__editor" v-model="(newCommentEditorState.text as string)" @onUploadImg="onNewCommentUploadImg" :language="newCommentEditorState.language" noIconfont/>
					<img src="./../../assets/img/article/sendCommentButton.svg" alt="Send Button" class="main__article__comments__newComment__sendButton">
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