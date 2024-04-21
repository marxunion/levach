export function timestampToLocaleFormatedTime(timestamp: number): string 
{
    const date = new Date(timestamp * 1000);

    const hours = ("0" + (date.getHours())).slice(-2);
    const minutes = ("0" + (date.getMinutes())).slice(-2);

    const day = ("0" + date.getDate()).slice(-2);
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const year = date.getFullYear();

    return `${hours}:${minutes} ${day}.${month}.${year}`;
}