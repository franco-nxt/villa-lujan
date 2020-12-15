import { Component, createElement } from 'react';
import { render } from 'react-dom';

var e = createElement;

class Container_ extends Component {

	constructor(props) {
		super(props);
		this.state = { sort : props.cols[0][1] }
	}

	render(){

		var thead = e('tr', null, this.props.cols.map((a, key) => e('th', { key, onClick: () => this.setState({ sort: a[1] }) }, a[0])));
		var sorted = this.props.rows.sort((a,b) => (a[this.state.sort] > b[this.state.sort]) ? 1 : ((b[this.state.sort] > a[this.state.sort]) ? -1 : 0)); 
		var tbody = sorted.map((row, key) => e('tr', { key, onClick(){ window.location.replace(row.url) }}, this.props.cols.map((col, key) => e('td', { key }, col.slice(1).map(k => row[k]).join(',')))))

		return e('div', {className: 'table-responsive'},
			e('table', {className: 'table-hover'}, Object.entries({tbody, thead}).map((a,key) => e(a[0], { key }, a[1])))
		)
	}
}

if(window.lodge){
	render(e(Container_, window.lodge), document.querySelector('#lodge-info'));
}