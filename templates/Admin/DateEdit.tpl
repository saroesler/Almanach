{include file='Admin/Header.tpl' __title='Date' icon='add'}
{pageaddvar name="javascript" value="modules/Almanach/javascript/DateEdit.js"}
{pageaddvar name="javascript" value="javascript/picky_color/picky_color.js"}
{pageaddvar name="stylesheet" value="javascript/picky_color/picky_color.css"}
{pageaddvar name="javascript" value="jquery-ui"}
{pageaddvar name="javascript" value="javascript/jquery-ui/i18n/jquery.ui.datepicker-de.js"}
{pageaddvar name="javascript" value="javascript/jquery-plugins/jQuery-Timepicker-Addon/jquery-ui-timepicker-addon.js"}
{pageaddvar name="stylesheet" value="javascript/jquery-plugins/jQuery-Timepicker-Addon/jquery-ui-timepicker-addon.css"}
{pageaddvar name="javascript" value="javascript/jquery-plugins/jQuery-Timepicker-Addon/localization/jquery-ui-timepicker-de.js"}
{pageaddvar name="stylesheet" value="javascript/jquery-ui/themes/base/jquery-ui.css"}

<script language="javaScript">
function id_datepicker(id)
{
	jQuery( "#"+id ).datetimepicker();
	jQuery( "#"+id ).datetimepicker({
                dateFormat: "dd.mm.yy",
                timeFormat: 'HH:mm',
                altFormat: "dd/mm/yy",
                altTimeFormat: "HH:mm",
                showButtonPanel: true,
            });
	
	var value= (document.getElementById(id).value);
	jQuery( "#"+id ).datetimepicker('setDate', parseDate(value));
}
jQuery(function() {
	id_datepicker("startdate");
	id_datepicker("enddate");
});
</script>
<input id="did" style="display:none" value="{$date->getDid()}">
<input id="allowDateColoring" style="display:none" value="{$allowDateColoring}">
<a id="deleteImage" style="display:none">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'}</a>
{form cssClass="z-form"}
	<fieldset>
	<legend>General</legend>
	<div class="z-formrow">
        {formlabel for='startdate' __text='Begin:'}
		{formtextinput id="startdate" maxLength=200 mandatory=true text=$date->getStartdateFormatted()}
	</div>
	<div class="z-formrow">
        {formlabel for='enddate' __text='End:'}
		{formtextinput id="enddate" maxLength=200 mandatory=true text=$date->getEnddateFormatted()}
	</div>
	<div class="z-formrow">
        {formlabel for='title' __text='Title:'}
		{formtextinput id="title" maxLength=200 mandatory=true text=$date->getTitle()}
	</div>
	<div class="z-formrow">
        {formlabel for='location' __text='Location:'}
		{formtextinput id="location" maxLength=1000 mandatory=false text=$date->getLocation()}
	</div>
	<div class="z-formrow">
        {formlabel for='description' __text='Description:'}
		{formtextinput id="description" maxLength=2000 mandatory=false text=$date->getDescription() rows='15' textMode='multiline'}
	</div>
	<div class="z-formrow">
        {formlabel for='gid' __text='Group:'}
		{formdropdownlist id="gid" size="1" mandatory=false items=$groupSelection selectedValue=$date->getGid()"}
	</div>
	{if $allowDateColoring <> 0}
		<div class="z-formrow">
		    {formlabel for='color' __text='Color:'}
			{formtextinput id="color" maxLength=7 mandatory=false text=$date->getColor()}
		</div>
		<script type="text/javascript" charset="utf-8">
			/* <![CDATA[ */

			var colorPicky = new PickyColor({
				field: 'color',
				color: '{{$date->getColor()|safetext}}',
				colorWell: 'color',
				closeText: "{{gt text='Close'}}",
			})

			/* ]]> */
		</script>
	{/if}
	<div class="z-formrow">
		{formlabel for='visibility' __text='Visibility:'}
		{formdropdownlist id="visibility" size="1" mandatory=false items=$visibilitySelection selectedValue=$date->getVisibility()"}
	</div>
	<div class="z-formrow">
		{formlabel for='guests' __text='Guests are welcome:'}
		{formcheckbox id="guests" checked=$date->getGuests()}
	</div>
	
	{if $date->getUid() > 0}
	<p>{gt text="This date was created by user %s at %s" tag1=$date->getUserName() tag2= $date->getCreationdateFormattedout()}
	{/if}
   </fieldset>
   
   <fieldset>
   <legend>Calendar</legend>
   <br/><br/>
   This Date is inputed in following calendars.<br/><br/>
   
   <input type="text" style="display:none;" value="0" id="newCalendarsNum" name="newCalendarsNum"/>
   <a onclick="calendarInput()">{gt text="Input this Date into "}</a>
   <select name="calendarSelection" id="calendarSelection"> 
		<option value="0">{gt text="no calendar selected"}</option> 
		{foreach from=$almanachSelector key=i item='option'}
			<option value="{$option->getAid()}"
			{if $almanachHide.$i}
				style="display:none;"
			{/if}
			>{$option->getName()}</option> 
		{/foreach}
	</select>
	
	<br/><br/>
	
	<table class="z-datatable" id="calendarlist">
		<thead>
			<tr>
				<th>{gt text='Id'}</th>
				<th>{gt text='Calendar'}</th>
				<th>
					{if $allowDateColoring <> 0}
						{gt text='Color'}
					{/if}
				</th>			
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$connections item='connection'}
				<tr id="Calendar{$connection->getEid()}">
					<td>{$connection->getEid()}</td>
					<td>{$connection->getAlmanachName()}</td>
					<td>
						{if $allowDateColoring <> 0}
							<div class="z-formrow">
								<input id="CalendarColorinput{$connection->getEid()}" class="colorpicker" name="CalendarColorinput{$connection->getEid()}" type="text" value="{$connection->getColor()|safetext}" maxlength="7" size="7"/>
							</div>
							<script type="text/javascript" charset="utf-8">
								/* <![CDATA[ */

								var color{{$connection->getEid()}}Picky = new PickyColor({
									field: 'CalendarColorinput{{$connection->getEid()}}',
									color: '{{$connection->getColor()|safetext}}',
									colorWell: 'CalendarColorinput{{$connection->getEid()}}',
									closeText: "{{gt text='Close'}}",
								})

								/* ]]> */
							</script>
						{/if}
						<input type="text" id="almanachId{$connection->getEid()}" style="display:none;" value="{$connection->getAid()}"/>
					</td>
					<td>
						<a id="delete{$connection->getEid()}" onclick="connectionDelete({$connection->getEid()}, false)" class="z-button">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'}</a>
						<input id="connectionDeleted{$connection->getEid()}" name="connectionDeleted{$connection->getEid()}" type="text" value="0" style="display:none;"/>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
	
   </fieldset>
   <fieldset>
	   <div class="z-formbuttons z-buttons">
		   {formbutton class="z-bt-ok" commandName="save" __text="Save"}
		   {formbutton class="z-bt-cancel" commandName="cancel" __text="Cancel"}
	   </div>
   </fieldset>
{/form}
{include file='Admin/Footer.tpl'}
