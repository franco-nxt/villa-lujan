import React from 'react'
import DatePicker from './DatePicker'

export default ({ users, properties }) => {
    const [user, setUser] = React.useState(users[0].id);
    // const [from, setFrom] = React.useState(null);
    // const [to, setTo] = React.useState(null);

    return (
        <div>
            <div className="clear">
                <label className="form-group">
                    <span>Usuario :</span>
                    <select name="user" onChange={e => setUser(e.target.value)}>
                        {users.map(({ id, name }) => <option key={id} value={id}>{name}</option>)}
                    </select>
                </label>
                <div className="clear">
                    {properties.filter(prop => prop.user_id == user).map(prop =>
                        <label htmlFor={'_' + prop.id} className="form-checkbox" key={prop.id}>
                            <input type="checkbox" id={'_' + prop.id} name="props[]" value={prop.id} />
                            <span>{prop.name}</span>
                        </label>
                    )}
                </div>
            </div>
            {/* <div className="form-group">
                <DatePicker to={to} onChange={date => setFrom(date)} value={from} name="from">Desde :</DatePicker>
            </div>
            <div className="form-group">
                <DatePicker from={from} onChange={date => setTo(date)} value={to} name="to">Hasta :</DatePicker>
            </div> */}
        </div>
    )
}