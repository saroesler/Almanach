{pageaddvar name="javascript" value="modules/Almanach/javascript/AlmanachDateAdmin.js"}
{pageaddvar name="javascript" value="modules/Almanach/javascript/Filter.js"}

<style>
	.filter{
		background-color: #ddd;
		border: 1px solid #aaa;
		border-radius: 4px;
		padding: 20px;
		max-width: 490px;
		margin: 10px;
	}
	
	.filter img{
		box-shadow: 0 0;
	}
	
	.filterHeader{
		font-weight: bold;
		font-size: 20px;
		margin-left: 0px;
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
	
	.dateDiv{
		padding: 10px;
		margin: 10px;
		border: 3px solid #aaa;
		border-radius: 10px;
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
	
	div.z-formrow label {
		color: #333;
		display: block;
		float: left;
		font-weight: normal;
		padding: 0.3em 1% 0.3em 0;
		text-align: right;
		width: 90%;
	}
	
	.filterbody{
		padding: 5px;
		margin: 5px;
		border: 1px solid #aaa;
		border-radius: 10px;
	}
	
	.timeselection{
		background-color: #ddd;
		border: 1px solid #aaa;
		border-radius: 4px;
		padding: 5px;
		padding-left: 20px;
		max-width: 505px;
		margin: 10px;
	}
	
	.selectedTime{
		background: rgba(0, 0, 0, 0) linear-gradient(#fafafa, #cfcfcf) repeat scroll 0 0;
		color: #222;
		text-decoration: none !important;
	}
</style>
{checkpermission component="Almanach::" instance="::" level=ACCESS_COMMENT assign=hasMyDates}
{if $hasMyDates}
	<a href="{modurl modname='Almanach' type='admin' func='main'}" >{img src='configure.png' modname='core' set='icons/small'} {gt text='My Calendar'}</a>
{/if}
	
<h1>{$almanach->getName()}</h1>

{if $subalmanachs|@count gt 0}
	<p>{gt text="This calendar has this subcalendars:"}
		{foreach from=$subalmanachs key=i item='subalmanach'}
			{assign var="color" value=$heredities.$i}
			<a href="{modurl modname=Almanach type=user func=view id=$subalmanach->getAid()}" style="color:{$color->getColor()}">
				{$subalmanach->getName()}
			</a>
			{if $i<>(($subalmanachs|@count)-1)}
			<a>, </a>
			{/if}
		{/foreach}
	</p>
{/if}

<div>
	{*<div class="calendarSubscribe" style="float:right;">
		{if $calendarSubscribtion <> 1}
			<a id="subscibe{$almanach->getAid()}" onclick="subscribeAlmanach({$almanach->getAid()})">{img src='unfavorites.png' modname='Almanach' set="favorites"} {gt text="Subscribe"}</a>
			<a id="unsubscibe{$almanach->getAid()}" style="display:none;" onclick="unsubscribeAlmanach({$almanach->getAid()})">{img src='favorites.png' modname='Almanach' set='favorites'} {gt text="Unsubscribe"}</a>
		{else}
			<a id="subscibe{$almanach->getAid()}" onclick="subscribeAlmanach({$almanach->getAid()})" style="display:none;">{img src='unfavorites.png' modname='Almanach' set='favorites'} {gt text="Subscribe"}</a>
			<a id="unsubscibe{$almanach->getAid()}" onclick="unsubscribeAlmanach({$almanach->getAid()})">{img src='favorites.png' modname='Almanach' set='favorites'} {gt text="Unsubscribe"}</a>
		{/if}
	</div>*}
	<div class="filter">
		<p class="filterHeader"> <a onclick="showFilter()" class="filterHeader" style="color: #333;">{img src='filter.png' modname='core' set='icons/small'}  {gt text="Filter"} </a><span style="margin-left:150px;font-size: 15px;"><a onclick="showFilter()" class="filterHeader z-button" style="color: #333; font-size: 15px;">{gt text="show"}</span></a></p><br/>
		<p>{gt text="You can filter the dates below by groups."}</p>
		
		<div class="filterbody" id="filterbody" style="display:none;">
			
			<p style="text-align:center;"><a style="margin-right:20px;" onclick="filterSelectAll()">{gt text="select all"}</a></span><a  onclick="filterSelectNone()">{gt text="select none"}</a></p>
			{assign var="counter" value=0}
			{if $noGroup}
				<div class="z-formrow">
					<label>{gt text="show Date belong to no group"}</label>
					<input type="checkbox" name="Group{$counter}"  id="Group{$counter}" value="0">
				</div>
			{/if}
			{assign var="counter" value=1}
			{foreach from=$groups key=i item='group'}
				<div class="z-formrow">
					<label
					{if $group->getColor() != '' and $group->getColor() != '#'}
						style="color:{$group->getColor()};"
					{/if}>
						{$group->getName()}
					</label>
					<input type="checkbox" name="Group{$counter}"  id="Group{$counter}" value="{$group->getGid()}">
				</div>
				{assign var="counter" value=$counter+1}
			{/foreach}
			<p style="text-align:center;"><a class="z-button" onclick="filterRun()">{gt text="Apply"}</a></p>
		</div>
		<input type="text" style="display:none;" name="GroupNum"  id="GroupNum" value="{$counter}">
	</div>
</div>

<div style="clear:both;"></div>

<div>
	<div style="float:right; box-shadow: 5px 5px 2px grey; padding: 5px; border: 1px solid #000;">
		<a id="hideOld" onclick="hideOldDates()" style="display:none;">{img src='forward.png' modname='core' set='icons/small' __title="hide old dates" style="box-shadow: 0px 0px 0px"} {gt text="History"}</a>
		<a id="showOld" onclick="showOldDates()">{img src='previous.png' modname='core' set='icons/small' __title="show old dates"  style="box-shadow: 0px 0px 0px"} {gt text="History"}</a>
	</div>
</div>

<div class="timeselection">
	<a class="z-button" id="selectTime0" style="margin:0px;" onclick="selectTime(0)">{gt text="today"}</a><a class="z-button selectedTime" style="margin:0px;" id="selectTime1" onclick="selectTime(1)">{gt text="this week"}</a><a class="z-button" style="margin:0px;" onclick="selectTime(2)" id="selectTime2" >{gt text="this month"}</a><a class="z-button" style="margin:0px;" onclick="selectTime(3)" id="selectTime3">{gt text="this year"}</a><a class="z-button" style="margin:0px;" onclick="selectTime(4)" id="selectTime4">{gt text="all"}</a>
</div>

<div style="clear:both;"></div>

<div id="dateList" class="dateContainer">
	{foreach from=$myDates key=i item='myDate'}
		<div class="dateDiv
			{if $i <= $oldKey}
				oldDate
			{else}
				{if $i > $today}
					notToday
				{/if}
				{if $i > $week}
					notThisWeek
				{/if}
				{if $i > $month}
					notThisMonth
				{/if}
				{if $i > $year}
					notThisYear
				{/if}
			{/if}
			group{$myDate->getGid()}
			" id="date{$myDate->getDid()}"
			style="
			{if $myDate->getColor() <> '' and $myDate->getColor() <> '#'}
				border-color:{$myDate->getColor()};
			{/if}
			{if $i > $week AND $i > $oldKey}
				display:none;
			{/if}
			"
			>
			<div class="dateAdmin" style="float:right;">
				<a href="{modurl modname=Almanach type=user func=showDate id=$myDate->getDid()}" style="font-size: 18px; color:{$myDate->getGroupColor()};">{gt text="Information"} </a> &nbsp;&nbsp; 
				{if $subscribedDates.$i == 1}
					<a id="unsubscibe{$myDate->getDid()}" onclick="unsubscribeDate({$myDate->getDid()})">{img src='favorites.png' modname='Almanach' set="favorites" __title="unsubscribe date"}</a>
				{/if}
				{if $adminDates.$i == 1}
					<a href="{modurl modname=Almanach type=admin func=editDate id=$myDate->getDid()}">{img src='xedit.png' modname='core' set="icons/small" __title="edit date"}</a>
					{*<a onclick="deleteDate({$myDate->getDid()})">{img src='14_layer_deletelayer.png' modname='core' set="icons/small" __title="delete date"}</a>*}
				{/if}
			</div>
			<div>
				{if $myDate->getGuests()}
					<div style="float:left;">{img src='package_favorite.png' modname='core' set='icons/small' __title="guests are welcome"}</div>
					<div style=" margin-left: 26px;">
				{else}
					<div style="margin-left: 26px;">
				{/if}
					<a class="dateTimes">
						{$myDate->getStartdateFormattedout()}
						{if $myDate->getShowEnddate() > 0} 
							- {$myDate->getEnddateFormattedout()}
						{/if}
					</a>
					{if $myDate->getLocation() <> ''}
						<br/>
						<a class="dateLocation">
							{gt text="Location"}: {$myDate->getLocation()}
						</a>
					{/if}
					{if $myDate->getGroupName() <> ''}
						<br/>
						<a class="dateGroup" style="color:{$myDate->getGroupColor()};">
							{gt text="Group"}: {$myDate->getGroupName()}
						</a>
					{/if}
				</div>
			</div>
			<div style="clear:both;"></div>
			<div>
				<a 
					{if $myDate->getNextAlmanach() > 0}
						href="{modurl modname=Almanach type=user func=view id=$myDate->getNextAlmanach()}"}
					{else}
						href="{modurl modname=Almanach type=user func=showDate id=$myDate->getDid()}"
					{/if} 
					class="dateTitle">{$myDate->getTitle()} </a> <br/>
				<p class="dateDescription" style="margin:0px;">{$myDate->getShortDescription()} </p>
			</div>
		</div>
	{foreachelse}
		<p class="noData">{gt text="There are no dates!"}</p>
	{/foreach}
	{if $i == $oldKey}
		<p class="noData">{gt text="There are no dates!"}</p>
	{/if}
</div>
