<script setup lang="ts">
	import { ref, computed, reactive, watch, onMounted } from 'vue';
	import { useRoute } from 'vue-router';
	import axios from 'axios';

	import { timestampToLocaleFormatedTime } from './../../ts/DateTimeHelper';
	import { tagsArrayToString } from './../../ts/TagsHelper'
	import { JsonData } from './../../ts/JsonHandler';

	import Loader from "./../../components/Loader.vue";

	import DropDown from '../../components/DropDown.vue';
	import DropDownVersion from "./../../components/DropDownVersion.vue";

	import CommentsList from "./../../components/CommentsList.vue";

	import { MdPreview, MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { openModal, closeModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
    import InfoModal from "./../../components/modals/InfoModal.vue";

    import { adminStatus, adminStatusReCheck } from './../../ts/AdminHandler'

	import { abbreviateNumber } from './../../ts/AbbreviateNumberHelper';

	import langsData from "./locales/articleView.json";
	import { LangDataHandler } from "./../../ts/LangDataHandler";
	
	import './../../libs/font_2605852_prouiefeic';

	const langData = LangDataHandler.initLangDataHandler("articleView", langsData).langData;

	const fetchedData = ref()
	const loaded = ref(false);

	let currentVersion = ref(1);

	const route = useRoute();
	const articleViewCode = ref<string | null>(null);

	articleViewCode.value = route.params.articleViewCode as string;

	adminStatusReCheck();

	async function fetchData()
	{
		return await axios.get('/api/article/view/'+articleViewCode.value)
		.then(response =>
		{
			if(response.data.versions)
			{
				return response.data;
			}
			else
			{
				if(response.data.Warning)
				{
					return null;
				}
				else if(response.data.Error)
				{
					if(response.data.Error.message == "Article not found")
					{
						return null;
					}
					else
					{
						return null;
					}
				}
				else if(response.data.Critical)
				{
					return null;
				}
				else
				{
					return null;
				}
			}
		})
		.catch(response =>
		{
			if(response.data.Warning)
			{
				return null;
			}
			else if(response.data.Error)
			{
				if(response.data.Error.message == "Article not found")
				{
					return null;
				}
				else
				{
					return null;
				}
			}
			else if(response.data.Critical)
			{
				return null;
			}
			else
			{
				return null;
			}
		});
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

						axios.post('/api/media/img/upload', form, 
						{
							headers: 
							{
								'Content-Type': 'multipart/form-data'
							}
						})
						.then((response) => 
						{
						
							if (response.data) 
							{
								if(response.data.fileName)
								{
									resolve(response);
								}
								else 
								{
									openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["unknown"] });
									(new Error("UnknownError"));
								}
							}
							else
							{
								openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"] });

							}
						})
						.catch((error) => 
						{
							if (error.response.data) 
							{
								if(error.response.data.Warning)
								{
									if(error.response.data.Warning.message == "UploadImage Invalid image type")
									{
										openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"] });
									}
									else if(error.response.data.Warning.message == "UploadImage File size exceeds the maximum allowable file size")
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageMaxSize"] as string + error.response.data.Warning.params.max_upload_filesize_mb + (langData.value['warnings'] as JsonData)["imageMaxSizeSymbol"] as string});
									}
									else if(error.response.data.Warning.message == "UploadImage Invalid image type")
									{
										openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["imageUnallowedType"] });
									}
									else
									{
										openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)["unknown"] });
									}
									
								}
								else if(error.response.data.Error)
								{
									openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"] });
								}
								else if(error.response.data.Critical)
								{
									openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"] });
								}
								else 
								{
									openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"] });
								}
							}
							else
							{
								openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)["unknown"] });
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

	onMounted(async function()
	{
		try 
		{
			fetchedData.value = await fetchData();
			if(fetchedData.value != null)
			{
				currentVersion.value = fetchedData.value.versions.length;
				setInterval(async () => 
				{
					fetchedData.value = await fetchData();
				}, 10000);
			}
			loaded.value = true;
		}
		catch
		{
			loaded.value = true;
			fetchedData.value = null;
		}
	});
</script>

<template>
	<main v-if="loaded" class="main">
		<article v-if="fetchedData" class="main__article">
			<div class="main__article__previewContainer">
				<p class="main__article__previewContainer__titleTime">{{ timestampToLocaleFormatedTime(fetchedData.versions[currentVersion-1].date) }}</p>
				<MdPreview class="main__article__previewContainer__preview" :modelValue="fetchedData.versions[currentVersion-1].text" :language="previewState.language"/>
				<p class="main__article__previewContainer__tags">{{ tagsArrayToString(fetchedData.versions[currentVersion-1].tags) }}</p>
				<div v-if="adminStatus" class="main__article__previewContainer__buttons oneButton">
					<a class="main__article__previewContainer__buttons__button deleteArticleButton">{{ langData['deleteArticleButton'] }}</a>
				</div>
				<div class="main__article__previewContainer__reactions">
					<div class="main__article__previewContainer__reactions__statistics">
						<img src="../../assets/img/article/rating.png" alt="Rating: " class="main__article__previewContainer__reactions__statistics__icon ratingIcon">
						<p class="main__article__previewContainer__reactions__statistics__title likeCounter">{{ abbreviateNumber(fetchedData.statistics.rating) }}</p>
						<img src="../../assets/img/article/share.svg" alt="Share..." class="main__article__previewContainer__reactions__statistics__icon shareIcon">
					</div>
					<div class="main__article__previewContainer__reactions__comments">
						<img src="../../assets/img/article/comment.svg" alt="Comments: " class="main__article__previewContainer__reactions__comments__icon commentIcon">
						<p class="main__article__previewContainer__reactions__comments__title commentsCounter">{{ abbreviateNumber(fetchedData.statistics.comments) }}</p>
					</div>
				</div>
				
				<DropDownVersion
				:max-version="fetchedData.versions.length"
				class="main__article__previewContainer__selectVersion" 
				@input="(version : number) => currentVersion = version" />
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
					<CommentsList v-for="comment in comments" :key="comment.id" :comment="comment" :level="0"/>
				</div>
			</div>
		</article>
		<article v-else class="main__article">
			<h1 class="main__article__title">{{ (langData['errors'] as JsonData)['articleNotFound'] }}</h1>
		</article>
	</main>
	<main v-else class="main">
		<Loader/>
	</main>
</template>

<style lang="css">
	.main__article__comments__newComment__editor .md-editor-footer
    {
		display: none;
	}
</style>
<style lang="scss" scoped src="./scss/articleView.scss"></style>