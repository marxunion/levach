export interface ArticleVersion
{
    title: string,
    text: string,
    tags: Array<string>,
    editorially_status: number,
    premoderation_status: number,
    acceptededitorially_status: number
}
export interface Article
{
    versions: Array<ArticleVersion>,
    statistics: Array<any>,
    currentVersion: number
}