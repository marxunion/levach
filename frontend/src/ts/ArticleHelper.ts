export interface ArticleVersion
{
    title: string,
    text: string,
    tags: Array<string>,
    editorially_status: number,
    premoderation_status: number,
    acceptededitorially_status: number,
    date: number
}
export interface Statistics
{
    rating: number,
    comments: number,
    currentVersion: number
} 

export interface Article
{
    versions: Array<ArticleVersion>,
    statistics: Statistics,
    view_code: string,
    currentVersion: number
}