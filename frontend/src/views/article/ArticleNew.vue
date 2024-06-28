<script setup lang="ts">
	import { ref, reactive, watch, Ref, ComputedRef} from 'vue';
	import { useRouter, Router } from 'vue-router';
	import axios from 'axios';

	import { JsonData } from '../../ts/interfaces/JsonData';

	import LinkAttr from 'markdown-it-link-attributes';

	import { MdEditor, config } from 'md-editor-v3';
	import 'md-editor-v3/lib/style.css';

	import { openModal, closeModal } from "jenesius-vue-modal";
	import LoaderModal from "./../../components/modals/LoaderModal.vue";
    import InfoModal from "./../../components/modals/InfoModal.vue";
	import InfoModalWithLink from "./../../components/modals/InfoModalWithLink.vue";
	
	import Captcha from '../../components/Captcha.vue';

	import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
	import langsData from "./locales/ArticleNew.json";

	import './../../libs/font_2605852_prouiefeic';

	import { csrfTokenInput, getNewCsrfToken } from '../../ts/handlers/CSRFTokenHandler';

	import settings from '../../configs/main.json';

	const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("ArticleNew", langsData).langData;

	const captcha : Ref<{ execute: () => void } | null> = ref(null);

	let captchaVerifyCallback : (token: string) => void;

	const router : Router = useRouter();

	//Editor
	config(
	{
		markdownItPlugins(plugins) {
			return [
			...plugins,
			{
				type: 'linkAttr',
				plugin: LinkAttr,
				options: {
				attrs: {
					target: '_blank'
				}
				}
			},
			];
		},
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
		editorState.language = LangDataHandler.currentLanguage.value;
	});

	//Tags
	const newTag : Ref<string> = ref('');
	const tags : Ref<string[]>= ref([]);

	const addTag = () => 
	{
		if(!tags.value.includes(newTag.value.split(" ").join("")))
		{
			if (newTag.value.length >= 1 && newTag.value.length <= 40)
			{
				tags.value.push(newTag.value.split(" ").join(""));
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
	}
	const removeTag = (index: number) => 
	{
		tags.value.splice(index, 1);
	}

	
	const onSendButtonRequest = async function (captchaToken: string)
	{
		await getNewCsrfToken();

        if(csrfTokenInput.value == null)
        {
            openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            return;
        }
		
		const data = {
			csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
			captchaToken: captchaToken,
			text: editorState.text,
			tags: tags.value
		}
		await axios.post('/api/article/new', data)
		.then(async response => 
		{
			if (response.data.editCode) 
			{
				const modal = await openModal(InfoModalWithLink, { status: true, text: langData.value['articleCreatedSuccessfully'], link: "https://" + window.location.hostname + "/#/article/edit/" + response.data.editCode, text2: (langData.value['warnings'] as JsonData)['articleEditCodeCopy'] })

				modal.onclose = function () 
				{
					router.push("/article/edit/" + response.data.editCode);
				};
			} 
			else 
			{
				if (response.data.Warning) 
				{
					if (response.data.Warning.message == "Please add a title for the article") 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle'] });
					} 
					else if (response.data.Warning.message == "Title must contain between 5 and 120 characters") 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols'] });
					} 
					else if (response.data.Warning.message == "Please add content for the article") 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent'] });
					} 
					else if (response.data.Warning.message == "The content of the article must be more than 25 characters") 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols'] });
					} 
					else if (response.data.Warning.message == 'Article has duplicated tags') 
					{
						openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleDuplicatedTags'] });
					} 
					else 
					{
						openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
					}
				} 
				else if (response.data.Error) 
				{
					if(response.data.Error.message == "Invalid captcha solving")
					{
						openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["captcha"]});
					}
					else
					{
						openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
					}
				} 
				else if (response.data.Critical) 
				{
					openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
				} 
				else 
				{
					openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
				}
			}
		})
		.catch(error => 
		{
			if (error.response.data.Warning) 
			{
				if (error.response.data.Warning.message == "Please add a title for the article") 
				{
					openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleNeedTitle'] });
				} 
				else if (error.response.data.Warning.message == "Title must contain between 5 and 120 characters") 
				{
					openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleTitleSymbols'] });
				} 
				else if (error.response.data.Warning.message == "Please add content for the article") 
				{
					openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleNeedContent'] });
				} 
				else if (error.response.data.Warning.message == "The content of the article must be more than 25 characters") 
				{
					openModal(InfoModal, { status: false, text: (langData.value['warnings'] as JsonData)['articleContentSymbols'] });
				} 
				else 
				{
					openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
				}
			} 
			else if (error.response.data.Error) 
			{
				if(error.response.data.Error.message == "Invalid captcha solving")
				{
					openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["captcha"]});
				}
				else
				{
					openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
				}
			} 
			else if (error.response.data.Critical) 
			{
				openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
			} 
			else 
			{
				openModal(InfoModal, { status: false, text: (langData.value['errors'] as JsonData)['unknown'] });
			}
		});
	}
	
	const onSendButtonValidate = async function ()
	{
		const contentParts = (editorState.text as string).split('\n');

		if(contentParts.length >= 1) 
		{
			const title = contentParts[0];
			if(title.substring(0, 2) == '# ') 
			{
				if(title.length >= 7 && title.length <= 120) 
				{
					if(contentParts.length >= 2) 
					{
						const content = contentParts.slice(1).join('\n');
						if(content.length >= 25) 
						{
							if(settings.captchaEnabled)
			    			{
								captchaVerifyCallback = onSendButtonRequest;
								captcha.value?.execute();
							}
							else
							{
								onSendButtonRequest('token');
							}
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

	let uploadedFiles : File[];
	let uploadedCallback : (urls: string[]) => void;

	const onUploadImgRequest = async (captchaToken : string) => 
	{
		openModal(LoaderModal);
		const promises = uploadedFiles.map((file) => 
		{
			return new Promise<{ data: { fileName: string } }>(resolve => 
			{
				const form = new FormData();

				form.append('file', file);
				form.append('captchaToken', captchaToken);

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
							if(response.data.Error.message == "Invalid captcha solving")
                            {
                                openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["captcha"]});
                        	}
							else
							{
								openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
							}
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
								openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)["unknown"]});
							}
						}
						else if(error.response.data.Error)
						{
							if(error.response.data.Error.message == "Invalid captcha solving")
                            {
                                openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["captcha"]});
                        	}
							else
							{
								openModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)["unknown"]});
							}
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
		});

		const res = await Promise.all(promises);
			
		const successfulResults = res.filter(item => item !== null);

		closeModal();
		uploadedCallback(successfulResults.map((item) => '/api/media/img/'+item.data.fileName));
	}

	const onUploadImgValidate = async (files: File[], callback: (urls: string[]) => void) => 
	{
		if(files.length > 0)
		{
			uploadedFiles = files;
			uploadedCallback = callback;
			if(settings.captchaEnabled)
			{
				captchaVerifyCallback = onUploadImgRequest;
				captcha.value?.execute();
			}
			else
			{
				onUploadImgRequest('token');
			}
		}
	}

	const onCaptchaVerify = (token: string) => 
    {
		captchaVerifyCallback(token);
    };

    const onCaptchaError = () =>
    {
        openModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['captcha']});
    }
</script>

<template>
	<main class="main">
		<article class="main__article">
			<div class="main__article__editorContainer">
				<MdEditor class="main__article__editorContainer__editor" v-model="(editorState.text as string)" @onUploadImg="onUploadImgValidate" :language="editorState.language" :preview="false" noIconfont/>
				<button class="main__article__editorContainer__sendButton" @click="onSendButtonValidate()">{{ langData['sendButton'] }}</button>	
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
			<Captcha v-if="settings.captchaEnabled" @on-verify="onCaptchaVerify" @on-error="onCaptchaError" ref="captcha" class="main__article__captcha"/>
		</article>
	</main>
</template>

<style lang="scss" scoped src="./scss/ArticleNew.scss"></style>