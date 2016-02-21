var colorpick = new Array();

function connectionDelete(id, newConnection)
{
	if(newConnection){
		var aid = document.getElementById('newCalendarAid' + id).value;
	    
		var row = document.getElementById('newCalendar' + id);
	    row.parentNode.removeChild(row);
	    
	    //add almanach to selection list
		for(var i = 0; i < document.getElementById('calendarSelection').options.length; i ++){
			if(document.getElementById('calendarSelection').options[i].value == aid){
				document.getElementById('calendarSelection').options[i].style.display = "block";
				break;
			}	
		}
	} else {
		if(confirm("Soll dieser Termin wirklich nicht in den Kalender eingetragen werden?"))
		{
			var aid = document.getElementById('almanachId' + id).value;
			
			//delete line in table
			document.getElementById('Calendar'+ id).style.display = "none";
			
			document.getElementById('connectionDeleted' + id).value = "1";
		
			//add almanach to selection list
			for(var i = 0; i < document.getElementById('calendarSelection').options.length; i ++){
				if(document.getElementById('calendarSelection').options[i].value == aid){
					document.getElementById('calendarSelection').options[i].style.display = "block";
					break;
				}	
			}
			document.getElementById("calendarSelection").selectedIndex = "0";		
		}
	}
}

function calendarInput()
{
	var e = document.getElementById("calendarSelection");
	var aid = e.options[e.selectedIndex].value;
	var almanachName = e.options[e.selectedIndex].text;
	var newId = parseInt(document.getElementById('newCalendarsNum').value);
	var allowDateColoring = document.getElementById('allowDateColoring').value;
	
	if(aid <= 0)
		return;
	
	table = document.getElementById('calendarlist').getElementsByTagName('tbody')[0];
					
	document.getElementById('newCalendarsNum').value = newId + 1;
	var newRow   = table.insertRow(table.rows.length);
	newRow.setAttribute("id", "newCalendar" + newId, 0);
	
	//first cell ist aid input
	var input = document.createElement("input");
	input.type = "text";
	input.value = aid;
	input.style.display = "none";
	input.id = "newCalendarAid" + newId;
	input.setAttribute("name", "newCalendarAid" + newId);
	
	//third cell chooses the color
	var div = document.createElement("div");
	div.className = "z-formrow";
	var colorinput = document.createElement("input");
	colorinput.type = "text";
	colorinput.id = "newCalendarColorinput" + newId;
	colorinput.setAttribute("name", "newCalendarColorinput" + newId);
	colorinput.className = "colorpicker";
	colorinput.size = 7;
	colorinput.maxLength = 7;
	div.appendChild(colorinput);
	
	//last cell, to delete the heredity
	var a = document.createElement("A");
	a.className = "z-button";
	a.onclick = function() { connectionDelete( newId , true); }
	a.innerHTML = document.getElementById('deleteImage').innerHTML;
	
	var cell1 = newRow.insertCell(0);
	var cell2 = newRow.insertCell(1);
	var cell3 = newRow.insertCell(2);
	var cell4 = newRow.insertCell(3);
	
	cell1.appendChild(input);
	cell2.innerHTML = almanachName;
	if(allowDateColoring != 0)
		cell3.appendChild(div);
	cell4.appendChild(a);

	//newRow.innerHTML = returns['newHeredity'];
	
	//delete almanach from dropdown
	for(var i = 0; i < document.getElementById('calendarSelection').options.length; i ++){
		if(document.getElementById('calendarSelection').options[i].value == aid){
			document.getElementById('calendarSelection').options[i].style.display = "none";
			break;
		}
	}
	document.getElementById("calendarSelection").selectedIndex = "0";
	
	//Colorpicker erstellen:
	var name =  "newCalendarColorinput";
	name = name + newId;
	colorpick[colorpick.length] = new PickyColor({
		field: name,
		color: '',
		colorWell: name,
		closeText: "Schließen"
	});
}
