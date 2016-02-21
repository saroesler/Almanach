function showCalendarList(){
	document.getElementById('calendarList').style.display = 'block';
	document.getElementById('calendarDown').style.display = 'none';
	document.getElementById('calendarUp').style.display = 'block';
}

function hideCalendarList(){
	document.getElementById('calendarList').style.display = 'none';
	document.getElementById('calendarDown').style.display = 'block';
	document.getElementById('calendarUp').style.display = 'none';
}

function showDateList(){
	document.getElementById('dateList').style.display = 'block';
	document.getElementById('datesDown').style.display = 'none';
	document.getElementById('datesUp').style.display = 'block';
}

function hideDateList(){
	document.getElementById('dateList').style.display = 'none';
	document.getElementById('datesDown').style.display = 'block';
	document.getElementById('datesUp').style.display = 'none';
}
