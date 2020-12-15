import React from 'react';
import DatePicker from './DatePicker'

class Lodge extends React.Component {
	constructor(props) {
		super(props);
		this.state = { from: null, to: null, sort: '', search: '', ...props.rows[0] };
	}

	get rows() {
		let { from, to, sort, search } = this.state;
		let { rows, cols } = this.props;

		return rows.filter(({ date }) => (d => (from ? d >= from : true) && (to ? d <= to : true))(new Date(date.replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$2/$1/$3")))).filter(row => cols.some(col => (row[col[1]] + '').toLowerCase().indexOf(search) != -1)).sort(this.dynamicSort(sort));
	}

	render() {
		let { date, time_shift, name, props, guests, id, sort, from, to } = this.state;

		return (
			<div>
				<div className="booking">
					<h1 className="booking__detail__title">Día {date} <small>Turno {time_shift}</small>   </h1>
					<div className="booking__detail">
						<h3 className="booking__detail__title">Ocupante: {name}</h3>
						{props.length ? <h3 className="booking__detail__title">Propiedades: <small><i>{props.map(p => p.name).join(', ')}</i></small></h3> : null}
					</div>
					<div className="booking__occupant">
						<h3 className="booking__detail__title">Invitados</h3>
						{guests.length ? <ol>{guests.map((li, i) => <li key={i}><strong>{i + 1}.</strong>{li}</li>)}</ol> : <u>No hay invitados registrados.</u>}
					</div>
				</div>
				<div className="booking__search">
					<label className="form-group">
						<span>Buscar :</span>
						<input type="text" onChange={({ target }) => this.setState({ search: target.value.trim().toLowerCase() })} />
					</label>
					<div className="form-group">
						<DatePicker to={to} onChange={date => this.setState({ from: date })} value={from} name="from">Desde :</DatePicker>
					</div>
					<div className="form-group">
						<DatePicker from={from} onChange={date => this.setState({ to: date })} value={to} name="to">Hasta :</DatePicker>
					</div>
				</div>
				<div className="table-responsive">
					<table className="table table-hover">
						<thead>
							<tr>
								{this.props.cols.map(([value, key]) => <th key={key} onClick={() => this.setState({ sort: sort == key ? '-' + key : key })}>{value}</th>)}
							</tr>
						</thead>
						<tbody>
							{this.rows.map((row, key) => <tr key={key} className={id == row.id ? 'active' : null} onClick={() => this.setState(row)}>
								{this.props.cols.map(a => a[1]).map(key => <td key={key}>{row[key]}</td>)}
							</tr>)}
						</tbody>
					</table>
				</div>
			</div>
		)
	}

	dynamicSort(property) {
		var sortOrder = 1;

		if (property[0] === "-") {
			sortOrder = -1;
			property = property.substr(1);
		}

		return (a, b) => sortOrder == -1 ? (b[property] + '').localeCompare(a[property] + '') : (a[property] + '').localeCompare(b[property] + '');
	}
}

export default Lodge;