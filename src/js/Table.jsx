import React, { Component, createElement } from 'react';

var e = createElement;

class Table extends Component {
    constructor(props) {
        super(props);
        this.state = {sort: '', search: '', ...props.rows[0]};
    }
    render() {
        var rows = this.props.rows.filter(row => this.props.cols.some(col => (row[col[1]] + '').toLowerCase().indexOf(this.state.search) != -1  )).sort(this.dynamicSort(this.state.sort));
        return <div>
            <div className="booking">
                <div className="booking__detail">
                    <h2 className="booking__detail__title">Reserva</h2>
                    <p className="booking__detail__info"><strong>Check In</strong><span>{this.state.date_in}</span></p>
                    <p className="booking__detail__info"><strong>Check Out</strong><span>{this.state.date_out}</span></p>
                </div>
                <div className="booking__occupant">
                    <h2 className="booking__detail__title">Inquilino: {this.state.occupant_name}</h2>
				    <p className="booking__detail__info"><strong>DNI: </strong><span>{this.state.occupant_dni}</span></p>
				    <p className="booking__detail__info"><strong>Telefono: </strong><span>{this.state.occupant_tel}</span></p>
				    <p className="booking__detail__info"><strong>Email: </strong><span>{this.state.occupant_email}</span></p>
                </div>
            </div>    
            <div className="booking__search">
                <label class="form-group">
                    <span>Buscar :</span>
                    {e('input', {type: 'text', onChange: e => this.search(e)})}
                </label>
            </div>            
            <div className="table-responsive">
                <table className="table table-hover">
                    <thead>
                        <tr>
                            {this.props.cols.map(col => e('th', {onClick: () => this.sort(col[1])}, col[0]))}
                        </tr>
                    </thead>
                    <tbody>
                        {rows.map((row, key) => e('tr', { key, class: this.state.id == row.id ? 'active' : null }, this.props.cols.map(col => e('td', {onClick: () => this.setState(row)}, row[col[1]]))))}
                    </tbody>
                </table>
            </div>
        </div>
    }

    search(){
        let search = event.target.value.trim().toLowerCase();
        this.setState({ search });
    }

    sort(a){
        var sort = this.state.sort == a ? '-' + a : a;
        this.setState({ sort }); 
    }
    
    dynamicSort(property) {
        var sortOrder = 1;

        if(property[0] === "-") {
            sortOrder = -1;
            property = property.substr(1);
        }

        return function (a,b) {
            if(sortOrder == -1){
                return (b[property]+ '').localeCompare(a[property]+ '');
            }else{
                return (a[property]+ '').localeCompare(b[property]+ '');
            }        
        }
    }
}

export default Table;