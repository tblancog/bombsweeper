import Axios from 'axios';

export default getNewGame = async () => {
  try {
    const response = await Axios.get(`${url}/new`);
    const data = await response.json();
    return data;
  } catch (e) {
    console.log(e);
  }
};
