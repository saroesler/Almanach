function almanachDelete(id)
{
	var params = new Object();
	params['id'] = id;
	if(confirm("Soll der Kalender wirklich gelöscht werden? Es werden dann alle Abhängigkeiten zu diesem Kalender mit gelöscht. Klicken Sie auf OK, wenn Sie sich sicher sind, dass dieser Kalender nicht mehr benötigt wird."))
	{
		new Zikula.Ajax.Request(
			"ajax.php?module=Almanach&func=almanachDel",
			{
				parameters: params,
				onComplete:	function (ajax) 
				{
					var returns = ajax.getData();
					
					if(returns['id'])
					{
						document.getElementById('Calendar'+returns['id']).style.display = "none";
					}
				}
			}
		);
	}
}
