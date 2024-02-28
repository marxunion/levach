<script setup lang="ts">
import { ref, watch, reactive } from 'vue';
import axios from 'axios';

import "./scss/createArticle.scss"

import { MdEditor, config, en_US } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';
import RU from '@vavt/cm-extension/dist/locale/ru';

import { LangDataHandler } from "./../ts/LangDataHandler";
import langsData from "./locales/createArticle.json";
    
const langData = ref(LangDataHandler.initLangDataHandler("createArticle", langsData).langData);

config(
{
  editorConfig: 
  {
    languageUserDefined: 
    {
      'ru': RU,
      'en': en_US
    }
  }
});

let state = reactive(
{
  text: '',
  language: langData.value['editorCode'] as string
});

watch(langData, () =>
{
  state.language = langData.value['editorCode'] as string;
});

const onUploadImg = async (files: File[], callback: (urls: string[]) => void) => 
{
  const res = await Promise.all(
    files.map((file) => {
      return new Promise<{ data: { url: string } }>((rev, rej) => 
      {
        const form = new FormData();
        form.append('file', file);

        axios
          .post('/api/media/img/upload', form, 
          {
            headers: {
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
const text = ref('');
</script>

<template>
  <MdEditor class="editor" v-model="text" @onUploadImg="onUploadImg" :language="state.language" />
</template>