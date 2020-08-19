import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import Moment from 'moment';

//import { Date} from 'prismic-reactjs';
import 'bootstrap/dist/css/bootstrap.min.css';
import './booking.scss';
import DayWeekBooking from "./components/dayWeekBooking/dayWeekBooking";
import ContentBooking from "./components/contentBooking/contentBooking";

class Bookings extends React.Component {

    constructor() {
        super();
        this.state = { bookings: [], loading: true};
    }

    componentDidMount() {
        this.getBookings();
    }

    getBookings() {
        axios.get(`http://127.0.0.1:8000/api/booking/get`).then(bookings => {

            console.log(bookings.data);
            this.setState({ bookings: bookings.data, loading: false})
        })
    }
    render() {

        if(this.state.loading){
            return <div>...</div>
        }else{
            return (

                <div className="backRestoProContainer bookingListContainer">
                    <DayWeekBooking/>

                    <div className='wraperBooking'>

                        <ContentBooking/>

                        {/*<ul>*/}
                        {/*    {this.state.bookings.map(booking=>*/}
                        {/*        <li key={booking.id}>{Moment(booking.date.date).format('D/M/Y')}</li>*/}
                        {/*    )}*/}

                        {/*</ul>*/}
                    </div>
                </div>
            )
        }


    }
}
ReactDOM.render(<Bookings/>, document.getElementById('root-booking'));