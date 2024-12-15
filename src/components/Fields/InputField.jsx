import React from "react";
import {Input} from "@/components/ui/input";
import FieldWrapper from "@/components/CustomizeComponent/FieldWrapper";

function InputField(props){
    return (
        <FieldWrapper {...props} >
            <Input
                {...props}
            />
        </FieldWrapper>
    );
}

export default InputField;