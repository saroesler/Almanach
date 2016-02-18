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
		</td>
	<td>
		<a id="editStart{$group->getGid()}" onclick="groupEditStart({$group->getGid()})" class="z-button">{img src='xedit.png' modname='core' set='icons/extrasmall'}</a>
		<a id="editSave{$group->getGid()}" onclick="groupEditSave({$group->getGid()})" class="z-button" style="display:none;">{img src='button_ok.png' modname='core' set='icons/extrasmall'}</a>
		<a id="editCancel{$group->getGid()}" onclick="groupEditCancel({$group->getGid()})" class="z-button" style="display:none;">{img src='button_cancel.png' modname='core' set='icons/extrasmall'}</a>
		<a id="delete{$group->getGid()}" onclick="groupDelete({$group->getGid()})" class="z-button">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'}</a>
	</td>
</tr>
