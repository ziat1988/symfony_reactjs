import React from 'react';
import Moment from "moment";

const NameDayBooking = (props) => {
    const {activeDate,totalPerson,DayNum,DayName,date,onClickEvent} = props;

    return (
        <div className={`widgetDay ${activeDate === date ? 'active' :''}`} onClick={onClickEvent}>
            <div className="widgetTextDay clearfix">
                <div className="textDay desktop hidden-xs">{DayName} </div>

                <div className="numberDay">
                    <span className="numberDayText">{ Moment(date).format('D')}</span>
                </div>
                <span data-toggle="tooltip"
                      title="Total de couverts"
                      className="tooltip-plugin totalPerson">

                        {totalPerson}
                    </span>

            </div>
        </div>
    );
};

export default NameDayBooking;

