export interface JsonData 
{
    [key: string]: string | string[] | JsonData | JsonData[] | number | number[];
}