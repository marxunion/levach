import { ref, Ref } from 'vue';

import { Comment } from './../interfaces/Comment'; 

export const comments : Ref<Comment[]> = ref([]);