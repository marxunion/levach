<script setup lang="ts">
    import { ref } from "vue";

    import axios from "axios";

    import { isAdmin } from "../../ts/AdminHandler";

    import InfoModal from "./InfoModal.vue";
    import { pushModal, closeModal, openModal } from "jenesius-vue-modal";

    import { JsonData } from "../../ts/JsonHandler";

    import { LangDataHandler } from "./../../ts/LangDataHandler";
    import langsData from "./locales/AdminModal.json";
    
    const langData = ref(LangDataHandler.initLangDataHandler("AdminModal", langsData).langData);

    const checkedRememberMe = ref(false);
    const nickname = ref('');
    const password = ref('');

    const onLoginButton = () => 
    {
        console.log(checkedRememberMe.value);
        
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
                        openModal(InfoModal, {status: false, text: langData.value['successLogin']}); 
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
                    console.log(error.response);
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
    const onQuitButton = () => 
    {
        axios.post('/api/admin/quit')
        .then(response => 
        {
            console.log(response);
            
            if(response.data.success)
            {
                closeModal();
                openModal(InfoModal, {status: false, text: langData.value['successQuit']}); 
            }
            else
            {
                if(response.data.Warning)
                {
                    if(response.data.Warning.message == 'Admin token not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warning'] as JsonData)['needLogin']});
                    }
                    else if(response.data.Warning.message == 'Admin nickname not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warning'] as JsonData)['needLogin']});
                    }
                    else if(response.data.Warning.message == 'Admin expiration_time not found')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warning'] as JsonData)['needLogin']});
                    }
                    else
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warning'] as JsonData)['unknown']});
                    }
                }
                else if(response.data.Error)
                {
                    if(response.data.Error.message == 'Token is invalid')
                    {
                        pushModal(InfoModal, {status: false, text: (langData.value['warning'] as JsonData)['needLogin']});
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
                    pushModal(InfoModal, {status: false, text: (langData.value['warning'] as JsonData)['needLogin']});
                }
                else if(error.response.data.Warning.message == 'Admin nickname not found')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warning'] as JsonData)['needLogin']});
                }
                else if(error.response.data.Warning.message == 'Admin expiration_time not found')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warning'] as JsonData)['needLogin']});
                }
                else
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warning'] as JsonData)['unknown']});
                }
            }
            else if(error.response.data.Error)
            {
                if(error.response.data.Error.message == 'Token is invalid')
                {
                    pushModal(InfoModal, {status: false, text: (langData.value['warning'] as JsonData)['needLogin']});
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
</script>

<template>
    <div v-if="!isAdmin" class="form">
        <p class="form__title">{{ langData["formLoginTitle"] }}</p>
        <div class="form__fields">
            <div class="form__fields__field">
                <p class="form__fields__field__title">{{ langData["formLoginTitleNickname"] }}</p>
                <input v-model="nickname" :placeholder="(langData['formLoginNicknamePlaceholder'] as string)" class="form__fields__field__input" type="text">
            </div>
            <div class="form__fields__field">
                <p class="form__fields__field__title">{{ langData["formLoginTitlePassword"] }}</p>
                <input v-model="password" :placeholder="(langData['formLoginPasswordPlaceholder'] as string)" class="form__fields__field__input" type="password">
            </div>
        </div>


        <label class="form__checkbox">{{ langData["formLoginCheckboxRememberMe"] }}
            <input type="checkbox" v-model="checkedRememberMe">
            <span class="form__checkbox__checkmark"></span>
        </label>
        
        <button @click="onLoginButton" class="form__button">{{ langData["formLoginButton"] }}</button>
    </div>
    <div v-else class="form quit">
        <p class="form__title">{{ langData["formQuitTitle"] }}</p>
        <button @click="onQuitButton" class="form__button quit">{{ langData["formQuitButton"] }}</button>
    </div>
</template>

<style lang="scss" scoped src="./scss/AdminModal.scss"></style>