<script setup lang="ts">
	import { ref, watch, Ref, ComputedRef, onMounted } from 'vue';
	import { useRoute, useRouter, Router, RouteLocationNormalizedLoaded } from 'vue-router';
	import axios from 'axios';

	import { MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { JsonData } from '../../ts/interfaces/JsonData';

	import Loader from "./../../components/Loader.vue";

	import { closeModal, openModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
	import InfoModal from "./../../components/modals/InfoModal.vue";
    import InfoModalWithLink from "./../../components/modals/InfoModalWithLink.vue";

	import langsData from "./locales/ArticleAdminApprove.json";

	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";

	import './../../libs/font_2605852_prouiefeic';

	import { timestampToLocaleFormatedTime } from '../../ts/helpers/DateTimeHelper';

	import { csrfTokenInput, getNewCsrfToken } from '../../ts/handlers/CSRFTokenHandler';

    import { arraysAreEqual } from '../../ts/helpers/ArrayHelper';

    import { Article } from '../../ts/interfaces/Article';

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("ArticleAdminApprove", langsData).langData;

    const currentChangesStatus : Ref<number> = ref(0);

	const route : RouteLocationNormalizedLoaded = useRoute();
    const router : Router = useRouter();
	const articleViewCode : Ref<string | null> = ref(null);

	articleViewCode.value = route.params.articleViewCode as string;

    let articleText : Ref<string> = ref('');

	let fetchedArticleData : Ref<Article | null> = ref(null);
	let loading : Ref<boolean> = ref(true);

    let newTag : Ref<string> = ref('');
	let tags : Ref<string[]> = ref([]);
    
    async function fetchData()
	{
		await getNewCsrfToken();

        if(csrfTokenInput.value == null)
        {
            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            return;
        }

        const data = 
        {
            csrfToken: (csrfTokenInput.value as HTMLInputElement).value
        }
		
		await axios.post('/api/admin/article/approve/preload/'+articleViewCode.value, data)
		.then(response =>
		{
			if(response.data)
			{
				fetchedArticleData.value = response.data;

                if(fetchedArticleData.value)
                {
                    if(fetchedArticleData.value.statistics.current_tags == null)
                    {
                        fetchedArticleData.value.statistics.current_tags = [];
                    }
                    Object.assign(tags.value, fetchedArticleData.value.statistics.current_tags);

                    articleText.value = fetchedArticleData.value.statistics.current_text;
                }
			}
			else
			{
				if(response.data.Warning)
				{
					fetchedArticleData.value = null;
				}
				else if(response.data.Error)
				{
					fetchedArticleData.value = null;
				}
				else if(response.data.Critical)
				{
					fetchedArticleData.value = null;
				}
				else
				{
					fetchedArticleData.value = null;
				}
			}
            loading.value = false;
		})
		.catch(error =>
		{
			if(error.response.data.Warning)
			{
				fetchedArticleData.value = null;
			}
			else if(error.response.data.Error)
			{
				fetchedArticleData.value = null;
			}
			else if(error.response.data.Critical)
			{
				fetchedArticleData.value = null;
			}
			else
			{
				fetchedArticleData.value = null;
			}
		});
	}

    const checkChanges = async () =>
    {
        await setTimeout(() => 
        {
            
        }, 1500);
        if(fetchedArticleData.value)
        {
            if(articleText.value != fetchedArticleData.value.statistics.current_text || !arraysAreEqual(tags.value, fetchedArticleData.value.statistics.current_tags))
            {
                currentChangesStatus.value = 1;
                return true;
            }
            else
            {
                currentChangesStatus.value = 0;
                return false;
            }
        }
    }
	
	const addTag = () => 
	{
		if(!tags.value.includes(newTag.value.split(" ").join("")))
		{
			if (newTag.value.length >= 1 && newTag.value.length <= 40)
			{
				tags.value.push(newTag.value.split(" ").join(""));
				newTag.value = '';
                checkChanges();
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
	}
	const removeTag = (index: number) => 
	{
		tags.value.splice(index, 1);
        checkChanges();
	}

	const onSendButton = async () =>
	{
        if(await checkChanges())
        {
            const contentParts = (articleText.value as string).split('\n');

            if(contentParts.length >= 1) 
            {
                const title = contentParts[0];
                if(title.substring(0, 2) == '# ') 
                {
                    if(title.length >= 5 && title.length <= 120) 
                    {
                        if(contentParts.length >= 2) 
                        {
                            const content = contentParts.slice(1).join('\n');
                            if(content.length >= 25) 
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
                                    status: 2,
                                    text: articleText.value, 
                                    tags: tags.value
                                }

                                await axios.post('/api/admin/article/approve/'+articleViewCode.value, data)
                                .then(async response => 
                                {
                                    if(response.data.success)
                                    {
                                        const modal = await openModal(InfoModal, {status: true, text: langData.value['articleApprovedSuccessfullyWithChanges']});
                                    
                                        modal.onclose = function()
                                        {
                                            router.push("/admin/articles/waitingApproval");
                                        }
                                    }
                                    else
                                    {
                                        if(response.data.Warning)
                                        {
                                            if(response.data.Warning.message == "Please add a title for the article")
                                            {
                                                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle']});
                                            }
                                            else if(response.data.Warning.message == 'Article has duplicated tags')
                                            {
                                                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleDuplicatedTags']});
                                            }
                                            else if(response.data.Warning.message == "Title must contain between 5 and 120 characters")
                                            {
                                                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols']});
                                            }
                                            else if(response.data.Warning.message == "Please add content for the article")
                                            {
                                                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent']});
                                            }
                                            else if(response.data.Warning.message == "The content of the article must be more than 25 characters")
                                            {
                                                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols']});
                                            }
                                            else
                                            {
                                                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                                            }
                                        }
                                        else if(response.data.Error)
                                        {
                                            if(response.data.Error.message == "Article for approve not found")
                                            {
                                                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleForApproveNotFound']});
                                            }
                                            else if(response.data.Error.message == "Please make changes for edit")
                                            {
                                                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedChanges']});
                                            }
                                            else
                                            {
                                                openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                                            }
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
                                        if(error.response.data.Warning.message == "Please add a title for the article")
                                        {
                                            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle']})
                                        }
                                        else if(error.response.data.Warning.message == "Wait for a timeout to re-edit the article")
                                        {
                                            openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)['editTimeoutToDate'] as string).replace('{date}', timestampToLocaleFormatedTime(error.response.data.Warning.params['edit_timeout_to_date']))})
                                        }
                                        else if(error.response.data.Warning.message == "Title must contain between 5 and 120 characters")
                                        {
                                            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols']})
                                        }
                                        else if(error.response.data.Warning.message == "Please add content for the article")
                                        {
                                            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent']})
                                        }
                                        else if(error.response.data.Warning.message == "The content of the article must be more than 25 characters")
                                        {
                                            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols']})
                                        }
                                        else
                                        {
                                            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                                        }
                                    }
                                    else if(error.response.data.Error)
                                    {
                                        if(error.response.data.Error.message == "Article for approve not found")
                                        {
                                            openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['articleForApproveNotFound']})
                                        }
                                        else if(error.response.data.Error.message == "Please make changes for edit")
                                        {
                                            openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['articleNeedChanges']});
                                        }
                                        else
                                        {
                                            openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                                        }
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
                                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols']})
                            }
                        }
                        else
                        {
                            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent']})
                        }
                    }
                    else
                    {
                        openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols']})
                    }
                }
                else
                {
                    openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle']})
                }
            }
            else
            {
                openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle']})
            }	
        }
        else
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
                status: 1,
            }

            await axios.post('/api/admin/article/approve/'+articleViewCode.value, data)
            .then(async response => 
            {
                if(response.data.success)
                {
                    const modal = await openModal(InfoModalWithLink, {status: true, text: langData.value['articleApprovedSuccessfully'], link: window.location.hostname + "/article/edit/" + articleViewCode.value, text2: (langData.value['warnings'] as JsonData)['articleEditLinkCopyWarning']});
                        
                    modal.onclose = function()
                    {
                        router.push("/admin/articles/waitingApproval");
                    }
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
            })
        }
	}

    const onUploadImg = async (files: File[], callback: (urls: string[]) => void) => 
	{
		if(files.length > 0)
		{
			openModal(LoaderModal);
			const promises = files.map((file) => 
				{
					return new Promise<{ data: { fileName: string } }>(resolve => 
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
									openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["unknown"]});
								}
							}
							else
							{
								if(response.data.Warning)
                                {
                                    if(response.data.Warning.message == "Invalid image type")
                                    {
                                        openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]});
                                    }
                                    else if(response.data.Warning.message == "File size exceeds the maximum allowable file size")
                                    {
                                        openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)["imageMaxSize"] as string).replace('{size}', response.data.Warning.params.max_upload_filesize_mb)});
                                    }
                                    else if(response.data.Warning.message == "Invalid image type")
                                    {
                                        openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageUnallowedType"]});
                                    }
                                    else
                                    {
                                        openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["unknown"]});
                                    }
                                }
                                else if(response.data.Error)
                                {
                                    openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
                                }
                                else if(response.data.Critical)
                                {
                                    openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
                                }
                                else 
                                {
                                    openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
                                }
							}
						})
						.catch((error) => 
						{
							if (error.response.data) 
							{
								if(error.response.data.Warning)
								{
									if(error.response.data.Warning.message == "Invalid image type")
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]});
									}
									else if(error.response.data.Warning.message == "File size exceeds the maximum allowable file size")
									{
										openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)["imageMaxSize"] as string).replace('{size}', error.response.data.Warning.params.max_upload_filesize_mb)});
									}
									else if(error.response.data.Warning.message == "Invalid image type")
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageUnallowedType"]});
									}
									else
									{
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["unknown"]})
									}
								}
								else if(error.response.data.Error)
								{
                                    openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
								}
								else if(error.response.data.Critical)
								{
									openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
								}
								else 
								{
									openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
								}
							}
							else
							{
								openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
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
	}

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

    watch(() => articleText.value, () => 
    {
        checkChanges();
    });

	onMounted(async function()
	{
        loading.value = true;
		await fetchData();
	});
</script>

<template>
	<main v-if="!loading" class="main">
		<article v-if="fetchedArticleData" class="main__article">
			<div class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="articleText" @onChange="checkChanges" @onUploadImg="onUploadImg" :language="LangDataHandler.currentLanguage.value" :preview="false" noIconfont/>
				<button class="main__article__editorContainer__sendButton" @click="onSendButton">{{ (langData['buttonTitles'] as JsonData)[currentChangesStatus] }}</button>	
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
		<article v-else class="main__article">
			<h1 class="main__article__title">{{ (langData['errors'] as JsonData)['articleForApproveNotFound'] }}</h1>
		</article>
	</main>
	<main v-else class="main">
		<Loader/>
	</main>
</template>

<style lang="scss" scoped src="./scss/ArticleEdit.scss"></style>