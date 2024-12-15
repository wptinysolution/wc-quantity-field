import React from "react";
import FieldWrapper from "@/components/CustomizeComponent/FieldWrapper";
import {Textarea} from "@/components/ui/textarea";

function TextareaField(props){
    return (
        <FieldWrapper {...props} >
            <Textarea
                className='border-slate-200'
                {...props}
            />
        </FieldWrapper>
    );
}

export default TextareaField;