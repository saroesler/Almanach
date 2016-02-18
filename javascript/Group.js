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
					table = document.getElementById('grouplist').innerHTML;
					
					document.getElementById('grouplist').innerHTML = "";
					str = table.substr(0, table.lastIndexOf("<tr>"));
					document.getElementById('grouplist').innerHTML = str.concat(returns['newGroup'], table.substr(table.lastIndexOf("<tr>"), table.length));
					colorpickerPicky[returns['gid']] = new PickyColor({
								field: 'GroupColorinput' + returns['gid'],
								color: returns['color'],
								colorWell: 'GroupColorinput'  + returns['gid'],
								closeText: "Schließen",
							});
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
	newGroupColorPicky.updateHex('FFFFFF');
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
