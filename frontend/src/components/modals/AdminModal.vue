<script setup lang="ts">
    import { onMounted, Ref, ComputedRef, ref } from "vue";

    import Captcha from "../Captcha.vue";

    import { JsonData } from "../../ts/interfaces/JsonData";

    import { useRoute, useRouter, RouteLocationNormalizedLoaded,  Router } from 'vue-router';

    import axios from "axios";

    import { adminStatus, adminStatusReCheck } from "../../ts/handlers/AdminHandler";

    import InfoModal from "./InfoModal.vue";
    import { pushModal, closeModal, openModal } from "jenesius-vue-modal";

    import VueNumberInput from '@chenfengyuan/vue-number-input';

    import { LangDataHandler } from "../../ts/handlers/LangDataHandler";
    import langsData from "./locales/AdminModal.json";

    import { csrfTokenInput, getNewCsrfToken } from "../../ts/handlers/CSRFTokenHandler";
    
    const langData : ComputedRef<JsonData> = LangDataHandler.initLangDataHandler("AdminModal", langsData).langData;

    const captcha : Ref<{ execute: () => void } | null> = ref(null);

    let captchaVerifyCallback : (token: string) => void;

    adminStatusReCheck();

    const route : RouteLocationNormalizedLoaded = useRoute();
    const router : Router = useRouter();

    const checkedRememberMe : Ref<boolean> = ref(false);
    const nickname : Ref<string> = ref('');
    const password : Ref<string> = ref('');

    const isCurrentRouteName = (routeName: string) : boolean => 
    {
        return routeName == route.name ? true : false;
    }

    const settings = ref({
        article_edit_timeout_minutes: 0,
        max_upload_filesize_mb: 0,
        article_need_rating_to_approve_editorially: 0
    });

    const onLoginButtonRequest = async () => 
    {
        await getNewCsrfToken();

        if(csrfTokenInput.value == null)
        {
            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            return;
        }

        const data = 
        {
            csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
			nickname: nickname.value,
			password: password.value,
            rememberMe: checkedRememberMe.value
		}

        await axios.post('/api/admin/login', data)
		 .then(response => 
		{
            if(response.data.success)
            {
                closeModal();
                openModal(InfoModal, {status: true, text: langData.value['successfullyLogin']}); 
            }
            else
            {
                if(response.data.Warning)
                {
                    if(response.data.Warning.message == 'Please ether nickname')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needNickname']});
                    }
                    else if(response.data.Warning.message == 'Please ether password')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needPassword']});
                    }
                    else
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                    }
                }
                        else if(response.data.Error)
                        {
                            if(response.data.Error.message == 'Admin nickname or password is incorrect')
                            {
                                pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['incorrectNicknameOrPassword']});
                            }
                            else
                            {
                                pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                            }
                        }
                        else if(response.data.Critical)
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                        }
                        else
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                        }
                    }
                })
        .catch(error => 
        {
            if(error.response.data.Warning)
            {
                if(error.response.data.Warning.message == 'Please ether nickname')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needNickname']});
                }
                else if(error.data.Warning.message == 'Please ether password')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needPassword']});
                }
                else
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                }
            }
            else if(error.response.data.Error)
            {
                if(error.response.data.Error.message == 'Admin nickname or password is incorrect')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['incorrectNicknameOrPassword']});
                }
                else
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
            }
            else if(error.response.data.Critical)
            {
                pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
            }
            else
            {
                pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
            }
        })
    }

    const onLoginButtonValidate = async () => 
    {
        if(nickname.value.length > 0)
        {
            if (password.value.length > 0) 
            {
                await getNewCsrfToken();

                if(csrfTokenInput.value == null)
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                    return;
                }

                const data = 
                {
                    csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
			    	nickname: nickname.value,
					password: password.value,
                    rememberMe: checkedRememberMe.value
				}

                await axios.post('/api/admin/login', data)
			    .then(response => 
				{
                    if(response.data.success)
                    {
                        closeModal();
                        openModal(InfoModal, {status: true, text: langData.value['successfullyLogin']}); 
                    }
                    else
                    {
                        if(response.data.Warning)
                        {
                            if(response.data.Warning.message == 'Please ether nickname')
                            {
                                pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needNickname']});
                            }
                            else if(response.data.Warning.message == 'Please ether password')
                            {
                                pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needPassword']});
                            }
                            else
                            {
                                pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                            }
                        }
                        else if(response.data.Error)
                        {
                            if(response.data.Error.message == 'Admin nickname or password is incorrect')
                            {
                                pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['incorrectNicknameOrPassword']});
                            }
                            else
                            {
                                pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                            }
                        }
                        else if(response.data.Critical)
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                        }
                        else
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                        }
                    }
                })
                .catch(error => 
                {
                    if(error.response.data.Warning)
                    {
                        if(error.response.data.Warning.message == 'Please ether nickname')
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needNickname']});
                        }
                        else if(error.data.Warning.message == 'Please ether password')
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needPassword']});

                        }
                        else
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                        }
                    }
                    else if(error.response.data.Error)
                    {
                        if(error.response.data.Error.message == 'Admin nickname or password is incorrect')
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['incorrectNicknameOrPassword']});
                        }
                        else
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                        }
                    }
                    else if(error.response.data.Critical)
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                    }
                    else
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                    }
                })
            }
            else
            {
                pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needPassword']});
            }
        }
        else
        {
            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needNickname']});
        }
    }

    const onSaveSettingsButton = async () =>
    {
        if(captcha.value != null && typeof captcha.value.execute === 'function')
        {
            captcha.value.execute();
        }
        
        await getNewCsrfToken();

        if(csrfTokenInput.value == null)
        {
            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            return;
        }

        const data =
        {
            csrfToken: (csrfTokenInput.value as HTMLInputElement).value,
            settings: settings.value
        }

        await axios.post('/api/admin/settings/set', data)
        .then(response => 
        {
            if(response.data.success)
            {
                pushModal(InfoModal, {status: true, text: langData.value['successfullySettingsSaved']}); 
            }
            else
            {
                if(response.data.Warning)
                {
                    if(response.data.Warning.message == 'Admin token not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else if(response.data.Warning.message == 'Admin nickname not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else if(response.data.Warning.message == 'Admin expiration_time not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                    }
                }
                else if(response.data.Error)
                {
                    if(response.data.Error.message == 'Invalid admin token')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                    }
                }
                else if(response.data.Critical)
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
                else
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
            }
        })
        .catch(error => 
        {
            if(error.response.data.Warning)
            {
                if(error.response.data.Warning.message == 'Admin token not found')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                }
                else if(error.response.data.Warning.message == 'Admin nickname not found')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                }
                else if(error.response.data.Warning.message == 'Admin expiration_time not found')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                }
                else
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                }
            }
            else if(error.response.data.Error)
            {
                if(error.response.data.Error.message == 'Invalid admin token')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                }
                else
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
            }
            else if(error.response.data.Critical)
            {
                pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
            }
            else
            {
                pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
            }
        });
    }

    const onQuitButton = async () => 
    {
        await getNewCsrfToken();

        if(csrfTokenInput.value == null)
        {
            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
            return;
        }

        const data = 
        {
            csrfToken: (csrfTokenInput.value as HTMLInputElement).value
        }

        await axios.post('/api/admin/quit', data)
        .then(async response => 
        {
            if(response.data.success)
            {
                closeModal();
                const modal = await openModal(InfoModal, {status: true, text: langData.value['successfullyQuit']});
									
				modal.onclose = function()
				{
                    if(isCurrentRouteName('adminEditComments') || isCurrentRouteName('ArticleAdminEditComments') || isCurrentRouteName('articlesWaitingApproval') || isCurrentRouteName('articlesWaitingPremoderate' || isCurrentRouteName('ArticleAdminApprove')))
                    {
                        router.push("/");
                    }
				}
            }
            else
            {
                if(response.data.Warning)
                {
                    if(response.data.Warning.message == 'Admin token not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else if(response.data.Warning.message == 'Admin nickname not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else if(response.data.Warning.message == 'Admin expiration_time not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                    }
                }
                else if(response.data.Error)
                {
                    if(response.data.Error.message == 'Invalid admin token')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                    }
                }
                else if(response.data.Critical)
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
                else
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
            }
        })
        .catch(error => 
        {
            if(error.response.data.Warning)
            {
                if(error.response.data.Warning.message == 'Admin token not found')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                }
                else if(error.response.data.Warning.message == 'Admin nickname not found')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                }
                else if(error.response.data.Warning.message == 'Admin expiration_time not found')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                }
                else
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                }
            }
            else if(error.response.data.Error)
            {
                if(error.response.data.Error.message == 'Invalid admin token')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                }
                else
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
            }
            else if(error.response.data.Critical)
            {
                pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
            }
            else
            {
                pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
            }
        });
    }

    onMounted(async () => 
    {
        if(adminStatus.value)
        {
            await getNewCsrfToken();

            if(csrfTokenInput.value == null)
            {
                pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                return;
            }

            const data = 
            {
                csrfToken: (csrfTokenInput.value as HTMLInputElement).value
            }

            await axios.post('/api/admin/settings/get', data)
            .then(response => 
            {
                if(response.data)
                {
                    settings.value = response.data;
                }
                else
                {
                    if(response.data.Warning)
                    {
                        if(response.data.Warning.message == 'Admin token not found')
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                        }
                        else if(response.data.Warning.message == 'Admin nickname not found')
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                        }
                        else if(response.data.Warning.message == 'Admin expiration_time not found')
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                        }
                        else
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                        }
                    }
                    else if(response.data.Error)
                    {
                        if(response.data.Error.message == 'Invalid admin token')
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                        }
                        else
                        {
                            pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                        }
                    }
                    else if(response.data.Critical)
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                    }
                    else
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                    }
                }
            })
            .catch(error => 
            {
                if(error.response.data.Warning)
                {
                    if(error.response.data.Warning.message == 'Admin token not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else if(error.response.data.Warning.message == 'Admin nickname not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else if(error.response.data.Warning.message == 'Admin expiration_time not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['unknown']});
                    }
                }
                else if(error.response.data.Error)
                {
                    if(error.response.data.Error.message == 'Invalid admin token')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warnings'] as JsonData)['needLogin']});
                    }
                    else
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                    }
                }
                else if(error.response.data.Critical)
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
                else
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['errors'] as JsonData)['unknown']});
                }
            });
        }
    });

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
    <div v-if="!adminStatus" class="form">
        <p class="form__title">{{ langData["formLoginTitle"] }}</p>
        <div class="form__fields">
            <div class="form__fields__field">
                <p class="form__fields__field__title">{{ langData["formLoginTitleNickname"] }}</p>
                <input v-model="nickname" :placeholder="(langData['formLoginNicknamePlaceholder'] as string)" class="form__fields__field__input text" type="text">
            </div>
            <div class="form__fields__field">
                <p class="form__fields__field__title">{{ langData["formLoginTitlePassword"] }}</p>
                <input v-model="password" :placeholder="(langData['formLoginPasswordPlaceholder'] as string)" class="form__fields__field__input text" type="password">
            </div>
        </div>
        <Captcha @on-verify="onCaptchaVerify" @on-error="onCaptchaError" ref="captcha" class="form__captcha"/>
        
        <label class="form__checkbox">{{ langData["formLoginCheckboxRememberMe"] }}
            <input type="checkbox" v-model="checkedRememberMe">
            <span class="form__checkbox__checkmark"></span>
        </label>
        
        <button @click="onLoginButtonValidate" class="form__button login">{{ langData["formLoginButton"] }}</button>
    </div>
    <div v-else class="form">
        <p class="form__title">{{ langData["formPanelTitle"] }}</p>

        <div class="form__fields">
            <div class="form__fields__field">
                <p class="form__fields__field__title small">{{ langData['formPanelEditSettingsArticleTimeoutMinutesTitle'] }}</p>
                <VueNumberInput :value="settings.article_edit_timeout_minutes" v-model="settings.article_edit_timeout_minutes" :min="1" class="form__fields__field__input number" controls></VueNumberInput>
            </div>
            <div class="form__fields__field">
                <p class="form__fields__field__title small">{{ langData['formPanelEditSettingsArticleMaxUploadFileSizeTitle'] }}</p>
                <VueNumberInput :value="settings.max_upload_filesize_mb" v-model="settings.max_upload_filesize_mb" :min="1" class="form__fields__field__input number" controls></VueNumberInput>
            </div>
            <div class="form__fields__field">
                <p class="form__fields__field__title small">{{ langData['formPanelEditSettingsArticleNeedRatingApproveEditorially'] }}</p>
                <VueNumberInput :value="settings.article_need_rating_to_approve_editorially" v-model="settings.article_need_rating_to_approve_editorially" :min="1" class="form__fields__field__input number" controls></VueNumberInput>
            </div>
        </div>

        <button @click="onSaveSettingsButton" class="form__button saveSettings">{{ langData["formPanelButtonSaveSettings"] }}</button>
        <button @click="onQuitButton" class="form__button quit">{{ langData["formPanelButtonQuit"] }}</button>
    </div>
    
</template>

<style lang="scss" src="./scss/AdminModal.scss"></style>