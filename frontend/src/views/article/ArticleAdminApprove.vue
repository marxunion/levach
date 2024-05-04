<script setup lang="ts">
	import { ref, watch, reactive, Ref, ComputedRef, computed, onMounted } from 'vue';
	import { useRoute, useRouter } from 'vue-router';
	import axios from 'axios';

	//import DropDown from "./../../components/DropDown.vue";

	import { MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { JsonData } from './../../ts/JsonHandler';

	import Loader from "./../../components/Loader.vue";

	import { closeModal, openModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
	import InfoModal from "./../../components/modals/InfoModal.vue";
    import InfoModalWithLink from "./../../components/modals/InfoModalWithLink.vue";

	import langsData from "./locales/ArticleAdminApprove.json";

	import { LangDataHandler } from "./../../ts/LangDataHandler";

	import './../../libs/font_2605852_prouiefeic';

	import { timestampToLocaleFormatedTime } from '../../ts/DateTimeHelper';

	import { csrfTokenInput, getNewCsrfToken } from '../../ts/csrfTokenHelper';
import { arraysAreEqual } from '../../ts/ArrayHelper';

	const langData = LangDataHandler.initLangDataHandler("ArticleAdminApprove", langsData).langData;

    const currentChangesStatus = ref(0);

	const route = useRoute();
    const router = useRouter();
	const articleViewCode = ref<string | null>(null);

	articleViewCode.value = route.params.articleViewCode as string;
	
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
            csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
        }
		
		return await axios.post('/api/admin/article/approve/preload/'+articleViewCode.value, data)
		.then(response =>
		{
			if(response.data.title)
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
					if(response.data.Error.message == "Article for edit not found")
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
		.catch(error =>
		{
			if(error.response.data.Warning)
			{
				return null;
			}
			else if(error.response.data.Error)
			{
				if(error.response.data.Error.message == "Article for edit not found")
				{
					return null;
				}
				else
				{
					return null;
				}
			}
			else if(error.response.data.Critical)
			{
				return null;
			}
			else
			{
				return null;
			}
		});
	}

	let fetchedData = ref();
	let loaded = ref(false);
	
	let editorState = reactive({
		text: '',
		language: ''
	});

	let newTag = ref('');
	let tags : Ref<string[]> = ref([]);

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
								openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
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
										openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["imageNeedImage"]});
									}
									else if(error.response.data.Warning.message == "UploadImage File size exceeds the maximum allowable file size")
									{
										openModal(InfoModal, {status: false, text: ((langData.value['warnings'] as JsonData)["imageMaxSize"] as string).replace('{size}', error.response.data.Warning.params.max_upload_filesize_mb)});
									}
									else if(error.response.data.Warning.message == "UploadImage Invalid image type")
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

    const checkChanges = async () =>
    {
        return setTimeout(() => 
        {
            if(editorState.text != fetchedData.value['text'] || !arraysAreEqual(tags.value, fetchedData.value['tags']))
            {
                currentChangesStatus.value = 1;
                return true;
            }
            else
            {
                currentChangesStatus.value = 0;
                return false;
            }
        }, 500);
    }

	const addTag = () => 
	{
		if(!tags.value.includes(newTag.value.trim()))
		{
			if (newTag.value.length >= 1 && newTag.value.length <= 40)
			{
				tags.value.push(newTag.value.trim());
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
            const contentParts = (editorState.text as string).split('\n');

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
                            if(content.length >= 25 && content.length <= 10000) 
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
                                    text: editorState.text, 
                                    tags: tags.value
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
                                            else if(response.data.Warning.message == "Article content must contain between 25 and 10000 characters")
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
                                            if(response.data.Error.message == "Article for edit not found")
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
                                        else if(error.response.data.Warning.message == "Article content must contain between 25 and 10000 characters")
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
                                        if(error.response.data.Error.message == "Article for edit not found")
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

	watch(langData, () =>
	{
		editorState.language = LangDataHandler.currentLanguage.value;
	});

    watch(() => editorState.text, () => 
    {
        checkChanges();
    });

	onMounted(async function()
	{
		try 
		{
			fetchedData.value = await fetchData();
			if(fetchedData.value != null)
			{
				editorState = reactive(
				{
					text: fetchedData.value['text'],
					language: LangDataHandler.currentLanguage.value
				});

				if(fetchedData.value['tags'] == null)
				{
					fetchedData.value['tags'] = [];
				}

                Object.assign(tags, fetchedData.value['tags']);
			}
			loaded.value = true;
		} 
		catch (error) 
		{
			loaded.value = true;
			fetchedData.value = null;
		}
	});
</script>

<template>
	<main v-if="loaded" class="main">
		<article v-if="fetchedData" class="main__article">
			<div class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="(editorState.text as string)" @onChange="checkChanges" @onUploadImg="onUploadImg" :language="editorState.language" :preview="false" noIconfont/>
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