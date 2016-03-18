function subscribeAlmanach(aid)
{
	var params = new Object();
	params['aid'] = aid;
	new Zikula.Ajax.Request(
		"ajax.php?module=Almanach&func=subscribeAlmanach",
		{
			parameters: params,
			onComplete:	function (ajax) 
			{
				var returns = ajax.getData();
				if(returns['text']!="")
					alert(returns['text']);
				if(returns['ok']==1)
				{
					document.getElementById('subscibe' + returns['aid']).style.display = 'none';
					document.getElementById('unsubscibe' + returns['aid']).style.display = 'block';
				}
			}
		}
	);
}

function unsubscribeAlmanach(aid)
{
	var params = new Object();
	params['aid'] = aid;
	new Zikula.Ajax.Request(
		"ajax.php?module=Almanach&func=unsubscribeAlmanach",
		{
			parameters: params,
			onComplete:	function (ajax) 
			{
				var returns = ajax.getData();
				if(returns['text']!="")
					alert(returns['text']);
				if(returns['ok']==1)
				{
					document.getElementById('subscibe' + returns['aid']).style.display = 'block';
					document.getElementById('unsubscibe' + returns['aid']).style.display = 'none';
				}
			}
		}
	);
}

function unsubscribeDate(did)
{
	var params = new Object();
	params['did'] = did;
	new Zikula.Ajax.Request(
		"ajax.php?module=Almanach&func=unsubscribeDate",
		{
			parameters: params,
			onComplete:	function (ajax) 
			{
				var returns = ajax.getData();
				if(returns['text']!="")
					alert(returns['text']);
				if(returns['ok']==1)
				{
					document.getElementById('unsubscibe' + returns['did']).style.display = 'none';
				}
			}
		}
	);
}

function deleteDate(did)
{
	var params = new Object();
	params['did'] = did;
	if(confirm("Soll der Termin wirklich gelöscht werden? Er wird dann aus allen Kalendern entfernt."))
	new Zikula.Ajax.Request(
		"ajax.php?module=Almanach&func=dateDel",
		{
			parameters: params,
			onComplete:	function (ajax) 
			{
				var returns = ajax.getData();
				if(returns['ok']==1)
				{
					document.getElementById('date' + returns['did']).style.display = 'none';
				}
			}
		}
	);
}

function showOldDates(){
	var elements = Array.prototype.slice.call(document.getElementsByClassName('oldDate'));
	//while(elements.length > 0) {
		for(var i = 0; i < elements.length; i ++){
			elements[i].className = elements[i].className.replace('oldDate', "shownOldDate");
		}
	//}
	document.getElementById("showOld").style.display = 'none';
	document.getElementById("hideOld").style.display = 'block';
}

function hideOldDates(){
	var elements = document.getElementsByClassName('shownOldDate');
	while(elements.length > 0) {	
		for(var i = 0; i < elements.length; i ++){
			elements[i].className = elements[i].className.replace('shownOldDate', "oldDate");
		}
	}
	document.getElementById("showOld").style.display = 'block';
	document.getElementById("hideOld").style.display = 'none';
}
