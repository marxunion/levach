<script setup lang="ts">
import { ref, reactive } from 'vue';
import axios from 'axios';
import { MdEditor, config } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';

import RU from '@vavt/cm-extension/dist/locale/ru';

config({
  editorConfig: {
    languageUserDefined: {
      'ru': RU
    }
  }
});

const state = reactive({
  text: '',
  language: 'ru'
});

const onUploadImg = async (files: File[], callback: (urls: string[]) => void) => {
  const res = await Promise.all(
    files.map((file) => {
      return new Promise<{ data: { url: string } }>((rev, rej) => {
        const form = new FormData();
        form.append('file', file);

        axios
          .post('/api/img/upload', form, {
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

<style>
  .editor 
  {
    width: 80%;
    height: 80%;
  }
</style>