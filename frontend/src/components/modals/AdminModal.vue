<script setup lang="ts">
    import { onMounted, reactive, ref } from "vue";

    import axios from "axios";

    import { adminStatus, adminStatusReCheck } from "../../ts/AdminHandler";

    import InfoModal from "./InfoModal.vue";
    import { pushModal, closeModal, openModal } from "jenesius-vue-modal";

    import VueNumberInput from '@chenfengyuan/vue-number-input';

    import { JsonData } from "../../ts/JsonHandler";

    import { LangDataHandler } from "./../../ts/LangDataHandler";
    import langsData from "./locales/AdminModal.json";
    
    const langData = ref(LangDataHandler.initLangDataHandler("AdminModal", langsData).langData);

    const checkedRememberMe = ref(false);
    const nickname = ref('');
    const password = ref('');

    let settings = reactive({
        editArticleTimeoutMinutes: 1,
        maxUploadFileSize: 30
    });

    const onLoginButton = () => 
    {
        if(nickname.value.length > 0)
        {
            if (password.value.length > 0) 
            {
                const data = 
                {
			    	"nickname": nickname.value,
					"password": password.value,
                    "rememberMe": checkedRememberMe.value
				}
                axios.post('/api/admin/login', data)
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

    const onSaveSettingsButton = () =>
    {
        axios.post('/api/admin/settings/edit', settings)
        .then(response => 
        {
            if(response.data.success)
            {
                pushModal(InfoModal, {status: true, text: langData.value['successfullySavedChanges']}); 
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
                    if(response.data.Error.message == 'Token is invalid')
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
                if(error.response.data.Error.message == 'Token is invalid')
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
    };

    const onQuitButton = () => 
    {
        axios.post('/api/admin/quit')
        .then(response => 
        {
            if(response.data.success)
            {
                closeModal();
                openModal(InfoModal, {status: true, text: langData.value['successfullyQuit']}); 
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
                    if(response.data.Error.message == 'Token is invalid')
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
                if(error.response.data.Error.message == 'Token is invalid')
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
    };

    onMounted(() => 
    {
        if(adminStatus)
        {
            axios.post('/api/admin/settings/get')
            .then(response => 
            {
                if(response.data.settings)
                {
                    settings = response.data.settings;
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
                        if(response.data.Error.message == 'Token is invalid')
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
                    if(error.response.data.Error.message == 'Token is invalid')
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

    adminStatusReCheck();
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

        <label class="form__checkbox">{{ langData["formLoginCheckboxRememberMe"] }}
            <input type="checkbox" v-model="checkedRememberMe">
            <span class="form__checkbox__checkmark"></span>
        </label>
        
        <button @click="onLoginButton" class="form__button login">{{ langData["formLoginButton"] }}</button>
    </div>
    <div v-else class="form">
        <p class="form__title">{{ langData["formPanelTitle"] }}</p>

        <div class="form__fields">
            <div class="form__fields__field">
                <p class="form__fields__field__title small">{{ langData['formPanelEditSettingsArticleTimeoutMinutesTitle'] }}</p>
                <VueNumberInput v-model="settings.editArticleTimeoutMinutes" :min="1" class="form__fields__field__input number" controls></VueNumberInput>
            </div>
            <div class="form__fields__field">
                <p class="form__fields__field__title small">{{ langData['formPanelEditSettingsArticleMaxUploadFileSizeTitle'] }}</p>
                <VueNumberInput v-model="settings.maxUploadFileSize" :min="1" class="form__fields__field__input number" controls></VueNumberInput>
            </div>
        </div>

        <button @click="onSaveSettingsButton" class="form__button saveSettings">{{ langData["formPanelButtonSaveSettings"] }}</button>
        <button @click="onQuitButton" class="form__button quit">{{ langData["formPanelButtonQuit"] }}</button>
    </div>
</template>

<style lang="scss" src="./scss/AdminModal.scss"></style>