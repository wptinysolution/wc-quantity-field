import React from "react";
import {Button} from "@/components/ui/button";
import PageMasterComponent from "@/js/backend/PageMasterComponent";
import SwitchField from "@/components/Fields/SwitchField";
import InputField from "@/components/Fields/InputField";
import useStore from "@/js/backend/Utils/StateProvider";

function Settings() {
    const {
        addNewField,
        setOptions,
        options,
        scrollToID
    } = useStore();
    /**
     * @param key
     * @param val
     * @returns {Promise<void>}
     */
    const onChangeField = async (key, val) => {
        await setOptions({
            ...options,
            [key]: val
        });
    };

    return (
        <PageMasterComponent>
            <InputField
                label="Quantity Text"
                desc="Quantity Text Change"
                defaultValue={options?.qtyText}
                onChange={(e) => onChangeField("qtyText", e.target.value)}
            />
            <h4 className='border border-l-4 p-4 border-l-sky-500 font-bold text-base mb-4'>Shop Page</h4>
            <SwitchField
                label="Display Quantity Text"
                checked={options?.isShowQtyText}
                onCheckedChange={(value) => onChangeField("isShowQtyText", value)}
            />

        </PageMasterComponent>
    );
}

export default Settings;