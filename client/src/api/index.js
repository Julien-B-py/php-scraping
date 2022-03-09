import axios from 'axios';

export const getSales = async () => {
    try {

        const { data } = await axios.post('http://localhost:8000/getSales.php');
        return data;

    } catch (error) {

        console.log(error.message);

    }
};