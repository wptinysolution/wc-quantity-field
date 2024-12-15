import React from "react";
import FieldWrapper from "@/components/CustomizeComponent/FieldWrapper";
import {Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue} from "@/components/ui/select";

function SelectField(props){
    const { options, defaultValue } = props;
    return (
        <FieldWrapper {...props} >
            <Select {...props} >
                <SelectTrigger className="w-full">
                    <SelectValue placeholder={defaultValue} />
                </SelectTrigger>
                <SelectContent className='z-99 bg-slate-50'>
                    {
                        Object.keys(options).map( (value, index, array) => {
                            return <SelectItem key={index} value={`${value}`}>{options[value]} </SelectItem>
                        } )
                    }
                </SelectContent>
            </Select>
        </FieldWrapper>
    );
}

export default SelectField;