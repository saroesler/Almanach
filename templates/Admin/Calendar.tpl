{include file='Admin/Header.tpl' __title='Calendar' icon='cubes'}
{pageaddvar name="javascript" value="modules/Almanach/javascript/CalendarAdmin.js"}

<div class="z-informationmsg">
	<p>{gt text="The dates are shown by calendars. Each date can be entered into multiple calendars. You can set special colors for each roup. If there is no special color of the date it is shown by this color. If no color is selected for a group they get the color given to the group generaly. Registrated user can subscribe calendars and its dates. User can get the permission to enter dates into a calendar. You can set user to administrate a calendar, too. This musst be set by Zikula- permissionrules. A calendar can input other calendars, if it is not already part of them. The dates of this calendar can be shown by an extra color."}</p>
</div>


<a href="{modurl modname=Almanach type=admin func=editCalendar id=0}">{gt text="create new Calendar"}</a>
<br/><br/>

<div id="calendarlistcontainer">
	<table class="z-datatable" id="calendarlist">
		<thead>
			<tr>
				<th>{gt text='Id'}</th>
				<th>{gt text='Name'}</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$calendars item='calendar'}
				<tr id="Calendar{$calendar->getAid()}">
					<td>{$calendar->getAid()}</td>
					<td> {$calendar->getName()}</td>
					<td>
						<a id="edit{$calendar->getAid()}" href="{modurl modname=Almanach type=admin func=editCalendar id=$calendar->getAid() }" class="z-button">{img src='xedit.png' modname='core' set='icons/extrasmall'}</a>
						<a id="delete{$calendar->getAid()}" onclick="almanachDelete({$calendar->getAid()})" class="z-button">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'}</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>
	
{include file='Admin/Footer.tpl'}
