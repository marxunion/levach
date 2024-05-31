import { Comment } from './Comment'; 

export interface ArticleVersion
{
    version_id: number,
    title: string,
    text: string,
    tags: string[],
    editorially_status: number,
    premoderation_status: number,
    approvededitorially_status: number,
    created_date: number
}

export interface Article
{
    id: number,
    visible_id: number,
    versions: ArticleVersion[],
    current_title: string,
    current_text: string,
    current_tags: string[],
    created_date: number,
    rating: number,
    comments_count: number,
    editorially_status: number,
    premoderation_status: number,
    approvededitorially_status: number,
    currentVersion: number
    edit_code: string,
    view_code: string,

    comments: Comment[],
    currentSelectedVersion: number,
    canRequestApprove: boolean
}
