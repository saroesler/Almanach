{include file='Admin/Header.tpl' __title='Mainpage' icon='home'}

{pageaddvar name="javascript" value="modules/Almanach/javascript/Mainpage.js"}
{pageaddvar name="javascript" value="modules/Almanach/javascript/AlmanachDateAdmin.js"}

<style>
	.calendarContainer{
		border: 1px solid #aaa;
		padding: 20px;
		margin: 10px;
		border-radius: 10px;
	}
	
	.calendarDiv, .dateDiv{
		padding: 10px;
		margin: 10px;
		border: 1px solid #aaa;
		border-radius: 10px;
	}
	.calendarSubscribe{
		background-color: red;
		color:white;
		border: 1px solid #aaa;
		border-radius: 4px;
		padding: 5px;
		padding-left: 10px;
		padding-right: 10px;
	}
	.calendarSubscribe a, .calendarSubscribe a:link{
		color:white !important;
	}
	.calendarSubscribe img{
		box-shadow: 0px 0px;
		margin-right: 10px;
	}
	
	.dateTimes, .dateLocation{
		color: #555 !important;
		font-style: italic;
		font-size: 14px;
	}
	
	.dateTitle{
		color: #000 !important;
		font-style: normal;
		font-weight: bold;
		font-size: 20px;
	}
	
	.dateDescription{
		color: #000 !important;
		font-style: normal;
		font-size: 14px;
	}
	
	.dateDiv img{
		box-shadow: 0px 0px;
	}
	
	.oldDate{
		display:none;
	}
	
	.shownOldDate{
		background-color: #ddd;
		border-width: 3px;
	}
	
	.noData{
		font-size: 20px;
		font-weight: bold;
	}
</style>
<h2>{gt text="My Calendars"}</h2>

<a id="calendarDown" onclick="showCalendarList()" style="display:none;">{img src='14_layer_lowerlayer.png' modname='core' set='icons/extrasmall'}</a>
<a id="calendarUp" onclick="hideCalendarList()">{img src='14_layer_raiselayer.png' modname='core' set='icons/extrasmall'}</a>

<div id="calendarList" class="calendarContainer">
	{foreach from=$almanachs key=i item='almanach'}
		<div class="calendarDiv">
			<div class="calendarSubscribe" style="float:right;">
				{if $calendarSubscribtions.$i <> 1}
					<a id="subscibe{$almanach->getAid()}" onclick="subscribeAlmanach({$almanach->getAid()})">{img src='unfavorites.png' modname='Almanach' set="favorites"} {gt text="Subscribe"}</a>
					<a id="unsubscibe{$almanach->getAid()}" style="display:none;" onclick="unsubscribeAlmanach({$almanach->getAid()})">{img src='favorites.png' modname='Almanach' set='favorites'} {gt text="Unsubscribe"}</a>
				{else}
					<a id="subscibe{$almanach->getAid()}" onclick="subscribeAlmanach({$almanach->getAid()})" style="display:none;">{img src='unfavorites.png' modname='Almanach' set='favorites'} {gt text="Subscribe"}</a>
					<a id="unsubscibe{$almanach->getAid()}" onclick="unsubscribeAlmanach({$almanach->getAid()})">{img src='favorites.png' modname='Almanach' set='favorites'} {gt text="Unsubscribe"}</a>
				{/if}
			</div>
			<div>
				<a href="{modurl modname=Almanach type=user func=view id=$almanach->getAid()}" style="font-size:20px;">{$almanach->getName()}</a>
			</div>
			<div style="clear:both;"></div>
		</div>
	{foreachelse}
		<p  class="noData">{gt text="There are no calendars!"}</p>
	{/foreach}
</div>

<h2>{gt text="My Dates"}</h2>

<div>
	<div style="float:right;">
		<a id="hideOld" onclick="hideOldDates()" style="display:none;">{img src='14_layer_visible.png' modname='core' set='icons/extrasmall' __title="hide old dates"}</a>
		<a id="showOld" onclick="showOldDates()">{img src='14_layer_novisible.png' modname='core' set='icons/extrasmall' __title="show old dates"}</a>
	</div>
	<div>
		<a id="datesDown" onclick="showDateList()" style="display:none;">{img src='14_layer_lowerlayer.png' modname='core' set='icons/extrasmall'}</a>
		<a id="datesUp" onclick="hideDateList()">{img src='14_layer_raiselayer.png' modname='core' set='icons/extrasmall'}</a>
	</div>
</div>

<div style="clear:both;"></div>

<div id="dateList" class="dateContainer">
	{foreach from=$myDates key=i item='myDate'}
		<div class="dateDiv
			{if $i <= $oldKey}
				oldDate
			{/if}
			" id="date{$myDate->getDid()}"
			{if $myDate->getColor() <> '' and $myDate->getColor() <> '#'}
				style="border-color:{$myDate->getColor()};"
			{/if}
			>
			<div class="dateAdmin" style="float:right;">
				{if $subscribedDates.$i == 1}
					<a id="unsubscibe{$myDate->getDid()}" onclick="unsubscribeDate({$myDate->getDid()})">{img src='favorites.png' modname='Almanach' set="favorites" __title="unsubscribe date"}</a>
				{/if}
				{if $adminDates.$i == 1}
					<a href="{modurl modname=Almanach type=admin func=editDate id=$myDate->getDid()}">{img src='xedit.png' modname='core' set="icons/extrasmall" __title="edit date"}</a>
					<a onclick="deleteDate({$myDate->getDid()})">{img src='14_layer_deletelayer.png' modname='core' set="icons/extrasmall" __title="delete date"}</a>
				{/if}
			</div>
			<div>
				{if $myDate->getGuests()}
					<div style="float:left;">{img src='package_favorite.png' modname='core' set='icons/extrasmall' __title="guests are welcome"}</div>
					<div style=" margin-left: 26px;">
				{else}
					<div style="margin-left: 26px;">
				{/if}
					<a class="dateTimes">
						{$myDate->getStartdateFormattedout()} - {$myDate->getEnddateFormattedout()}
					</a>
					{if $myDate->getLocation() <> ''}
						<br/>
						<a class="dateLocation">
							{gt text="Location"}: {$myDate->getLocation()}
						</a>
					{/if}
				</div>
			</div>
			<div style="clear:both;"></div>
			<div>
				<a href="{modurl modname=Almanach type=user func=showDate id=$myDate->getDid()}" class="dateTitle">{$myDate->getTitle()} </a> <br/>
				<a class="dateDescription">{$myDate->getShortDescription()} </a>
			</div>
		</div>
	{foreachelse}
		<p class="noData">{gt text="There are no dates!"}</p>
	{/foreach}
	{if $i == $oldKey}
		<p class="noData">{gt text="There are no dates!"}</p>
	{/if}
</div>
{include file='Admin/Footer.tpl'}
