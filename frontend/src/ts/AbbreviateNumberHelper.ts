export function abbreviateNumber(number: number): string 
{
    const siSymbol: string[] = ["", "K", "M", "B", "T", "Qa", "Qi", "Sx", "Sp", "Oc", "No", "Dc"];

    if (number === 0) 
    {
        return "0";
    }
    if (number < 1000) 
    {
        return number.toString();
    }

    let i: number = 0;
    while (number >= 1000 && i < siSymbol.length - 1) 
    {
        number /= 1000;
        i++;
    }

    const roundedNumber: number = Math.round(number * 100) / 100;

    return roundedNumber + siSymbol[i];
}