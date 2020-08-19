import React,{ useState, useEffect } from 'react';
import axios from "axios";
import Moment from "moment";

const ContentBooking = () => {

    const [hasError, setErrors] = useState(false);
    const [planets, setPlanets] = useState({});

    const url = Routing.generate('content_booking_by_day');
    const restaurantToken = 'ssd2656dsd1321gh13fd5d13sf5d4';
    useEffect(() =>{
            axios.get(url,{
                params: {
                    restaurantToken: restaurantToken,

                }
            }).then(paramsArr => {
                console.log(paramsArr);
            })
        }
    );


    return (
        <div>
            Hello
        </div>
    );
};

export default ContentBooking;