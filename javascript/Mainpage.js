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
