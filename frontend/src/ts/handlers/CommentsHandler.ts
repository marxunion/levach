import { ref, Ref } from 'vue';

import { Comment } from './../interfaces/Comment'; 

export const lastLoadedComment : Ref<number> = ref(0);
export const comments : Ref<Comment[]> = ref([]);