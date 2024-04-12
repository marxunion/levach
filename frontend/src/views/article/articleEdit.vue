<script setup lang="ts">
	import { ref, watch, reactive, Ref, computed } from 'vue';
	import axios from 'axios';

	import DropDown from "./../../components/DropDown.vue";

	import { MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { JsonData } from './../../ts/JsonHandler';

	import { closeModal, openModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
	import InfoModal from "./../../components/modals/InfoModal.vue";
    import InfoModalWithLink from "./../../components/modals/InfoModalWithLink.vue";

	import { abbreviateNumber } from './../../ts/AbbreviateNumberHelper';

	import langsData from "./locales/articleEdit.json";

	import { LangDataHandler } from "./../../ts/LangDataHandler";

	import { StringWithEnds } from "./../../ts/StringWithEnds";

	import './../../libs/font_2605852_prouiefeic';


	const langData = LangDataHandler.initLangDataHandler("articleEdit", langsData).langData;


	//TODO Refactor this code later
	// Statistics
	interface Statistic 
	{
		count: number;
		title: StringWithEnds;
	}

	interface Statistics
	{
		[statisticName: string]: Statistic;
	}

	let statistics = computed(() => 
	{
		const statisticsTemp : Statistics = {};

		(langData.value['statistics'] as JsonData[]).forEach((statistic: JsonData) => 
		{
			statisticsTemp[statistic['statisticName'] as string] = 
			{
				count: 5,
				title: new StringWithEnds(((statistic['data'] as JsonData)["titleWithEnds"]) as JsonData)
			};
		});
		return statisticsTemp;
	});
	
	// Statuses
	let statuses = reactive({
		premoderationStatus: 0,
		acceptedEditoriallyStatus: 0
	})

	const statusesTexts = computed(() => 
		({
			premoderationStatus: ((langData.value['statuses'] as JsonData)['premoderationStatus'] as JsonData)[statuses.premoderationStatus.toString()],
			acceptedEditoriallyStatus: ((langData.value['statuses'] as JsonData)['acceptedEditoriallyStatus'] as JsonData)[statuses.acceptedEditoriallyStatus.toString()]
		})
	);
	


	const texts = 
	[
		"# TestArticle \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n### ",
		
		"# Test Article \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n### 🤖 Base\n\n**bold**, <u>underline</u>, _italic_, ~~line-through~~, superscript<sup>26</sup>, subscript<sub>1</sub>, `inline code`, [link](https://github.com/imzbf)\n\n> quote: I Have a Dream\n\n1. So even though we face the difficulties of today and tomorrow, I still have a dream.\n2. It is a dream deeply rooted in the American dream.\n3. I have a dream that one day this nation will rise up.\n\n- [ ] Friday\n- [ ] Saturday\n- [x] Sunday\n\n![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)\n\n.",
		
		"# Test Article \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n### 🤖 Base\n\n**bold**, <u>underline</u>, _italic_, ~~line-through~~, superscript<sup>26</sup>, subscript<sub>1</sub>, `inline code`, [link](https://github.com/imzbf)\n\n> quote: I Have a Dream\n\n1. So even though we face the difficulties of today and tomorrow, I still have a dream.\n2. It is a dream deeply rooted in the American dream.\n3. I have a dream that one day this nation will rise up.\n\n- [ ] Friday\n- [ ] Saturday\n- [x] Sunday\n\n![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)\n\n🤗 Code\n\n```vue\n<template>\n  <MdEditor v-model=\"text\" />\n</template>\n\n\<script setup\>\nimport { ref } from 'vue';\nimport { MdEditor } from 'md-editor-v3';\nimport 'md-editor-v3/lib/style.css';\n\nconst text = ref('Hello Editor!');\n\</script\>\n```\n\n## 🖨 Text\n\nThe Old Man and the Sea served to reinvigorate Hemingway's literary reputation and prompted a reexamination of his entire body of work.\n\n##",
		
		"# Test Article \n## 😲 md-editor-v3\n\nMarkdown Editor for Vue3, developed in jsx and typescript, support different themes、beautify content by prettier.\n\n### 🤖 Base\n\n**bold**, <u>underline</u>, _italic_, ~~line-through~~, superscript<sup>26</sup>, subscript<sub>1</sub>, `inline code`, [link](https://github.com/imzbf)\n\n> quote: I Have a Dream\n\n1. So even though we face the difficulties of today and tomorrow, I still have a dream.\n2. It is a dream deeply rooted in the American dream.\n3. I have a dream that one day this nation will rise up.\n\n- [ ] Friday\n- [ ] Saturday\n- [x] Sunday\n\n![Picture](https://imzbf.github.io/md-editor-rt/imgs/mark_emoji.gif)\n\n🤗 Code\n\n```vue\n<template>\n  <MdEditor v-model=\"text\" />\n</template>\n\n\<script setup\>\nimport { ref } from 'vue';\nimport { MdEditor } from 'md-editor-v3';\nimport 'md-editor-v3/lib/style.css';\n\nconst text = ref('Hello Editor!');\n\</script\>\n```\n\n## 🖨 Text\n\nThe Old Man and the Sea served to reinvigorate Hemingway's literary reputation and prompted a reexamination of his entire body of work.\n\n## 📈 Table\n\n| nickname | from             |\n| -------- | ---------------- |\n| zhijian  | ChongQing, China |\n\n## 📏 Formula\n\nInline: $x+y^{2x}$\n\n$$\n\\sqrt[3]{x}\n$$\n\n## 🧬 Diagram\n\n```mermaid\nflowchart TD\n  Start --> Stop\n```\n\n## 🪄 Alert\n\n!!! note Supported Types\n\nnote、abstract、info、tip、success、question、warning、failure、danger、bug、example、quote、hint、caution、error、attention\n\n!!!\n\n## ☘️ em..."
	]

	// Versions
	let currentVersionIdIndex = ref(0);

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
		currentVersionIdIndex.value = version;
	};

	// Editor
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

	let editorState = reactive(
	{
		text: texts[versionsIds.value[currentVersionIdIndex.value]-1],
		language: LangDataHandler.currentLanguage.value
	});

	watch(currentVersionIdIndex, () => 
	{
		editorState.text = texts[versionsIds.value[currentVersionIdIndex.value]-1];
	});

	watch(langData, () =>
	{
		//editorState.text = langData.value['editorDefaultText'];
		editorState.language = LangDataHandler.currentLanguage.value;
	});

	const onUploadImg = async (files: File[], callback: (urls: string[]) => void) => 
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
	
	// Tags
	const newTag = ref('');
	const tags : Ref<string[]> = ref([]);

	const addTag = () => 
	{
		if(!tags.value.includes(newTag.value.trim()))
		{
			if (newTag.value.length >= 1 && newTag.value.length <= 40)
			{
				tags.value.push(newTag.value.trim());
				newTag.value = '';
			}
			else
			{
				openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['tagSymbols']});
			}
		}
		else
		{
			openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['tagAlreadyExist']});
		}
	};
	const removeTag = (index: number) => 
	{
		tags.value.splice(index, 1);
	};

	const onSendButton = () =>
	{
		console.log(editorState);
		openModal(InfoModalWithLink, {status: true, text: "Это гибрид анонимного форума и интернет-журнала, предназначенный для анонимного общения в левом политическом дискурсе. Добро пожаловать.", link: "levach.com/article/edit/3238r94y9843ufggevb9yfd8v89df89v8d8989vdf67", text2: "Не забудьте сохранить ссылку, иначе ваша статья будет не доступна к редактированию"})
	}
