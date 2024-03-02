<script setup lang="ts">
	import { ref, watch, reactive, Ref, computed } from 'vue';
	import axios from 'axios';

	import DropDown from "./../../components/DropDown.vue";

	import { MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { JsonData } from './../../ts/JsonHandler';

	import { abbreviateNumber } from './../../ts/AbbreviateNumberHelper';

	import langsData from "./locales/articleEdit.json";

	import { LangDataHandler } from "./../../ts/LangDataHandler";

	import { StringWithEnds } from "./../../ts/StringWithEnds";

	const langData = LangDataHandler.initLangDataHandler("articleEdit", langsData).langData;


	//Statistics
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
				count: 0,
				title: new StringWithEnds(((statistic['data'] as JsonData)["titleWithEnds"]) as JsonData)
			};
		});
		return statisticsTemp;
	});
	
	//Statuses
	let statuses = reactive({
		premoderationStatus: 0,
		acceptedEditoriallyStatus: 0
	})

	const statusesTexts = computed(() => ({
		premoderationStatus: ((langData.value['statuses'] as JsonData)['premoderationStatus'] as JsonData)[statuses.premoderationStatus.toString()],
		acceptedEditoriallyStatus: ((langData.value['statuses'] as JsonData)['acceptedEditoriallyStatus'] as JsonData)[statuses.acceptedEditoriallyStatus.toString()]
	}));


	//Versions
	let currentVersion : number = 0;

	let versionsIds = ref([
		1,
		2,
		3,
		4
	])

	const versionsTexts = computed(
		() => versionsIds.value.map((version) => langData.value['versionText'] as string + version)
	);
	
	const changeVersion = (version : string) => 
	{
		const match = version.match(/\d+/);
		return match ? parseInt(match[0]) : 0;
	};

	//Editor
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
		text: langData.value['editorDefaultText'],
		language: LangDataHandler.currentLanguage.value
	});

	watch(langData, () =>
	{
		//state.text = langData.value['editorDefaultText'];
		editorState.language = LangDataHandler.currentLanguage.value;
	});

	const onUploadImg = async (files: File[], callback: (urls: string[]) => void) => 
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

	//Tags
	const newTag = ref('');
	const tags : Ref<string[]> = ref([]);

	const addTag = () => 
	{
		if (newTag.value.length > 0 && newTag.value.length < 40 && !tags.value.includes(newTag.value.trim())) 
		{
			tags.value.push(newTag.value.trim());
			newTag.value = '';
		}
	};
	const removeTag = (index: number) => 
	{
		tags.value.splice(index, 1);
	};
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
							<img :src="`/src/assets/img/statuses/${statuses.premoderationStatus}.svg`" alt="premoderationStatus">
							<p>{{ statusesTexts.premoderationStatus }}</p>
						</div>
						<div class="main__article__info__statusesContainer__status">
							<p>{{ (langData['statuses'] as JsonData)['acceptedEditoriallyStatusText'] }}</p>
							<img :src="`/src/assets/img/statuses/${statuses.acceptedEditoriallyStatus}.svg`" alt="acceptedEditoriallyStatus">
							<p>{{ statusesTexts.acceptedEditoriallyStatus }}</p>
						</div>
					</div>
					<DropDown :options="versionsTexts" class="main__article__info__statusesContainer__selectVersion" @input="changeVersion"/>
				</div>
			</div>
			<div class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="(editorState.text as string)" @onUploadImg="onUploadImg" :language="editorState.language"/>
				<button class="main__article__editorContainer__sendButton">{{ langData['sendButton'] }}</button>	
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