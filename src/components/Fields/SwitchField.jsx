import React from "react";
import { Switch } from "@/components/ui/switch"
import FieldWrapper from "@/components/CustomizeComponent/FieldWrapper";

function SwitchField(props){
    return (
        <FieldWrapper {...props} >
            <Switch
                {...props}
            />
        </FieldWrapper>
    );
}

export default SwitchField;