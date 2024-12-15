import { create } from 'zustand'
import {getOptions, updateOptions} from "@/js/backend/Utils/Data";
export const uid = function(){
    return Date.now().toString(36) + Math.random().toString(36).substring(2);
}
export default create((set, get) => ({
    generalData:{},
    options: {
        allGroups: [],
    },
    scrollToID: null,
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

    resetScrollToID: async () => {
        set((state) => ({ scrollToID:null }));
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
    addNewGroup: async () => {
        const prevState = get();
        const groups = prevState.options?.allGroups || [];
        const newGroupId = uid();
        const newGroup = [
            ...groups,
            {
                id: newGroupId,
                enable_group: true,
                title:'Is it accessible?',
                description: '',
                groupBy: '',
                groupByCat: '',
                groupByProduct: '',
                displayGroupName: false,
                displayGroupDesc: false,
            }
        ]
        set((state) => ({
            options: { ...state.options, allGroups: newGroup },
            scrollToID: newGroupId
        }));
    }

}))