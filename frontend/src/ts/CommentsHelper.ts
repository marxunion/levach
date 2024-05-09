import { ref, Ref } from 'vue';

export interface Comment
{
    comment_id: number,
    parent_comment_id: number,

    text: string,
    rating: number,
    rating_influence: number,
    created_date: number,

    subcomments: Comment[] | null
}

export const comments : Ref<Comment[]> = ref([]);