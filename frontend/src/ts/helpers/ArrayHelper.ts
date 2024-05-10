export function arraysAreEqual(arr1 : Array<any>, arr2 : Array<any>) : boolean
{
    if (arr1.length !== arr2.length) 
    {
        return false;
    }

    for (let i = 0; i < arr1.length; i++) 
    {
        if (arr1[i] !== arr2[i])
        {
            return false;
        }
    }
    
    return true;
}