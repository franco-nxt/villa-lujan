import { Component, createElement } from 'react';
import { render } from 'react-dom';

var e = createElement;

class Row_ extends Component{

	constructor(props){
		super(props);
	}

	render(){

		var properties = this.props.properties.map((p, key) => e('span', { key }, p.name));
		
		var cells = [
			properties,
			this.props.occupant_name,
			this.props.occupant_tel,
			this.props.date_in,
			this.props.date_out
		];

		return e('tr', {onClick: this.props.onClick, className: this.props.className }, cells.map((a, key) => e('td', { key }, a)))
	}
}

class Container_ extends Component {

	constructor(props) {
		super(props);
		this.state = props.bookings[0];
	}

	render(){

		var properties = this.props.properties.filter(p => p.booking_id == this.state.id).map((p, key) => e('span', { key }, p.name));
		var cols = ['Propiedades', 'Nombre', 'Telefono', 'Desde', 'Hasta'].map((a, key) => e('th', { key }, a));
		var thead = e('tr', null, cols);
		var tbody = this.props.bookings.map((booking, key) => e(Row_, { className: this.state.id == booking.id ? 'active' : null, key, properties: this.props.properties.filter( p => p.booking_id == booking.id ), ...booking, onClick: () => this.setState(booking) }));

		return e('div', null,
			e('div', {className: 'booking'},
				e('div', {className: 'booking__detail'},
					e('h2', {className: 'booking__detail__title'}, 'Reserva'),
					e('p', {className: 'booking__detail__info'}, e('strong', null, 'Check In: '), e('span', null, this.state.date_in)),
					e('p', {className: 'booking__detail__info'}, e('strong', null, 'Check In: '), e('span', null, this.state.date_out)),
					this.state.observations && e('p', {className: 'booking__detail__info'}, e('strong', null, 'Patente Auto: '), e('span', null, this.state.observations)),
					e('p', {className: 'booking__detail__info'}, e('strong', null, 'Propiedades: '), properties),
					window.watcher ? null : e('form', {method: 'POST', action: 'eliminar' }, e('button', {className: 'form-btn  form-btn-del', name: 'delete', value: this.state.id}, null, 'ELIMINAR RESERVA')),

				),
				e('div', {className: 'booking__occupant'},
					e('h2', {className: 'booking__detail__title'}, 'Inquilino: ' + this.state.occupant_name),
					e('p', {className: 'booking__detail__info'}, e('strong', null, 'DNI: '), e('span', null, this.state.occupant_dni)),
					e('p', {className: 'booking__detail__info'}, e('strong', null, 'Telefono: '), e('span', null, this.state.occupant_tel)),
					e('p', {className: 'booking__detail__info'}, e('strong', null, 'Email: '), e('span', null, this.state.occupant_email))
				),
			),
			e('div', {className: 'table-responsive table-bookings'}, 
				e('table', {className: 'table table-hover'}, Object.entries({tbody, thead}).map((a,key) => e(a[0], { key }, a[1])))
			)
		)
	}
}

if(window.bookings){
	render(e(Container_, bookings), document.querySelector('#props-info'));
}