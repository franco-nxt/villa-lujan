import { render } from 'react-dom';
import React from 'react'
import Lodge from './Lodge';
import Bookings from './Bookings';
import BookUser from './BookUser';
import UsersTable from './UsersTable';

export default {
    lodge({ bookings }) {
        const cols = [['Día', 'date'], ['Turno', 'time_shift'], ['Nombre', 'name']];
        render(<Lodge {...{ rows: bookings, cols }} />, document.querySelector('#lodge-info'));
    },
    bookings({ bookings, properties }) {
        const rows = bookings.map(b => ({ props: properties.filter(p => p.booking_id == b.id).map(b => b.name).join(', '), ...b }))
        const cols = [['Propiedades', 'props'], ['Nombre', 'occupant_name'], ['Telefono', 'occupant_tel'], ['Desde', 'date_in'], ['Hasta', 'date_out']];

        render(<Bookings {...{ rows, cols }} />, document.querySelector('#props-info'));
    },
    bookUser(props){
        render(<BookUser {...props}/>, document.querySelector('#input-props'))
    },
    usersTable(props){
        render(<UsersTable {...props}/>, document.querySelector('#users-table'))
    }
}
