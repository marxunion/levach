import { ref, Ref } from "vue";

export interface ArticleVersion
{
    version_id: number,
    title: string,
    text: string,
    tags: Array<string>,
    editorially_status: number,
    premoderation_status: number,
    approvededitorially_status: number,
    created_date: number
}
export interface Statistics
{
    created_date: number,
    rating: number,
    comments: number,
    currentVersion: number
} 

export interface Article
{
    id: number,
    versions: Array<ArticleVersion>,
    statistics: Statistics,
    view_code: string,
    currentSelectedVersion: number
}

export const articleReloading = ref(false);
export let articles : Ref<Array<Article>> = ref([]);