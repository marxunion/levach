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
export interface Statistics
{
    current_title: string,
    current_text: string,
    current_tags: string[],
    created_date: number,
    rating: number,
    comments: number,
    editorially_status: number,
    premoderation_status: number,
    approvededitorially_status: number,
    currentVersion: number
} 

export interface Article
{
    id: number,
    versions: ArticleVersion[],
    statistics: Statistics,
    view_code: string,
    comments: Comment[]
    currentSelectedVersion: number
}
