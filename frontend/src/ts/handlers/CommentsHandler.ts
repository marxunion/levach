import { ref, Ref } from 'vue';

import { ArticleComment } from './../interfaces/ArticleComment'; 

export const lastLoadedComment : Ref<number> = ref(0);

export const comments : Ref<ArticleComment[]> = ref([]);