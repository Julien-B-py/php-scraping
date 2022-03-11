import axios from 'axios';
import qs from 'qs';

const LOCAL_DOMAIN = "http://localhost:8000";
const ONLINE_DOMAIN = "https://young-waters-54181.herokuapp.com";

export const getSales = async (userFormData) => {
    try {

        var dataToSend = qs.stringify(userFormData);

        var config = {
            method: 'post',
            url: `${LOCAL_DOMAIN}/getSales.php`,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            data: dataToSend
        };

        const { data } = await axios(config);
        return data;

    } catch (error) {

        return error.message;

    }
};