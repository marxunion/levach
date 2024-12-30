export function removeLineBreakFromString(str : string) : string
{
    return str.replace(/\n/g, '');
}