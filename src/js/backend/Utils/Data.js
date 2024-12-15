/*
 * Import local dependencies
 */
import Axios from 'axios';
import TheAlerts from "@/components/CustomizeComponent/TheAlerts";

const apiBaseUrl = `${wcqfParams.restApiUrl}TinySolutions/wcqf/v1/api`;

/*
 * Create a Api object with Axios and
 * configure it for the WordPRess Rest Api.
 */
const Api = Axios.create({
    baseURL: apiBaseUrl,
    headers: {
        'X-WP-Nonce': wcqfParams.rest_nonce
    }
});

export const updateOptions = async ( prams ) => {
    return await Api.post(`/updateOptions`, prams );
}

export const getOptions = async () => {
    const getTheOptions = await Api.get(`/getOptions`);
    return JSON.parse( getTheOptions.data );

}

export const getPluginList = async () => {
    return await Api.get(`/getPluginList`);
}

