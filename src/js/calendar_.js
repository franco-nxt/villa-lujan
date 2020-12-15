import $ from 'jquery'; 

function Calendar(config){

	this.today = new Date();
	this.currentMonth = this.today.getMonth();
	this.currentYear = this.today.getFullYear();
	this.max = new Date(config.max);
	this.min = new Date(config.min);

	this.busy = config.bookings.filter((a,b) => config.bookings.some(d => d.date == a.date && d.time_shift != a.time_shift )).map(a => Date.parse(a.date));
	this.almost = config.bookings.filter((a,b) => !config.bookings.some(d => d.date == a.date && d.time_shift != a.time_shift )).map(a => Date.parse(a.date));

	var $controls = $('<div />', {class: 'calendar__control'});

	this.$input = config.$input;
	this.$prev = $('<button />', {class: 'calendar__prev'}).on('click', () => this.prev()).appendTo($controls);
	this.$month = $('<span />', {class: 'calendar__span'}).appendTo($controls);
	this.$next = $('<button />', {class: 'calendar__next'}).on('click', () => this.next()).appendTo($controls);
	this.$body = $(config.$el);
	this.$days = this.$body.find('.calendar__day');
	
	$controls.appendTo(config.$el).children().wrap('<div></div>');
	
	['DO', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA'].forEach(a => $('<div />', {class: ['calendar__col calendar__week'], text: a}).appendTo(this.$body) )

	this.months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

	this.showCalendar(this.currentMonth, this.currentYear);
}

Calendar.prototype = {
	constructor: Calendar,

	showCalendar: function(month, year) {

		let firstDay = (new Date(year, month)).getDay();
		let daysInMonth = 32 - new Date(year, month, 32).getDate();

		this.$body.find(".calendar__col:not(.calendar__week)").remove();

		this.$month.html(this.months[month] + " " + year);

		let date = 1;
		let maxDate = this.max.getTime();
		let minDate = this.min.getTime();

		for (let i = 0; i < 6; i++) {
			for (let j = 0; j < 7; j++) {
				if (i === 0 && j < firstDay) {
					this.$body.append($('<div />', {class: 'calendar__col'}))
				}
				else if (date > daysInMonth) {
					break;
				}
				else {
					let day = new Date(year, month, date);
					let time = day.getTime();
					let $wrap = $('<div />', {class: 'calendar__col'});
					let $day = $('<label />', {for: '_' + time, class : 'calendar__day', text: date}).on('click', () => $(this.$input).val(day.toLocaleDateString()));
					let available = true;
					this.$days = this.$days.add($day);

					if(maxDate && time > maxDate || (minDate && time < minDate)){
						$day.addClass('calendar__day-disabled');
						available = false;
					}
					else if(this.busy.indexOf(time) !== -1){
						$day.addClass('calendar__day-busy');
						available = false;
					}
					else if(this.almost.indexOf(time) !== -1){
						$day.addClass('calendar__day-half-busy');
						available = true;
					}

					if(available){
						$wrap.append($('<input />', {id: '_' + time, name: 'calendar', type: 'radio', value: time}))
					}

					this.$body.append( $wrap.append($day) )
					date++;
				}
			}
		}
	},

	next: function() {
		this.currentYear = (this.currentMonth === 11) ? this.currentYear + 1 : this.currentYear;
		this.currentMonth = (this.currentMonth + 1) % 12;
		this.showCalendar(this.currentMonth, this.currentYear);
		return false;
	},
	prev: function() {
		this.currentYear = (this.currentMonth === 0) ? this.currentYear - 1 : this.currentYear;
		this.currentMonth = (this.currentMonth === 0) ? 11 : this.currentMonth - 1;
		this.showCalendar(this.currentMonth, this.currentYear);
		return false;
	}
}


export default Calendar;
