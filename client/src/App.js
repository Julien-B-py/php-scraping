
import { useEffect, useState } from 'react';
import { getSales } from './api';
import carImg from './assets/car-img.jpg';
import { brands } from './assets/brands';

import './App.css';

function App() {

  const [input, setInput] = useState({ brand: "", energy: "", minPrice: "", maxPrice: "", gearbox: "" });
  const [searchMode, setSearchMode] = useState(true);
  const [sales, setSales] = useState(null);

  useEffect(() => {

    if (!searchMode) {
      getSales(input).then((data) => setSales(data));
    }

  }, [searchMode])





  // Handle and update user input values changes
  const handleChange = (e) => {
    const { name, value } = e.target;
    setInput((prevState) => {
      return { ...prevState, [name]: value };
    });
  };


  const handleSubmit = (event) => {
    event.preventDefault();
    setSearchMode(false);
  }


  return (
    <>

      {searchMode && <form>

        <h2>Rechercher un véhicule d'occasion</h2>

        <div>
          <img src="./cars.jpg" alt="" />

          <div className="inputs">

            <div>

              <select name="brand" id="brand" onChange={handleChange} value={input.brand}>
                <option value="">-- Marque --</option>
                {brands.map((brand, index) => <option key={index} value={brand}>{brand}</option>)}


              </select>

              <select name="energy" id="energy" onChange={handleChange} value={input.energy}>

                <option value="">-- Energie --</option>
                <option value="dies">Diesel</option>
                <option value="ess">Essence</option>
                <option value="elec">Electrique</option>
                <option value="hyb">Hybride</option>

              </select>

              <input name="minPrice" type="number" placeholder="Prix min" onChange={handleChange} value={input.minPrice} />
              <input name="maxPrice" type="number" placeholder="Prix max" onChange={handleChange} value={input.maxPrice} />

              <select name="gearbox" id="gearbox" onChange={handleChange} value={input.gearbox}>

                <option value="">-- Boîte de vitesse --</option>
                <option value="AUTO">Automatique</option>
                <option value="MANUAL">Mécanique</option>

              </select>

            </div>

            <button onClick={handleSubmit}>Rechercher</button>
          </div>
        </div>

      </form>}




      {!searchMode && sales?.cars.map((car, index) => <div className='car' key={index}>
        <img src={carImg} />

        <div className='right-part'>
          <div>
            <h2>{car.model}</h2>
            <h3>{car.version}</h3>
            <div>{car.price}</div>
            <div>{car.goodDeal}</div>
            <div><i className='fa-solid fa-location-dot'></i> {car.location} - {car.city}</div>
            <div>{car.year}</div>
            <div>{car.km}</div>
          </div>
          <a href={car.url}><button>Voir l'annonce</button></a>
        </div>

      </div>)}

    </>
  );
}

export default App;
