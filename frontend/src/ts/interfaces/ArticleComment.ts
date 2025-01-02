export interface ArticleComment
{
    id: number,
    view_id: number,
    parent_comment_id: number,

    text: string,
    rating: number,
    rating_influence: number,
    created_date: number,

    subcomments: ArticleComment[] | null
}