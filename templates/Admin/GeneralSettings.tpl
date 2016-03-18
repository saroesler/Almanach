{include file='Admin/Header.tpl' __title='General Settings' icon='config'}

{form cssClass="z-form"}
	<fieldset>
	<legend>Settings</legend>
	<div class="z-formrow">
        {formlabel for='savetime' __text='Save Time (in days):'}
		{formtextinput id="savetime" maxLength=4 mandatory=true text=$savetime}
	</div>
	<div class="z-formrow">
		<div class="z-informationmsg"><p>{gt text="If you dont want users dont being administrators give dates extra colors delet this hook."}</p></div>
		{formlabel for='datecolor' __text='Allow extra colors for dates:'}
		{formcheckbox id="datecolor" checked=$datecolor}
	</div>
	</fieldset>
	<fieldset>
	<legend>Profile Settings</legend>
	<div class="z-informationmsg"><p>{gt text="Please insert in the following fields the name of the Profile- fields."}</p></div>
	<div class="z-formrow">
		{formlabel for='formofaddress' __text='Form of address:'}
		{formtextinput id="formofaddress" maxLength=200 mandatory=true text=$formofaddress}
	</div>
	<div class="z-formrow">
		{formlabel for='surname' __text='Surname:'}
		{formtextinput id="surname" maxLength=200 mandatory=true text=$surname}
	</div>
	<div class="z-formrow">
		{formlabel for='firstname' __text='Frist name:'}
		{formtextinput id="firstname" maxLength=200 mandatory=true text=$firstname}
	</div>
   </fieldset>
   
   <fieldset>
	   <div class="z-formbuttons z-buttons">
		   {formbutton class="z-bt-ok" commandName="save" __text="Save"}
		   {formbutton class="z-bt-cancel" commandName="cancel" __text="Cancel"}
	   </div>
   </fieldset>
{/form}
{include file='Admin/Footer.tpl'}
