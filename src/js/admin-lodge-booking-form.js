import { render } from 'react-dom'
import React, { Component, createElement } from 'react'

var e = createElement
class Container extends Component {
	constructor(props) {
		super(props)
		this.state = { user: props.users[0].id }

		this.handleChange = this.handleChange.bind(this)
	}

	handleChange(event) {
		this.setState({ user: event.target.value })
	}

	render() {
		return (
			<div>
				<label class="form-group">
					<span>Usuario :</span>
					<select name="user" onChange={this.handleChange}>
						{this.props.users.map(user => (
							<option value={user.id}>{user.name}</option>
						))}
					</select>
				</label>
				<div className="clear">
					{this.props.props
						.filter(prop => prop.user_id == this.state.user)
						.map(prop =>
							e(
								'label',
								{ for: '_' + prop.id, class: 'form-checkbox' },
								e('input', {
									type: 'checkbox',
									id: '_' + prop.id,
									value: prop.id,
									name: 'props[]'
								}),
								e('span', null, prop.name)
							)
						)}
				</div>
			</div>
		)
	}
}

if (window['admin-props-booking-form']) {
	render(
		createElement(Container, window['admin-props-booking-form']),
		document.querySelector('#input-props')
	)
}
