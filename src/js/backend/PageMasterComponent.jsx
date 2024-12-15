import React, {useEffect, useState} from "react";
import MainHeader from "@/js/backend/MainHeader";
import { ScrollArea } from "@/components/ui/scroll-area"
import {Button} from "@/components/ui/button";
import {AnimateSpain} from "@/components/ui/AnimateSpain";
import useStore from '@/js/backend/Utils/StateProvider';

function PageMasterComponent({ children } ) {
    const {
        setOptions,
        options,
        getTheOptions,
        saveSettings,
        addNewGroup
    } = useStore();

    const [isSaveButtonLoading, setButtonLoading] = useState(false);
    const [isAddNewButtonLoading, setAddNewButtonLoading] = useState(false);

    useEffect( () => {
        getTheOptions();
    }, [isSaveButtonLoading]);

    return (
        <>
            <MainHeader/>
            <ScrollArea className="bg-white h-lvh scroll-smooth p-6">
                <div className='scroll-childran'>
                    {children}
                </div>
            </ScrollArea>
            <div className='sticky bottom-0 z-10 '>
                <div className="bg-white py-2 pl-6 pr-6 border-t flex justify-between items-center ">
                    <Button
                        variant="outline"
                        onClick={ async () => {
                            await setAddNewButtonLoading( true );
                            await addNewGroup()
                            await setTimeout(()=> setAddNewButtonLoading( false ), 200 )
                        } }
                        className="text-white hover:text-white h-12 w-48 px-3 py-3 bg-sky-500 border-sky-500"
                    >
                        {isAddNewButtonLoading ? <AnimateSpain/> : 'Add New Group'}
                    </Button>
                    <Button
                        variant="outline"
                        onClick={ async () => {
                            await setButtonLoading( true );
                            await saveSettings();
                            await setTimeout(()=> setButtonLoading( false ), 200 )
                        }}
                        className="text-white hover:text-white h-12 w-48 px-3 py-3 bg-sky-500 border-sky-500"
                    >
                        {isSaveButtonLoading ? <AnimateSpain/> : 'Save All Changes'}
                    </Button>
                </div>
            </div>
        </>
    );
}

export default PageMasterComponent;