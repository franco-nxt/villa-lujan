import React from 'react'
import Calendar from './Calendar'

const Property = ({ name, id }) => {
	return (
		<label htmlFor={'_' + id} key={id} className="form-checkbox">
			<input type="checkbox" id={'_' + id} value={id} name="props[]" /> <span>{name}</span>
		</label>
	)
}
const RawHTML = ({ children, className = '' }) => (
	<div
		className={className}
		dangerouslySetInnerHTML={{ __html: children.replace(/\n/g, '<br />') }}
	/>
)

export default ({
	properties,
	user,
	errors,
	success,
	bookings,
	currentMonth,
	currentYear,
	max,
	min,
	busy,
	almost
}) => {
	const [day, setDay] = React.useState('')
	const [guests, setGuests] = React.useState([])

	const setInput = (i, v) => {
		setGuests(Object.assign([...guests], { [i]: v }))
	}
	const addGuest = e => {
		e.preventDefault()
		if (guests.length < 13) {
			setGuests(a => a.concat(['']))
		}
	}

	return (
		<div>
			<div className="row">
				<div className="col-sm-6">
					<div className="header">
						<p className="header__msg">
							Bienvenido {user.lastname}, {user.name}
						</p>
						<h1 className="header__title">
							Reservá tu turno <br />
							para el Quincho
						</h1>
					</div>
					<RawHTML>{errors}</RawHTML>
					<RawHTML>{success}</RawHTML>
				</div>
				<div className="col-sm-6">
					<Calendar
						{...{
							bookings,
							currentMonth,
							currentYear,
							max,
							min,
							busy,
							almost,
							onChange: d => setDay(d),
							day
						}}
					/>
					<div className="calendar__samples">
						<span className="calendar__sample">
							<img src="/bookings/images/sample-busy.png" alt="" /> Ocupado
						</span>
						<span className="calendar__sample">
							<img src="/bookings/images/sample-free.png" alt="" /> Disponible
						</span>
						<span className="calendar__sample">
							<img src="/bookings/images/sample-half-busy.png" alt="" /> Disponible
							1/2 turno
						</span>
					</div>
				</div>
			</div>
			<div className="fieldset clear">
				<div className="form-group-collection">
					<div className="clear">
						{properties.length ? (
							properties.map(props => <Property {...props} />)
						) : (
							<h3>No hay propiedades para alquilar.</h3>
						)}
					</div>
					<label className="form-group">
						<span>Turno :</span>
						{day && (
							<select name="time">
								{(booking =>
									(booking && booking.time_shift != 0) || !booking ? (
										<option value="0">Mediodia</option>
									) : null)(bookings.find(b => b.time == day.getTime()))}
								{(booking =>
									(booking && booking.time_shift != 1) || !booking ? (
										<option value="1">Noche</option>
									) : null)(bookings.find(b => b.time == day.getTime()))}
							</select>
						)}
					</label>
					<label className="form-group">
						<span>Nombre :</span>
						<input
							type="text"
							name="name"
							value={[user.lastname, user.name].join(', ')}
							readOnly
						/>
					</label>
					<label className="form-group">
						<span>Dia :</span>
						{day && (
							<input
								type="text"
								name="date"
								id="date"
								readOnly
								value={day.toLocaleDateString()}
							/>
						)}
					</label>
					<button className="form-btn">RESERVAR</button>
					<div className="clear">
						<a href="#" className="form-link add-people" onClick={addGuest}>
							AGREGAR INVITADOS <small>{guests.length}/13</small>
						</a>
					</div>
					<div className="clear">
						{guests.map((guest, x) => (
							<label key={x} className="form-group">
								<span>Nombre: </span>
								<input
									type="text"
									name="guests[]"
									value={guest}
									onChange={e => setInput(x, e.target.value)}
								/>
							</label>
						))}
					</div>
				</div>
			</div>
		</div>
	)
}
