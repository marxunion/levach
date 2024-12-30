import { ArticleComment } from './ArticleComment'; 

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
    view_id: number,
    versions: ArticleVersion[],
    current_title: string,
    current_text: string,
    current_tags: string[],
    created_date: number,
    last_edit_date: number,
    rating: number,
    comments_count: number,
    editorially_status: number,
    premoderation_status: number,
    approvededitorially_status: number,
    currentVersion: number
    edit_code: string,
    view_code: string,

    scrollToCommentId: number,
    comments: ArticleComment[],

    currentSelectedVersion: number,
    canRequestApprove: boolean
}
