import { create } from 'zustand'
import {getOptions, updateOptions} from "@/js/backend/Utils/Data";
export const uid = function(){
    return Date.now().toString(36) + Math.random().toString(36).substring(2);
}
export default create((set, get) => ({
    generalData:{},
    options: {},
    pluginList: [],
    notice: {
        hasNotice: false,
        positionClass: 'bottom-10',
        title: null,
        desc: null
    },
    setNotice: async ( theNotice ) => {
       set((state) => ({ notice: { ...state.notice, ...theNotice } }));
    },
    setOptions: async ( theOption ) => {
        set((state) => ({ options: { ...state.options, ...theOption },  scrollToID: null }));
    },
    getTheOptions: async () => {
        const theOption = await getOptions();
        set((state) => ({ options: theOption }));
    },
    saveSettings : async () => {
        const state = get();
        await updateOptions( {...state.options} );
        const theOption = await getOptions();
        set((state) => ({ options: theOption }));
        set((state) => ({
            notice: {
                ...state.notice,
                ...{
                    hasNotice: true,
                    title: 'Data Saved Successfully',
                }
            }
        }));
    },

}))