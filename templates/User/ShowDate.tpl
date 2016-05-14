<style>
	.dateTimes, .dateLocation, .contactPerson{
		color: #555 !important;
		font-style: italic;
		font-size: 14px;
	}
	
	.guests{
		font-weight: bold;
		font-size: 20px;
	}
	p {
		font-size: 16px;
	}
	
	h1 img{
		box-shadow: 0 0;
	}
</style>
{checkpermission component="Almanach::" instance="::" level=ACCESS_COMMENT assign=hasMyDates}
{if $hasMyDates}
	<a href="{modurl modname='Almanach' type='admin' func='main'}" >{img src='configure.png' modname='core' set='icons/small'} {gt text='My Calendar'}</a>
{/if}
{if $permission == 2}
	<a href="{modurl modname=Almanach type=admin func=editDate id=$date->getDid()}" class="z-button">{gt text="Edit this date"} {img src='xedit.png' modname='core' set="icons/extrasmall" __title="edit date"}</a>
{/if}

<h1>{$date->getTitle()}
{if $date->getGuests()}
	{img src='package_favorite.png' modname='core' set='icons/medium' __title="guests are welcome"}
{/if}
</h1>

<p class="dateTimes">{$date->getStartdateFormattedout()} 
	{if $date->getShowEnddate() > 0}
		- {$date->getEnddateFormattedout()}
	{/if}
</p>
<p class="dateLocation">{gt text="Location:"}: {$date->getLocation()}</p>
{if $hasMyDates && $date->getShowUid() > 0}
	<p class="contactPerson">{gt text="Contact Person:"}: {$date->getUserName()}</p>
{/if}
<br/>
{if $date->getGuests()}
	<p class="guests">{gt text="Guests are welcome!"}</p>
{/if}

<br/>
{if $date->getGid() > 0}
	<p>
		{gt text="This is a date of the group"} <span
			{if $date->getGroupColor() != '' and $date->getGroupColor() != '#'}
				style="color: {$date->getGroupColor()};"
			{/if}
		>{$date->getGroupName()}</span>
	</p>
{/if}

<br/> <br/>
<p>
	{$date->getDescription()}
</p>
<br/> <br/>
<p>{gt text="Created on"} {$date->getCreationdateFormattedout()}</p>
