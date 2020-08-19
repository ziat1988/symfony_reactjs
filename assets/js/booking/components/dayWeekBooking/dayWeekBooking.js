import React from 'react';
import ReactDOM from 'react-dom';
import Moment from "moment";
import './dayWeekBooking.scss';
import { ArrowRight,ArrowLeft } from 'react-bootstrap-icons';
import axios from "axios";
import NameDayBooking from "../nameDayBooking/nameDayBooking";

// const routes = require('../../../../../public/js/fos_js_routes.json');
// const Routing = require('../../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js');
// Routing.setRoutingData(routes);

class DayWeekBooking extends React.Component {

    constructor() {
        //par défault active est date au jourd'hui.
        console.log('component dc tao ra');
        const today = new Date();
        const todayString = Moment(today).format('YYYY-MM-DD 00:00:00');

        super();
        this.state = { dateTS:null, listDayWeek: [], loading: true,activeDate: todayString};
    }

    handleClick (date,event){
        // console.log(date);
        //  console.log(event);
        this.setState({activeDate:date})

    }

    callApi(dateTS = null,action=null){

        const restaurantToken = 'ssd2656dsd1321gh13fd5d13sf5d4';
        const urlRouting = Routing.generate('day_week_booking_navigate');

        axios.get(urlRouting,{
            params: {
                restaurantToken: restaurantToken,
                dateTS:dateTS,
                action:action
            }
        }).then(paramsArr => {
            this.setState({
                listDayWeek: paramsArr.data.listDayWeek,
                dateTS: Moment(paramsArr.data.date.date).format('X')
            })

        })
    }

    changeWeek(action,dateTS){
        this.callApi(dateTS,action);
    }

    componentDidMount() {
        this.callApi();
    }

    render(){
        console.log('component dang render',this.state.dateTS);

        return (
            <div data-datets="" className="widgetMenuDay">
                <div className="menu-date-title desktop hidden-xs">
                    12/07/2020
                </div>
                <div className="list-group list-day-menu clearfix">
                    <div className="arrowLeft" onClick={this.changeWeek.bind(this,1,this.state.dateTS)}>
                        <span  className="desktop hidden-xs arrow-widget arrow-left arrowPrevWeek" > <ArrowLeft/> </span>
                    </div>

                    <div className="wrapperDayWeek">
                        {this.state.listDayWeek.map(
                            (arrDayInfo,i) =>  <NameDayBooking activeDate={this.state.activeDate} key={arrDayInfo.date} {...arrDayInfo} onClickEvent={this.handleClick.bind(this,arrDayInfo.date)}/>
                        )}
                    </div>

                    <div className="arrowRight" onClick={this.changeWeek.bind(this,2,this.state.dateTS)}>
                        <span  className="arrowChangeDateMobile mobile arrow-widget arrow-left arrowYesterday"> <ArrowRight/></span>
                    </div>

                </div>
            </div>


        )

    }


}

export default DayWeekBooking;

