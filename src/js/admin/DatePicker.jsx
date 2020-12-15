import React from "react";
import './DatePicker.scss';

function padding0(num, digit) {
    var zero = '';
    for (var i = 0; i < digit; i++) {
        zero += '0';
    }
    return (zero + num).slice(-digit);
}

function useOutsideClick(ref, onClickOutside) {

    const handleClickOutside = event => {
        if (ref.current && !ref.current.contains(event.target)) {
            onClickOutside(event)
        }
    }

    React.useEffect(() => {
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    });
}

function OutsideAlerter({ children, onClickOutside }) {
    const wrapperRef = React.useRef(null);
    useOutsideClick(wrapperRef, onClickOutside);

    return <div ref={wrapperRef}>{children}</div>;
}

let MONTHS = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
let DAYS_SHORT = ['DO', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA'];

export default class DayPicker extends React.Component {
    constructor(props) {
        super(props);

        const now = new Date();

        this.state = {
            active: '',
            show: false,
            value: '',
            date: now.getDate(),
            month: now.getMonth(),
            today: new Date(now.getFullYear(), now.getMonth(), now.getDate()),
            year: now.getFullYear()
        };
    }

    static isSameDay(a, b) {
        return a && b && a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
    }

    get days() {
        const { month, year } = this.state;
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const days = [];
        const offset = new Date(year, month, 1).getDay();
        if (offset < 7) {
            for (let i = 0; i < offset; i++) {
                days.push(null);
            }
        }
        for (let i = 1; i <= daysInMonth; i++) {
            days.push(new Date(year, month, i));
        }
        return days;
    }

    get weeks() {
        const days = this.days;
        const weeks = [];
        const weekCount = Math.ceil(days.length / 7);
        for (let i = 0; i < weekCount; i++) {
            weeks.push(days.slice(i * 7, (i + 1) * 7));
        }
        return weeks;
    }

    longMonthName(month) {
        if (this.props.monthNames) {
            return this.props.monthNames[month];
        }

        return MONTHS[month];
    }

    shortDayName(dayOfWeek) {
        if (this.props.shortDayNames) {
            return this.props.shortDayNames[dayOfWeek];
        }

        return DAYS_SHORT[dayOfWeek];
    }

    previousMonth = () => {
        const { month, year } = this.state;

        this.setState({
            month: month !== 0 ? month - 1 : 11,
            year: month !== 0 ? year : year - 1
        });
    };

    nextMonth = () => {
        const { month, year } = this.state;

        this.setState({
            month: month !== 11 ? month + 1 : 0,
            year: month !== 11 ? year : year + 1
        });
    };

    renderDay = (day, index) => {
        const { active, month, today, year } = this.state;
        const { to, from } = this.props;

        const isToday = day && day === today;
        const isActive = active && day && DayPicker.isSameDay(active, day);
        let enable = (to ? day < to : 1) && (from ? day > from : 1);

        let className = ["day", isActive ? "active" : null, !day ? "empty" : null, isToday ? "today" : null, enable ? false : 'disable'].filter(v => v).join(" ")
        let key = [year, month, index].join('.');


        let onClick = e => {
            e.stopPropagation()
            if (day && enable) {
                this.props.onChange && this.props.onChange(day);
                let value = [padding0(day.getDate(), 2), padding0(day.getMonth() + 1, 2), padding0(day.getFullYear())].join('/');
                this.setState({ value })
            }
            this.setState({ active: day, show: false })
            return false;
        }

        return <td {...{ key, className, onClick }}><span>{day ? day.getDate() : ""}</span></td>;
    };

    render() {
        var { month, year, show, value } = this.state;

        return (
            <OutsideAlerter onClickOutside={() => this.setState({ show: false })}>
                <span>{this.props.children}</span>
                <input type="text" id="from-date" name={this.props.name} readOnly={true} onFocus={() => this.setState({ show: true })} value={value} />
                {show &&
                    <div className="react-daypicker-root" onClick={e => e.stopPropagation()}>
                        <div className="header">
                            <div className="previous-month" onClick={this.previousMonth}>◀</div>
                            <div className="month-year">{MONTHS[month]} {year}</div>
                            <div className="next-month" onClick={this.nextMonth}>▶</div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    {DAYS_SHORT.map(name => <th scope="col" key={name}><abbr title={name}>{name}</abbr></th>)}
                                </tr>
                            </thead>
                            <tbody>{this.weeks.map((days, index) => <tr key={`${year}.${month}.week.${index}`}>{days.map(this.renderDay)}</tr>)}</tbody>
                        </table>
                    </div>
                }
            </OutsideAlerter>
        );
    }
}