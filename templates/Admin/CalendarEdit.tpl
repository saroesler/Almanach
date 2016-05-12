{include file='Admin/Header.tpl' __title='Calendar' icon='cubes'}
{pageaddvar name="javascript" value="modules/Almanach/javascript/CalendarEdit.js"}
{pageaddvar name="javascript" value="javascript/picky_color/picky_color.js"}
{pageaddvar name="stylesheet" value="javascript/picky_color/picky_color.css"}

<input id="aid" style="display:none" value="{$almanach->getAid()}">
<a id="deleteImage" style="display:none">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'}</a>
{form cssClass="z-form"}
	<fieldset>
		<legend>{gt text="General"}</legend>
		<div class="z-formrow">
		    {formlabel for='name' __text='Name:'}
			{formtextinput id="name" maxLength=200 mandatory=true text=$almanach->getName()}
		</div>
		<div class="z-informationmsg">
			<p>{gt text="You can descide that more dates can happen on the same time. They can overlap. If you dont want, that there are more dates on the same time, you should remove the hook. This is useful if you want to create calendars for rooms. This workes for dates inputed via other calendars this calendar input, too. User get an error, if they want input a date to a time, there is another one."}</p>
		</div>
		<div class="z-formrow">
			{formlabel for='overlapping' __text='Overlapping:'}
			{formcheckbox id="overlapping" checked=$almanach->getOverlapping()}
		</div>
		<div class="z-informationmsg">
			<p>{gt text="You can create calendars only including other ones. Users cant insert dates directly into this calendar."}</p>
		</div>
		<div class="z-formrow">
			{formlabel for='input' __text='Allow Date input:'}
			{formcheckbox id="input" checked=$almanach->getInput()}
		</div>
		<div class="z-informationmsg">
			<p>{gt text="To set a special design to this calendar please use templates."}</p>
		</div>
		<div class="z-formrow">
			{formlabel for='template' __text='Template:'}
			{formtextinput id="template" maxLength=2000 mandatory=false text=$almanach->getTemplate()}
		</div>
	</fieldset>
	{if $googleApiExist == 0}
	<input type='hidden' id="googleApi" value='1'/>
	<fieldset>
		<legend>{gt text="Google Calendar"}</legend>
		<div class="z-informationmsg">
			<p>{gt text="You can connect this calendar with a google calendar. Therefor you has to share the google calendar with "} {$googleApiAddress}. {gt text="Please insert the google calendar id in the field below. You find the id in the settings of the google calendar. It has the form 'ok430k5bire7huqihlqf18jaao@group.calendar.google.com'. You have to set 'Add and edit events' in google permission rules."}</p>
		</div>
		<div class="z-formrow">
			{formlabel for='googleCalendarId' __text='Google Calendar ID:'}
			{formtextinput id="googleCalendarId" maxLength=2000 mandatory=false text=$almanach->getGoogleCalendarId() onchange="setGoogleRequest()"}
		</div>
		<div class="z-formrow">
			{formlabel for='pullGid' __text='Group for dates get by Google Calendar:'}
			{formdropdownlist id="pullGid" size="1" mandatory=false items=$pullGroupSelection selectedValue=$almanach->getPullGid()"}
		</div>
		
		<div class="z-formrow">
			{formlabel for='pullUid' __text='User for dates get by Google Calendar:'}
			{formdropdownlist id="pullUid" size="1" mandatory=false items=$pullUserSelection selectedValue=$almanach->getPullUid()"}
		</div>
		<div class="z-informationmsg" id="googleTransfer" style="display:none;">
			<p>{gt text="You changes the google Calendar. Do you want the transfer all dates of this calendar to the google calendar?"}</p>
			<div>
				{formlabel __text='No' for='noButton' mandatory=true}{formradiobutton id='noButton' dataField='ok'} <br/>
				{formlabel __text='Yes' for='yesButton' mandatory=true} {formradiobutton id='yesButton' dataField='ok'}	
			</div>
		</div>
	</fieldset>
	{else}
		<input type='hidden' id="googleApi" value='1'/>
	{/if}
   
   <fieldset>
   <legend>{gt text="Heredity"}</legend>
   <br/>
   <div class="z-warningmsg" id="googleHierarchy" 
	{if !($heredities|@count)}
		style="display:none;"
	{/if}
	>
   		{gt text="Only dates direktly input in this calendar will be synchronisized with google calendar. Dates get by other calendars will not transfered."}
   </div>
   <br/>
   {gt text="This Calendar inherits the dates of the following calendars. All dates of this calendar has the selected color. If no color is selected, the date keeps the former color."}<br/><br/>
   <input type="text" style="display:none;" value="0" id="newHereditiesNum" name="newHereditiesNum"/>
   <a onclick="hereditySave()">{gt text="create new Heredity to"}</a> 
   <select name="HereditySelection" id="HereditySelection"> 
		<option value="0">{gt text="no calendar selected"}</option> 
		{foreach from=$hereditySelection key=i item='option'}
			<option value="{$option->getAid()}"
			{if $heredityHide.$i}
				style="display:none;"
			{/if}
			>{$option->getName()}</option> 
		{/foreach}
	</select>
	
	<br/><br/>
	
	<table class="z-datatable" id="hereditylist">
		<thead>
			<tr>
				<th>{gt text='Id'}</th>
				<th>{gt text='Calendar'}</th>
				<th>{gt text='Color'}</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$heredities item='heredity'}
				<tr id="Heredity{$heredity->getHid()}">
					<td>{$heredity->getHid()}</td>
					<td>{$heredity->getCAlmanachName()}</td>
					<td>
						<div class="z-formrow">
						    <input id="heredityColorinput{$heredity->getHid()}" class="colorpicker" name="heredityColorinput{$heredity->getHid()}" type="text" value="{$color|safetext}" maxlength="7" size="7"/>
						</div>
						<script type="text/javascript" charset="utf-8">
						    /* <![CDATA[ */

							var heredity{{$heredity->getHid()}}Picky = new PickyColor({
								field: 'heredityColorinput{{$heredity->getHid()}}',
								color: '{{$heredity->getColor()|safetext}}',
								colorWell: 'heredityColorinput{{$heredity->getHid()}}',
								closeText: "{{gt text='Close'}}",
							})

							/* ]]> */
						</script>
						<input type="text" id="heredityCaid{$heredity->getHid()}" style="display:none;" value="{$heredity->getCaid()}"/>
					</td>
					<td>
						<a id="delete{$heredity->getHid()}" onclick="heredityDelete({$heredity->getHid()}, false)" class="z-button">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'}</a>
						<input id="heredityDeleted{$heredity->getHid()}" name="heredityDeleted{$heredity->getHid()}" type="text" value="0" style="display:none;"/>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
	
   </fieldset>
   
   <fieldset>
   <legend>{gt text="Groups"}</legend>
   <br/><br/>
   {gt text="Every date is show by a color. This color can selected for every group. This overwrides the normal group color for all dates of this calendar."}<br/><br/>
   
   <input type="text" style="display:none;" value="0" id="newColorsNum" name="newColorsNum"/> 
   <a onclick="colorSave()">{gt text="create color for group"}</a>
   <select name="GroupSelection" id="GroupSelection"> 
		<option value="0">{gt text="no group selected"}</option> 
		{foreach from=$groupSelection key=i item='option'}
			<option value="{$option->getGid()}"
			{if $groupHide.$i}
				style="display:none;"
			{/if}
			>{$option->getName()}</option> 
		{/foreach}
	</select>
	
	<br/><br/>
	
	<table class="z-datatable" id="colorlist">
		<thead>
			<tr>
				<th>{gt text='Id'}</th>
				<th>{gt text='Group'}</th>
				<th>{gt text='Color'}</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$colors item='color'}
				<tr id="Color{$color->getCid()}">
					<td>{$color->getCid()}</td>
					<td>{$color->getGroupName()}</td>
					<td>
						<div class="z-formrow">
						    <input id="groupColorInput{$color->getCid()}" class="colorpicker" name="groupColorInput{$color->getCid()}" type="text" value="{$color|safetext}" maxlength="7" size="7" oninput="groupColorInput({$color->getCid()})"/>
						</div>
						<script type="text/javascript" charset="utf-8">
						    /* <![CDATA[ */

							var color{{$color->getCid()}}Picky = new PickyColor({
								field: 'groupColorInput{{$color->getCid()}}',
								color: '{{$color->getColor()|safetext}}',
								colorWell: 'groupColorInput{{$color->getCid()}}',
								closeText: "{{gt text='Close'}}",
							})

							/* ]]> */
						</script>
						<input type="text" id="colorGid{$color->getCid()}" style="display:none;" value="{$color->getGid()}"/>
					</td>
					<td>
						<a id="delete{$color->getCid()}" onclick="colorDelete({$color->getCid()})" class="z-button">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'}</a>
						<input id="colorDeleted{$color->getCid()}" name="colorDeleted{$color->getCid()}" type="text" value="0" style="display:none;"/>
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
