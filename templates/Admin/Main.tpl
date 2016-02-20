{include file='Admin/Header.tpl' __title='Mainpage' icon='home'}

{pageaddvar name="javascript" value="modules/Almanach/javascript/Mainpage.js"}

<style>
	.calendarContainer{
		border: 1px solid #aaa;
		padding: 20px;
		margin: 10px;
		border-radius: 10px;
	}
	
	.calendarDiv{
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
				<a style="font-size:20px;">{$almanach->getName()}</a>
			</div>
			<div style="clear:both;"></div>
		</div>
	{foreachelse}
		<p>{gt text="There are no calendars"}</p>
	{/foreach}
</div>
{include file='Admin/Footer.tpl'}
