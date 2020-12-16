import { render } from 'react-dom'
import React from 'react'
import $ from 'jquery'

class Table extends React.Component {
	constructor(props) {
		super(props)
		this.state = {
			$from: null,
			$to: null,
			from: null,
			to: null,
			sort: '',
			search: '',
			...props.rows[0]
		}
	}
	componentDidMount() {
		let monthNames = [
			'Enero',
			'Febrero',
			'Marzo',
			'Abril',
			'Mayo',
			'Junio',
			'Julio',
			'Agosto',
			'Septiembre',
			'Octubre',
			'Noviembre',
			'Diciembre'
		]
		let dayNamesMin = ['DO', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA']
		let dateFormat = 'dd/mm/yy'

		var $from = $('input#from-date')
			.datepicker({ monthNames, dayNamesMin, dateFormat })
			.on('change', e => this.from(e))
		var $to = $('input#to-date')
			.datepicker({ monthNames, dayNamesMin, dateFormat })
			.on('change', e => this.to(e.target.value))

		this.setState({ $from, $to })
	}
	render() {
		var rows = this.props.rows
			.filter(r =>
				((from, to, date) =>
					(from ? date.getTime() >= from : true) && (to ? date.getTime() <= to : true))(
					this.state.from,
					this.state.to,
					new Date(r.date.replace(/(\d{2})\/(\d{2})\/(\d{4})/, '$2/$1/$3'))
				)
			)
			.filter(x => true)
			.filter(row =>
				this.props.cols.some(
					col => (row[col[1]] + '').toLowerCase().indexOf(this.state.search) != -1
				)
			)
			.sort(this.dynamicSort(this.state.sort))
		return (
			<div>
				<div className="booking">
					<h1 className="booking__detail__title">
						Día {this.state.date} <small>Turno {this.state.time_shift}</small>{' '}
					</h1>
					<div className="booking__detail">
						<h3 className="booking__detail__title">Ocupante: {this.state.name}</h3>
						<h3 className="booking__detail__title">
							Propiedades:{' '}
							<small>
								<i>{this.state.props.map(({ name }) => name).join(',')}</i>
							</small>
						</h3>
					</div>
					<div className="booking__occupant">
						<h3 className="booking__detail__title">Invitados</h3>
						{this.state.guests.length ? (
							<ol>
								{this.state.guests.map((li, i) => (
									<li key={i}>
										<strong>{i + 1}.</strong>
										{li}
									</li>
								))}
							</ol>
						) : (
							<u>No hay invitados registrados.</u>
						)}
					</div>
				</div>
				<div className="booking__search">
					<label className="form-group">
						<span>Buscar :</span>
						<input
							type="text"
							onChange={e =>
								this.setState({ search: event.target.value.trim().toLowerCase() })
							}
						/>
					</label>
					<label className="form-group">
						<span>Desde :</span>
						<input type="text" id="from-date" />
					</label>
					<label className="form-group">
						<span>Hasta :</span>
						<input type="text" id="to-date" />
					</label>
				</div>
				<div className="table-responsive">
					<table className="table table-hover">
						<thead>
							<tr>
								{this.props.cols.map(([value, key]) => (
									<th key={key} onClick={e => this.sort(key)}>
										{value}
									</th>
								))}
							</tr>
						</thead>
						<tbody>
							{rows.map((row, key) => (
								<tr
									key={key}
									className={this.state.id == row.id ? 'active' : null}
									onClick={() => this.setState(row)}
								>
									{this.props.cols
										.map(a => a[1])
										.map(key => (
											<td key={key}>{row[key]}</td>
										))}
								</tr>
							))}
						</tbody>
					</table>
				</div>
			</div>
		)
	}
	from(value) {
		var from = null
		if (value) {
			from = $.datepicker.parseDate('dd/mm/yy', value)
			this.state.$to.datepicker('option', 'minDate', from.addDays(1))
		}
		this.setState({ from })
	}
	to(value) {
		var to = null
		if (value) {
			to = $.datepicker.parseDate('dd/mm/yy', value)
			this.state.$from.datepicker('option', 'maxDate', to.subDays(1))
		}
		this.setState({ to })
	}

	sort(a) {
		var sort = this.state.sort == a ? '-' + a : a
		this.setState({ sort })
	}

	dynamicSort(property) {
		var sortOrder = 1

		if (property[0] === '-') {
			sortOrder = -1
			property = property.substr(1)
		}

		return (a, b) =>
			sortOrder == -1
				? (b[property] + '').localeCompare(a[property] + '')
				: (a[property] + '').localeCompare(b[property] + '')
	}
}

// if (window['admin-lodge-booking-info']) {
// 	render(<Table {...{ rows: window['admin-lodge-booking-info'].bookings, cols: [['Día', 'date'], ['Turno', 'time_shift'], ['Nombre', 'name']] }} />, document.querySelector('#lodge-info'));
// }
