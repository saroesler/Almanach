var colorpick = new Array();

function heredityDelete(id, newHeredity)
{
	if(newHeredity){
		var caid = document.getElementById('newHeredityCaid' + id).value;
	    
		var row = document.getElementById('newHeredity' + id);
	    row.parentNode.removeChild(row);
	    
	    //add almanach to selection list
		for(var i = 0; i < document.getElementById('HereditySelection').options.length; i ++){
			if(document.getElementById('HereditySelection').options[i].value == caid){
				document.getElementById('HereditySelection').options[i].style.display = "block";
				break;
			}	
		}
	} else {
		if(confirm("Soll die Vererbung wirklich gelöscht werden? Es werden dann alle Termine, die bisher aus dem untergrordneten Kalender kamen aus diesem Kalender gelöscht."))
		{
			var caid = document.getElementById('heredityCaid' + id).value;
			
			//delete line in table
			document.getElementById('Heredity'+ id).style.display = "none";
			
			document.getElementById('heredityDeleted' + id).value = "1";
		
			//add almanach to selection list
			for(var i = 0; i < document.getElementById('HereditySelection').options.length; i ++){
				if(document.getElementById('HereditySelection').options[i].value == caid){
					document.getElementById('HereditySelection').options[i].style.display = "block";
					break;
				}	
			}
			document.getElementById("HereditySelection").selectedIndex = "0";		
		}
	}
}

function hereditySave()
{
	var e = document.getElementById("HereditySelection");
	var newId = parseInt(document.getElementById('newHereditiesNum').value);
	var caid = e.options[e.selectedIndex].value;
	var caname = e.options[e.selectedIndex].text;
	if(caid <= 0)
		return;
	
	table = document.getElementById('hereditylist').getElementsByTagName('tbody')[0];
					
	document.getElementById('newHereditiesNum').value = newId + 1;
	var newRow   = table.insertRow(table.rows.length);
	newRow.setAttribute("id", "newHeredity" + newId, 0);
	
	//first cell ist Caid input
	var input = document.createElement("input");
	input.type = "text";
	input.value = caid;
	input.style.display = "none";
	input.id = "newHeredityCaid" + newId;
	input.setAttribute("name", "newHeredityCaid" + newId);
	
	//third cell chooses the color
	var div = document.createElement("div");
	div.className = "z-formrow";
	var colorinput = document.createElement("input");
	colorinput.type = "text";
	colorinput.id = "newHeredityColorinput" + newId;
	colorinput.setAttribute("name", "newHeredityColorinput" + newId);
	colorinput.className = "colorpicker";
	colorinput.size = 7;
	colorinput.maxLength = 7;
	div.appendChild(colorinput);
	
	//last cell, to delete the heredity
	var a = document.createElement("A");
	a.className = "z-button";
	a.onclick = function() { heredityDelete( newId , true); }
	a.innerHTML = document.getElementById('deleteImage').innerHTML;
	
	var cell1 = newRow.insertCell(0);
	var cell2 = newRow.insertCell(1);
	var cell3 = newRow.insertCell(2);
	var cell4 = newRow.insertCell(3);
	
	cell1.appendChild(input);
	cell2.innerHTML = caname;
	cell3.appendChild(div);
	cell4.appendChild(a);

	//newRow.innerHTML = returns['newHeredity'];
	
	//delete almanach from dropdown
	for(var i = 0; i < document.getElementById('HereditySelection').options.length; i ++){
		if(document.getElementById('HereditySelection').options[i].value == caid){
			document.getElementById('HereditySelection').options[i].style.display = "none";
			break;
		}
	}
	document.getElementById("HereditySelection").selectedIndex = "0";
	
	//Colorpicker erstellen:
	var name =  "newHeredityColorinput";
	name = name + newId;
	colorpick[colorpick.length] = new PickyColor({
		field: name,
		color: '',
		colorWell: name,
		closeText: "Schließen"
	});
	
	//set google warning, if there is a google calendar:
	if(document.getElementById("googleApi").value){
		if(document.getElementById('googleCalendarId').value != '')
			document.getElementById('googleHierarchy').style.display = 'block';
	}
}
			
