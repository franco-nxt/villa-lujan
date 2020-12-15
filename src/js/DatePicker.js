import $ from 'jquery'; 

Date.prototype.addDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}

Date.prototype.subDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() - days);
    return date;
}

function DatePicker($from, $to){
	let monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
	let dayNamesMin = ['DO', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA'];
	let dateFormat = "dd/mm/yy";

	this.dateFormat = dateFormat;

	let beforeShowDay = date => { 
		let time = date.getTime();
		let to = this.$to.val() ? this.$to.datepicker('getDate').getTime() : 0;
		let from = this.$from.val() ? this.$from.datepicker('getDate').getTime() : 0;
		
		return [true, time == from || time <= to && time >= from ? 'ui-datepicker-range' : '' ];
	};

	this.$from = $($from).datepicker({minDate: 0, monthNames, dayNamesMin, dateFormat, beforeShowDay}).on('change', (e) => this.from(e.target.value) );
	this.$to = $($to).datepicker({monthNames, dayNamesMin, dateFormat, beforeShowDay}).on('change', (e) => this.to(e.target.value) );
}

DatePicker.prototype = {
	constructor: DatePicker,
	from: function(value) {
		this.$to.datepicker('option', 'minDate' , $.datepicker.parseDate( this.dateFormat, value ).addDays(1));
	},
	to: function(value) {
		this.$from.datepicker('option', 'maxDate' , $.datepicker.parseDate( this.dateFormat, value ).subDays(1));
	}
}

export default DatePicker;
