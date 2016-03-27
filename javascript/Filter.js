function showFilter(){
	document.getElementById('filterbody').style.display = 'block';
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

function filterSelectAll(){
	var groupnam = parseInt(document.getElementById('GroupNum').value);
	
	for(var i = 0; i < groupnam; i ++){
		var element = document.getElementById('Group' + i);
		
		if(element){
			element.checked = true;
		}
	}
}

function filterSelectNone(){
	var groupnam = parseInt(document.getElementById('GroupNum').value);
	
	for(var i = 0; i < groupnam; i ++){
		var element = document.getElementById('Group' + i);
		
		if(element){
			element.checked = false;
		}
	}
}

function filterRun(){
	var groupnam = parseInt(document.getElementById('GroupNum').value);
	for(var i = 0; i < groupnam; i ++){
		var element = document.getElementById('Group' + i);
		
		if(element){
			var state;
			
			if(element.checked)
				state = "";
			else
				 state = "none";
			var dates = document.getElementsByClassName('group' + element.value);
			
			for(var j = 0; j < dates.length; j ++){
				dates[j].style.display = state;
			}
		}
	}
}

function selectTime(param){
	document.getElementById('selectTime0').className="z-button";
	document.getElementById('selectTime1').className="z-button";
	document.getElementById('selectTime2').className="z-button";
	document.getElementById('selectTime3').className="z-button";
	
	document.getElementById('selectTime' + param).className="z-button selectedTime";
	
	switch(param){
		case 0:
			var dates = document.getElementsByClassName('notToday');
			
			for(var j = 0; j < dates.length; j ++){
				dates[j].style.display = 'none';
			}
			break;
		case 1:
			var dates = document.getElementsByClassName('notToday');
			
			for(var j = 0; j < dates.length; j ++){
				dates[j].style.display = '';
			}
			
			var dates = document.getElementsByClassName('notThisWeek');
			
			for(var j = 0; j < dates.length; j ++){
				dates[j].style.display = 'none';
			}
			break;
			
		case 2:
			var dates = document.getElementsByClassName('notToday');
			
			for(var j = 0; j < dates.length; j ++){
				dates[j].style.display = '';
			}
			
			var dates = document.getElementsByClassName('notThisMonth');
			
			for(var j = 0; j < dates.length; j ++){
				dates[j].style.display = 'none';
			}
			break;
		case 3:
			var dates = document.getElementsByClassName('notToday');
			
			for(var j = 0; j < dates.length; j ++){
				dates[j].style.display = '';
			}
			
			var dates = document.getElementsByClassName('notThisYear');
			
			for(var j = 0; j < dates.length; j ++){
				dates[j].style.display = 'none';
			}
			break;
		case 4:
			var dates = document.getElementsByClassName('notToday');
			
			for(var j = 0; j < dates.length; j ++){
				dates[j].style.display = '';
			}
			break;
		
	}
}
