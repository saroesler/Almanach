{include file='Admin/Header.tpl' __title='Groups' icon='view'}
{pageaddvar name="javascript" value="modules/Almanach/javascript/Group.js"}

<div id="grouplistcontainer">
	<table class="z-datatable" id="grouplist">
		<thead>
			<tr>
				<th>{gt text='Id'}</th>
				<th>{gt text='Name'}</th>
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
					<a onclick="groupSave()" class="z-button">{img src='button_ok.png' modname='core' set='icons/extrasmall'}</a>
					<a onclick="groupClear()" class="z-button">{img src='button_cancel.png' modname='core' set='icons/extrasmall'}</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
	
{include file='Admin/Footer.tpl'}
