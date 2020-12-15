import Datagrid from './Table.jsx'
import { render } from 'react-dom';
import { createElement } from 'react';

if(window['admin-props-booking-info']){
    var data = window['admin-props-booking-info'];
    var rows =  data.bookings.map(b => ({props: data.props.filter(p => p.booking_id == b.id).map(b => b.name).join(', ') , ...b}))
    var cols = [['Propiedades', 'props'], ['Nombre', 'occupant_name'], ['Telefono', 'occupant_tel'], ['Desde', 'date_in'], ['Hasta', 'date_out']];

    render(createElement(Datagrid, {rows, cols}), document.querySelector('#props-info'));
}