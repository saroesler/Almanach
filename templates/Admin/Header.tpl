{include file='Header.tpl'}
{adminheader}

<div class="z-admin-content-pagetitle">
	{if $icon != ""}
   		{icon type=$icon size="small"}
   	{/if}
    <h3>{$title}</h3>
</div>

<div class="z-errormsg" id="BrowserError" style="display:block;"><p>{gt text="Sie nutzen einen zu alten InternetExplorer ! ! !      Es wird bei der Eingabe von Daten und beim Drucken zu Fehlern kommen ! Nutzen Sie bitte allgemein ein anderes Programm (z.B. Firefox, Chrome oder Opera)."}</p></div>
<div class="z-warningmsg" id="BrowserWarning" style="display:none;"><p>{gt text="Bitte benutzen Sie nicht den InternetExplorer! Dies kann zu Problemen beim Drucken führen!"}</p></div>

<script type="text/javascript" language="JavaScript">
	//look for Browser
	var rv = -1;
	if (navigator.appName == 'Microsoft Internet Explorer')
	{
		var ua = navigator.userAgent;
		var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
		if (re.exec(ua) != null)
		rv = parseFloat( RegExp.$1 );
	}
	else if (navigator.appName == 'Netscape')
	{
		var ua = navigator.userAgent;
		var re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
		if (re.exec(ua) != null)
		rv = parseFloat( RegExp.$1 );
	}
	if(rv==-1)
	{
		document.getElementById("BrowserError").style.display = "none";
	}
	if(rv>8)
	{
		document.getElementById("BrowserError").style.display = "none";
		document.getElementById("BrowserWarning").style.display = "block";
	}
	else if(rv != -1)
		alert("Sie nutzen einen veralteten InternetExplorer!!! \nBitte verwenden Sie allgemein ein anderes Programm!");

</script>
