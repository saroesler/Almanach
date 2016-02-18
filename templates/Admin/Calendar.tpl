{include file='Admin/Header.tpl' __title='Calendar' icon='view'}
{pageaddvar name="javascript" value="modules/Almanach/javascript/CalendarAdmin.js"}

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
