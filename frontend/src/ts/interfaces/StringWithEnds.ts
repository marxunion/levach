import { JsonData } from './JsonData';

interface End
{
	end: string;
	endOffsetStart: number;
	endOffsetEnd: number;
}

export interface StringWithEnds
{
	str: string;
	ends: End[]; 
}


export class StringWithEnds implements StringWithEnds 
{
	str: string;
	ends: End[]; 
	constructor(data: JsonData) 
	{
		this.str = data['str'] as string;
		this.ends = (data['ends'] as JsonData[]).map((dataEnds) => {
			return {
				end: dataEnds['end'] as string,
				endOffsetStart: dataEnds['endOffsetStart'] as number,
				endOffsetEnd: dataEnds['endOffsetEnd'] as number
			} as End; 
		});
		return this;
	}

	public getStringWithEnd(num: number): string 
	{
		for (const e of this.ends) 
		{
			const isInfinity = e.endOffsetEnd === -1;
			const endOffsetEndValue = isInfinity ? Infinity : e.endOffsetEnd;
	
			if (e.endOffsetStart <= num && endOffsetEndValue >= num) 
			{
				return this.str + e.end;
			}
		}
		return "";
	}
}