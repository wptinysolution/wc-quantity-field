import React from "react";
import {Button} from "@/components/ui/button";
import PageMasterComponent from "@/js/backend/PageMasterComponent";
import SwitchField from "@/components/Fields/SwitchField";
import InputField from "@/components/Fields/InputField";
import SelectField from "@/components/Fields/SelectField";
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
    // console.log( options )
    return (
        <PageMasterComponent>
            <InputField
                label="Quantity Text"
                desc="Quantity Text Change"
                defaultValue={options?.qtyText}
                onChange={(e) => onChangeField("qtyText", e.target.value)}
            />
            <SelectField
                label="Quantity Field Layout"
                desc="Select Layout."
                options={{
                    'default': 'Default',
                    'layout1': 'Layout 1',
                }}
                defaultValue={options?.qtyLayout || 'default'}
                onValueChange={(value) => onChangeField( "qtyLayout", value)}
            />
            <h4 className='border border-l-4 p-4 border-l-sky-500 font-bold text-base mb-4'>Shop Page</h4>
            <SwitchField
                label="Display Quantity Field"
                checked={options?.isShopShowQtyField}
                onCheckedChange={(value) => onChangeField("isShopShowQtyField", value)}
            />
            <SwitchField
                label="Display Quantity Text"
                checked={options?.isShopShowQtyText}
                onCheckedChange={(value) => onChangeField("isShopShowQtyText", value)}
            />
            <h4 className='border border-l-4 p-4 border-l-sky-500 font-bold text-base mb-4'>Checkout Page</h4>
            <SwitchField
                label="Display Quantity Field"
                checked={options?.isCheckoutShowQtyField}
                onCheckedChange={(value) => onChangeField("isCheckoutShowQtyField", value)}
            />
            <h4 className='border border-l-4 p-4 border-l-sky-500 font-bold text-base mb-4'>Product Page</h4>
            <SwitchField
                label="Display Quantity Field"
                checked={options?.isProductShowQtyField}
                onCheckedChange={(value) => onChangeField("isProductShowQtyField", value)}
            />
        </PageMasterComponent>
    );
}

export default Settings;