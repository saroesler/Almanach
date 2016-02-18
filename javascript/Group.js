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
	params['name'] = document.getElementById('name').value;;
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
					groupClear();
				}
			}
		}
	);
}

function groupClear()
{
	document.getElementById('name').value = "";
}

function groupEditStart(id){
	document.getElementById('editStart'+id).style.display='none';
	document.getElementById('editSave'+id).style.display='inline';
	document.getElementById('editCancel'+id).style.display='inline';
	document.getElementById('nameview'+id).style.display='none';
	document.getElementById('name'+id).style.display='inline';
	document.getElementById('name'+id).value = document.getElementById('nameview'+id).innerHTML;
}

function groupEditCancel(id){
	document.getElementById('editStart'+id).style.display='inline';
	document.getElementById('editSave'+id).style.display='none';
	document.getElementById('editCancel'+id).style.display='none';
	document.getElementById('nameview'+id).style.display='inline';
	document.getElementById('name'+id).style.display='none';
	document.getElementById('name'+id).value = document.getElementById('nameview'+id).innerHTML;
}

function groupEditSave(id){
	var params = new Object();
	if(id > 0){
		params['gid'] = id;
		params['name'] = document.getElementById('name'+id).value;
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
						groupEditCancel(id);
					}
				}
			}
		);
	} else {
		alert('invalid id!')
	}
}
