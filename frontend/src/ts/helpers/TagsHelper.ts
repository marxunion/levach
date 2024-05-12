export function tagsArrayToString(tagsArray : Array<string>|null) 
{    
    if(Array.isArray(tagsArray))
    {
        return tagsArray.map(tag => `#${tag}`).join(' ');
    }
}