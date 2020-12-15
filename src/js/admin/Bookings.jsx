import React from 'react'

export default ({ rows, cols }) => {
    const [active, setActive] = React.useState(rows[0]);
    const [sort, setSort] = React.useState('');
    const [search, setSearch] = React.useState('');

    const dynamicSort = property => {
        var sortOrder = 1;

        if (property[0] === "-") {
            sortOrder = -1;
            property = property.substr(1);
        }

        return (a, b) => sortOrder == -1 ? (b[property] + '').localeCompare(a[property] + '') : (a[property] + '').localeCompare(b[property] + '');
    }

    const _rows = rows.filter(row => cols.some(col => (row[col[1]] + '').toLowerCase().indexOf(search) != -1)).sort(dynamicSort(sort));
    const { date_in, date_out, occupant_name, occupant_dni, occupant_tel, occupant_email, id } = active;

    return (
        <div>
            <div className="booking">
                <div className="booking__detail">
                    <h2 className="booking__detail__title">Reserva</h2>
                    <p className="booking__detail__info"><strong>Check In</strong><span>{date_in}</span></p>
                    <p className="booking__detail__info"><strong>Check Out</strong><span>{date_out}</span></p>
                </div>
                <div className="booking__occupant">
                    <h2 className="booking__detail__title">Inquilino: {occupant_name}</h2>
                    <p className="booking__detail__info"><strong>DNI: </strong><span>{occupant_dni}</span></p>
                    <p className="booking__detail__info"><strong>Telefono: </strong><span>{occupant_tel}</span></p>
                    <p className="booking__detail__info"><strong>Email: </strong><span>{occupant_email}</span></p>
                </div>
            </div>
            <div className="booking__search">
                <label className="form-group">
                    <span>Buscar :</span>
                    <input type="text" onChange={e => setSearch(e.target.value)} />
                </label>
            </div>
            <div className="table-responsive">
                <table className="table table-hover">
                    <thead>
                        <tr>
                            {cols.map(([value, key]) => <th key={key} onClick={() => setSort(b => b == key ? '-' + key : key)}>{value}</th>)}
                        </tr>
                    </thead>
                    <tbody>
                        {_rows.map((row, key) =>
                            <tr key={key} className={id == row.id ? 'active' : null} onClick={() => setActive(row)}>
                                {cols.map(a => a[1]).map(key => <td key={key}>{row[key]}</td>)}
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    )
}