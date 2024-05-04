export function tagsArrayToString(tagsArray : Array<string>|null) 
{
    console.log(tagsArray);
    
    if(Array.isArray(tagsArray))
    {
        console.log(tagsArray.map(tag => `#${tag}`).join(' '));
        return tagsArray.map(tag => `#${tag}`).join(' ');
    }
}