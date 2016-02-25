function groupDelete(id)
{
	var params = new Object();
	params['id'] = id;
	if(confirm("Soll die Gruppe wirklich gelöscht werden? Es werden dann alle Termine, die mit der Gruppe zusammenhängen werden ebenfalls gelöscht."))
	{
		new Zikula.Ajax.Request(
			"ajax.php?module=Almanach&func=groupDel",
			{
				parameters: params,
				onComplete:	function (ajax) 
				{
					var returns = ajax.getData();
					
					if(returns['id'])
					{
						document.getElementById('Group'+returns['id']).style.display = "none";
					}
				}
			}
		);
	}
}

function groupSave()
{
	var params = new Object();
	params['name'] = document.getElementById('name').value;
	params['color'] = document.getElementById('newGroupColor').value;
	new Zikula.Ajax.Request(
		"ajax.php?module=Almanach&func=groupSave",
		{
			parameters: params,
			onComplete:	function (ajax) 
			{
				var returns = ajax.getData();
				if(returns['text']!="")
					alert(returns['text']);
				if(returns['ok']==1)
				{				
					var table = document.getElementById("grouplist");
					
					var newRow = table.insertRow(table.rows.length -1);
					newRow.id = "Group" + returns['gid'];
					
					// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
					var cell1 = newRow.insertCell(0);
					cell1.innerHTML = returns['gid'];
					
					var cell2 = newRow.insertCell(1);
					var groupNames = document.createElement("span");
					groupNames.id = "nameview" + returns['gid'];
					groupNames.innerHTML = returns['name'];
					
					var nameInput = document.createElement("input");
					nameInput.type = "text";
					nameInput.id = "name" + returns['gid'];
					nameInput.setAttribute("name", "name" + returns['gid']);
					nameInput.maxLength = 200;
					nameInput.style.display = 'none';
					
					cell2.appendChild(nameInput);	
					cell2.appendChild(groupNames);
					
					var cell3 = newRow.insertCell(2);
					
					//third cell chooses the color
					var div = document.createElement("div");
					div.className = "z-formrow";
					
					var subdiv = document.createElement("div");
					subdiv.id = "colorview" + returns['gid'];
					subdiv.style.backgroundColor = returns['color'];
					subdiv.style.padding = '5px';
					
					var colortext = document.createElement("p");
					colortext.innerHTML = returns['color'];
					
					subdiv.appendChild(colortext);
					div.appendChild(subdiv);
					
					var colorinput = document.createElement("input");
					colorinput.type = "text";
					colorinput.id = "GroupColorinput" + returns['gid'];
					colorinput.setAttribute("name", "GroupColorinput" + returns['gid']);
					colorinput.className = "colorpicker";
					colorinput.size = 7;
					colorinput.maxLength = 7;
					colorinput.style.display = 'none';
					
					div.appendChild(colorinput);
					
					cell3.appendChild(div);

					colorpickerPicky[returns['gid']] = new PickyColor({
								field: 'GroupColorinput' + returns['gid'],
								color: returns['color'],
								colorWell: 'GroupColorinput'  + returns['gid'],
								closeText: "Schließen",
							});
					
					var cell4 = newRow.insertCell(3);
					
					//third cell chooses the color
					var editButton = document.createElement("a");
					editButton.id = "editStart" +  returns['gid'];
					editButton.className = "z-button";
					editButton.innerHTML = document.getElementById('editImg').innerHTML;
					editButton.onclick = function() { groupEditStart( returns['gid']); }
					
					var editSaveButton = document.createElement("a");
					editSaveButton.id = "editSave" +  returns['gid'];
					editSaveButton.className = "z-button";
					editSaveButton.style.display = 'none';
					editSaveButton.innerHTML = document.getElementById('saveImg').innerHTML;
					editSaveButton.onclick = function() { groupEditSave( returns['gid']); }
					
					var editCancelButton = document.createElement("a");
					editCancelButton.id = "editCancel" +  returns['gid'];
					editCancelButton.className = "z-button";
					editCancelButton.style.display = 'none';
					editCancelButton.innerHTML = document.getElementById('cancelImg').innerHTML;
					editCancelButton.onclick = function() { groupEditCancel( returns['gid']); }
					
					var editDeleteButton = document.createElement("a");
					editDeleteButton.id = "delete" +  returns['gid'];
					editDeleteButton.className = "z-button";
					editDeleteButton.innerHTML = document.getElementById('deleteImg').innerHTML;
					editDeleteButton.onclick = function() { groupDelete( returns['gid']); }
					
					
					cell4.appendChild(editButton);
					cell4.appendChild(editSaveButton);
					cell4.appendChild(editCancelButton);
					cell4.appendChild(editDeleteButton);
					
					groupClear();
				}
			}
		}
	);
}

function groupClear()
{
	document.getElementById('name').value = "";
	document.getElementById('newGroupColor').value = "#";
	newGroupColorPicky.updateHex('000000');
}

function groupEditStart(id){
	document.getElementById('editStart'+id).style.display='none';
	document.getElementById('editSave'+id).style.display='inline';
	document.getElementById('editCancel'+id).style.display='inline';
	document.getElementById('nameview'+id).style.display='none';
	document.getElementById('name'+id).style.display='inline';
	document.getElementById('name'+id).value = document.getElementById('nameview'+id).innerHTML;
	document.getElementById('colorview'+id).style.display='none';
	document.getElementById('GroupColorinput'+id).style.display='inline';
	document.getElementById('GroupColorinput'+id).value = document.getElementById('colorview'+id).getElementsByTagName('p')[0].innerHTML;
}

function groupEditCancel(id){
	document.getElementById('editStart'+id).style.display='inline';
	document.getElementById('editSave'+id).style.display='none';
	document.getElementById('editCancel'+id).style.display='none';
	document.getElementById('nameview'+id).style.display='inline';
	document.getElementById('name'+id).style.display='none';
	document.getElementById('name'+id).value = document.getElementById('nameview'+id).innerHTML;
	
	document.getElementById('colorview'+id).style.display='block';
	document.getElementById('GroupColorinput'+id).style.display='none';
	document.getElementById('GroupColorinput'+id).value = document.getElementById('colorview'+id).getElementsByTagName('p')[0].innerHTML;
	colorpickerPicky[id].updateHex(document.getElementById('colorview'+id).getElementsByTagName('p')[0].innerHTML);
	
}

function groupEditSave(id){
	var params = new Object();
	if(id > 0){
		params['gid'] = id;
		params['name'] = document.getElementById('name'+id).value;
		params['color'] = document.getElementById('GroupColorinput' + id).value;
		new Zikula.Ajax.Request(
			"ajax.php?module=Almanach&func=groupEdit",
			{
				parameters: params,
				onComplete:	function (ajax) 
				{
					var returns = ajax.getData();
					if(returns['text']!="")
						alert(returns['text']);
					if(returns['ok']==1)
					{
						document.getElementById('name'+id).value = returns['name'];
						document.getElementById('nameview'+id).innerHTML = returns['name'];
						document.getElementById('GroupColorinput'+id).value = returns['color'];
						document.getElementById('colorview'+id).getElementsByTagName('p')[0].innerHTML = returns['color'];
						document.getElementById('colorview'+id).style.background = returns['color'];
						groupEditCancel(id);
					}
				}
			}
		);
	} else {
		alert('invalid id!')
	}
}
