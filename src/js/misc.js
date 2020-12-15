import $ from 'jquery'; 
// import Calendar from './calendar.js';
import DatePicker from './DatePicker';


$('#add-guests').on('click', function(e){
	e.preventDefault();

	var $guests = $('#guests-list');
	var guests = $guests.find('input').length;

	$(this).find('small').text((guests || 1) + '/25');

	if(guests < 25){
		$guests.append('<label class="form-group"><span>Nombre: </span><input type="text" name="guests[]" /></label>');
	}

	return false;
})


// if(window.calendarconfig){
	// 	window.calendar = new Calendar(window.calendarconfig);
	// }
	
$('.navbar__toggle').on('click', () => $('.navbar__collapse').slideToggle())
new DatePicker('#props-booking input[name="from"]', '#props-booking input[name="to"]');