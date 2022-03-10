
import { useEffect, useState } from 'react';
import { getSales } from './api';
import Car from "./components/Car";

import cars from './assets/cars.jpg'

import { brands } from './assets/brands';

import './App.css';

function App() {

  // Error messages
  const [error, setError] = useState("");
  // User input values
  const [input, setInput] = useState({ brand: "", energy: "", minPrice: "", maxPrice: "", gearbox: "" });
  // Loading
  const [loading, setLoading] = useState(false);
  // Hide or display user search form
  const [searchMode, setSearchMode] = useState(true);
  // Store collected cars data
  const [sales, setSales] = useState(null);

  useEffect(() => {

    if (!searchMode) {

      setLoading(true);

    }

  }, [searchMode])



  useEffect(() => {

    (error || sales) && setLoading(false);

  }, [error, sales])


  useEffect(() => {

    loading && getSales(input).then((data) => {

      if (typeof data === "string") {
        setError(data);
        return;
      }

      setSales(data);

    });

  }, [loading])


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
          <img src={cars} alt="cars" />

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

      {!searchMode && sales?.cars.map((car, index) => <Car key={index} car={car} />)}

      {error && <div className="message">{error}</div>}

      {loading && <div className="message">Chargement ...</div>}
      
    </>
  );
}

export default App;
