import carImg from '../assets/car-img.jpg';

const Car = ({ car }) => {

    // Determine a colored icon to display close to goodDeal for a better user experience
    const getDealIcon = () => {

        if (car.goodDeal.includes("marché")) {
            return <i className="fa-solid fa-thumbs-down" style={{ color: "#D40000" }}></i>;
        } else if (car.goodDeal.includes("indisponible")) {
            return "";
        }
        return <i className="fa-solid fa-thumbs-up" style={{ color: "#00aa0e" }}></i>;

    }

    return (<div className='car' >

        <img src={carImg} alt="car" />
        <div className='right-part'>
            <div>
                <h2>{car.model}</h2>
                <h3>{car.version}</h3>
                <div>{car.price}</div>
                <div>{getDealIcon()} {car.goodDeal}</div>
                <div><i className='fa-solid fa-location-dot'></i> {car.location} - {car.city}</div>
                <div>{car.year}</div>
                <div>{car.km}</div>
            </div>
            <a href={car.url}><button>Voir l'annonce</button></a>
        </div>

    </div>);
}

export default Car;