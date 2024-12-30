export interface Comment
{
    id: number,
    view_id: number,
    parent_comment_id: number,

    text: string,
    rating: number,
    rating_influence: number,
    created_date: number,

    subcomments: Comment[] | null
}