function colorDelete(id, newColor)
{
	if(newColor){
		var gid = document.getElementById('newColorGid' + id).value;
	    
		var row = document.getElementById('newColor' + id);
	    row.parentNode.removeChild(row);
	    
	    //add group to selection list
		for(var i = 0; i < document.getElementById('GroupSelection').options.length; i ++){
			if(document.getElementById('GroupSelection').options[i].value == gid){
				document.getElementById('GroupSelection').options[i].style.display = "block";
				break;
			}	
		}
	} else {
		if(confirm("Soll die Farbe wirklich gelöscht werden? Die Termine erhalten unter Umständen andere Farben."))
		{
			var gid = document.getElementById('colorGid' + id).value;
			
			//delete line in table
			document.getElementById('Color'+ id).style.display = "none";
			
			document.getElementById('colorDeleted' + id).value = "1";
		
			//add group to selection list
			for(var i = 0; i < document.getElementById('GroupSelection').options.length; i ++){
				if(document.getElementById('GroupSelection').options[i].value == gid){
					document.getElementById('GroupSelection').options[i].style.display = "block";
					break;
				}	
			}					
		}
	}
}

function colorSave()
{
	var e = document.getElementById("GroupSelection");
	var gid = e.options[e.selectedIndex].value;
	var groupname = e.options[e.selectedIndex].text;
	var newId = parseInt(document.getElementById('newColorsNum').value);
	if(gid <= 0)
		return;
	
	table = document.getElementById('colorlist').getElementsByTagName('tbody')[0];
					
	
	document.getElementById('newColorsNum').value = newId + 1;
	var newRow   = table.insertRow(table.rows.length);
	newRow.setAttribute("id", "newColor" + newId, 0);
	
	//first cell ist Caid input
	var input = document.createElement("input");
	input.type = "text";
	input.value = gid;
	input.style.display = "none";
	input.id = "newColorGid" + newId;
	input.setAttribute("name", "newColorGid" + newId);
	
	//third cell chooses the color
	var div = document.createElement("div");
	div.className = "z-formrow";
	var colorinput = document.createElement("input");
	colorinput.type = "text";
	colorinput.id = "newGroupColorInput" + newId;
	colorinput.setAttribute("name", "newGroupColorInput" + newId);
	colorinput.className = "colorpicker";
	colorinput.size = 7;
	colorinput.maxLength = 7;
	div.appendChild(colorinput);
	
	//last cell, to delete the color
	var a = document.createElement("A");
	a.className = "z-button";
	a.onclick = function() { colorDelete( newId , true); }
	a.innerHTML = document.getElementById('deleteImage').innerHTML;
	
	var cell1 = newRow.insertCell(0);
	var cell2 = newRow.insertCell(1);
	var cell3 = newRow.insertCell(2);
	var cell4 = newRow.insertCell(3);
	
	cell1.appendChild(input);
	cell2.innerHTML = groupname;
	cell3.appendChild(div);
	cell4.appendChild(a);
	
	//delete almanach from dropdown
	for(var i = 0; i < document.getElementById('GroupSelection').options.length; i ++){
		if(document.getElementById('GroupSelection').options[i].value == gid){
			document.getElementById('GroupSelection').options[i].style.display = "none";
			break;
		}	
	}	
	document.getElementById("GroupSelection").selectedIndex = "0";
	
	//Colorpicker erstellen:
	var name =  "newGroupColorInput";
	name = name + newId;
	var value = document.getElementById(name).value;
	colorpick[colorpick.length] = new PickyColor({
		field: name,
		color: '',
		colorWell: name,
		closeText: "Schließen"
	});
}

function setGoogleRequest(){
	document.getElementById('googleTransfer').style.display = 'block';
}
