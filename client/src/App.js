
import { useEffect, useState } from 'react';
import { getSales } from './api';
import carImg from './assets/car-img.jpg';

import './App.css';

function App() {

  const [sales, setSales] = useState(null);

  useEffect(() => {

    getSales().then((data) => setSales(data));

  }, [])

  return (
    <>

      {sales ? sales.cars.map(car => <div class='car'>
        <img src={carImg} />

        <div class='right-part'>
          <div>
            <h2>{car.model}</h2>
            <h3>{car.version}</h3>
            <div>{car.price}</div>
            <div>{car.goodDeal}</div>
            <div><i class='fa-solid fa-location-dot'></i> {car.location} - {car.city}</div>
            <div>{car.year}</div>
            <div>{car.km}</div>
          </div>
          <a href={car.url}><button>Voir l'annonce</button></a>
        </div>

      </div>) : "Loading"}

    </>
  );
}

export default App;
