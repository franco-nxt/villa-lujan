import React from 'react'

const MONTHS = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
const DAYS_SHORT = ['DO', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA'];

const Month = ({ month, year, max, min, day, onChange, bookings }) => {
    let firstDay = (new Date(year, month)).getDay();
    let daysInMonth = 32 - new Date(year, month, 32).getDate();
    let date = 1;
    let days = [];

    for (let i = 0; i < 6; i++) {
        for (let j = 0; j < 7; j++) {

            if (i === 0 && j < firstDay) {
                days.push(<div className="calendar__col" key={[i, j].join()}></div>)
            }
            else if (date > daysInMonth) {
                break;
            }
            else {
                let className = ['calendar__day'];
                let _day = new Date(year, month, date);
                let time = _day.getTime();
                let available = true;

                if (max && _day > max || (min && _day < min)) {
                    className.push('calendar__day-disabled');
                    available = false;
                }
                else {
                    let _bookings = bookings.filter(b => b.time == time);

                    if (_bookings.length > 1) {
                        className.push('calendar__day-busy');
                        available = false;
                    }

                    if (_bookings.length == 1) {
                        className.push('calendar__day-half-busy-' + _bookings[0].time_shift);
                        available = true;
                    }
                }

                days.push(<div className="calendar__col" key={[i, j].join()}>
                    {available && <input type="text" id={'_' + time} name="calendar" type="radio" value={time} onChange={() => onChange(_day)} checked={day && day.getTime() == time} />}
                    <label htmlFor={'_' + time} className={className.join(' ')}>{date}</label>
                </div>)
                date++;
            }
        }
    }

    return days
}

export default function Calendar({ bookings, currentMonth, currentYear, max, min, busy, almost, onChange, day }) {
    const [month, setMonth] = React.useState(currentMonth);
    const [year, setYear] = React.useState(currentYear);

    const next = e => {
        e.preventDefault();
        setYear(a => (month === 11) ? a + 1 : a);
        setMonth(a => (a + 1) % 12);
    }

    const prev = e => {
        e.preventDefault();
        setYear(a => (month === 0) ? a - 1 : a);
        setMonth(a => (a === 0) ? 11 : a - 1);
    }

    return (
        <div className="calendar" id="calendar">
            <div className="calendar__control">
                <div>
                    <button className="calendar__prev" onClick={prev}></button>
                </div>
                <div>
                    <span className="calendar__span">{MONTHS[month]} {year}</span>
                </div>
                <div>
                    <button className="calendar__next" onClick={next}></button>
                </div>
            </div>
            {DAYS_SHORT.map(day => <div className="calendar__col calendar__week" key={day}>{day}</div>)}
            <Month {...{ month, year, max, min, busy, almost, onChange, bookings,day }} />
        </div>
    )
}