import axios from 'axios';
import qs from 'qs';

export const getSales = async (userFormData) => {
    try {


        var dataToSend = qs.stringify(userFormData);


        var config = {
            method: 'post',
            url: 'http://localhost:8000/getSales.php',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            data: dataToSend
        };


        const { data } = await axios(config);
        return data;

    } catch (error) {

        console.log(error.message);

    }
};