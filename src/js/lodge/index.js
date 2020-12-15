import { render } from 'react-dom';
import React from 'react'
import BookingForm from './BookingForm';

export default {
	calendar(props, qs) {
		const today = new Date();
		const currentMonth = today.getMonth();
		const currentYear = today.getFullYear();
		const max = new Date(props.max);
		const min = new Date(props.min);
		const bookings = props.bookings.map(b => (date => Object.assign(b, { date, time: date.getTime() }))(new Date(b.date)))

		render(<BookingForm {...{ ...props, bookings, currentMonth, currentYear, max, min }} />, document.querySelector(qs));
	}
}
