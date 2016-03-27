{include file='Admin/Header.tpl' __title='Groups' icon='group'}
{pageaddvar name="javascript" value="modules/Almanach/javascript/Group.js"}
{pageaddvar name="javascript" value="javascript/picky_color/picky_color.js"}
{pageaddvar name="stylesheet" value="javascript/picky_color/picky_color.css"}

<script type="text/javascript" charset="utf-8">
	var colorpickerPicky = new Array();
</script>

<div class="z-informationmsg">
	<p>{gt text="Groups are the smalest entity. You can connect every date with a group. In the calendar you can filter the dates by its groups. Each group get a color. All dates of this group is shown by this color, if neigther there is no special color for this date nor a color of the calendar. You can set a new color of the group by every calendar. User can get the permission to create dates of a group. This musst be set by Zikula- permissionrules. You can use groups to categorisize the dates."}</p>
</div>
<div id="grouplistcontainer">
	<table class="z-datatable" id="grouplist">
		<thead>
			<tr>
				<th>{gt text='Id'}</th>
				<th>{gt text='Name'}</th>
				<th>{gt text='Color'}</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$groups item='group'}
				<tr id="Group{$group->getGid()}">
					<td>{$group->getGid()}</td>
					<td>
						<span id="nameview{$group->getGid()}">{$group->getName()}</span>
						<input type="text" name="name{$group->getGid()}" id="name{$group->getGid()}" maxlength="200" style="display:none;"/>
					</td>
					<td>
						<div class="z-formrow">
							<div id="colorview{$group->getGid()}" style="background-color:{$group->getColor()}; padding:5px;">
								<p>{$group->getColor()}</p>
							</div>
						    <input id="GroupColorinput{$group->getGid()}" class="colorpicker" name="GroupColorinput{$group->getGid()}" type="text" value="{$group->getColor()|safetext}" maxlength="7" size="7" style="display:none"/>
						</div>
						<script type="text/javascript" charset="utf-8">
						    /* <![CDATA[ */

							colorpickerPicky[{{$group->getGid()}}] = new PickyColor({
								field: 'GroupColorinput{{$group->getGid()}}',
								color: '{{$group->getColor()|safetext}}',
								colorWell: 'GroupColorinput{{$group->getGid()}}',
								closeText: "{{gt text='Close'}}",
							});

							/* ]]> */
						</script>
					</td>
					<td>
						<a id="editStart{$group->getGid()}" onclick="groupEditStart({$group->getGid()})" class="z-button">{img src='xedit.png' modname='core' set='icons/extrasmall'}</a>
						<a id="editSave{$group->getGid()}" onclick="groupEditSave({$group->getGid()})" class="z-button" style="display:none;">{img src='button_ok.png' modname='core' set='icons/extrasmall'}</a>
						<a id="editCancel{$group->getGid()}" onclick="groupEditCancel({$group->getGid()})" class="z-button" style="display:none;">{img src='button_cancel.png' modname='core' set='icons/extrasmall'}</a>
						<a id="delete{$group->getGid()}" onclick="groupDelete({$group->getGid()})" class="z-button">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'}</a>
					</td>
				</tr>
			{/foreach}
			<tr>
				<td></td>
				<td><input type="text" name="name" id="name" maxlength="200"/></td>
				<td>
					<div class="z-formrow">
					    <input id="newGroupColor" class="colorpicker" name="newGroupColor" type="text" value="" maxlength="7" size="7"/>
					</div>
					<script type="text/javascript" charset="utf-8">
					    /* <![CDATA[ */

						var newGroupColorPicky = new PickyColor({
							field: 'newGroupColor',
							color: '',
							colorWell: 'newGroupColor',
							closeText: "{{gt text='Close'}}",
						})

						/* ]]> */
					</script>
				</td>
				<td>
					<a onclick="groupSave()" class="z-button">{img src='button_ok.png' modname='core' set='icons/extrasmall'}</a>
					<a onclick="groupClear()" class="z-button">{img src='button_cancel.png' modname='core' set='icons/extrasmall'}</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
	
<p id="editImg" style="display:none;">{img src='xedit.png' modname='core' set='icons/extrasmall'}</p>
<p id="saveImg" style="display:none;">{img src='button_ok.png' modname='core' set='icons/extrasmall'}</p>
<p id="cancelImg" style="display:none;">{img src='button_cancel.png' modname='core' set='icons/extrasmall'}</p>
<p id="deleteImg" style="display:none;">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'}</p>

{include file='Admin/Footer.tpl'}
