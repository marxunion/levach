import { ref, Ref } from "vue";

import { Article } from './../interfaces/Article'; 

export const articleReloading : Ref<boolean> = ref(false);
export let articles : Ref<Array<Article>> = ref([]);