</script>

<template>
	<main class="main">
		<article class="main__article">
			<div class="main__article__info">
				<div class="main__article__info__statistics">
					<div class="main__article__info__statistics__statistic" v-for="(status, index) in statistics" :key="index">
						<h1 class="main__article__info__statistics__statistic__counter">{{ abbreviateNumber(status.count) }}</h1>
						<p class="main__article__info__statistics__statistic__title">{{ status.title.getStringWithEnd(status.count) }}</p>
					</div>
				</div>
				<div class="main__article__info__statusesContainer">
					<div class="main__article__info__statusesContainer__statuses">
						<div class="main__article__info__statusesContainer__status">
							<p>{{ (langData['statuses'] as JsonData)['premoderationStatusText'] }}</p>
							<img :src="`/src/assets/img/article/statuses/${statuses.premoderationStatus}.svg`" alt="premoderationStatus">
							<p>{{ statusesTexts.premoderationStatus }}</p>
						</div>
						<div class="main__article__info__statusesContainer__status">
							<p>{{ (langData['statuses'] as JsonData)['acceptedEditoriallyStatusText'] }}</p>
							<img :src="`/src/assets/img/article/statuses/${statuses.acceptedEditoriallyStatus}.svg`" alt="acceptedEditoriallyStatus">
							<p>{{ statusesTexts.acceptedEditoriallyStatus }}</p>
						</div>
					</div>
					<DropDown :options="versionsTexts" :default="versionsTexts[currentVersionIdIndex]" class="main__article__info__statusesContainer__selectVersion" @inputIndex="changeVersion"/>
				</div>
			</div>
			<div class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="(editorState.text as string)" @onUploadImg="onUploadImg" :language="editorState.language" :preview="false" noIconfont/>
				<button class="main__article__editorContainer__sendButton" @click="onSendButton">{{ langData['sendButton'] }}</button>	
			</div>
			<div class="main__article__editTags">
				<div class="main__article__editTags__tags__tag" v-for="(tag, index) in tags" :key="index">
					<p class="main__article__editTags__tags__tag__title">{{ tag }}</p>
					<button class="main__article__editTags__tags__tag__button" @click="removeTag(index)"><p>+</p></button>
				</div>
				<div class="main__article__editTags__addTag">
					<input v-model="newTag" class="main__article__editTags__addTag__input" type="text" :placeholder="(langData['addTagPlaceholder'] as string)">
					<button @click="addTag" class="main__article__editTags__addTag__button">+</button>
				</div>
			</div>
		</article>
	</main>
</template>

<style lang="scss" scoped src="./scss/articleEdit.scss"></style>