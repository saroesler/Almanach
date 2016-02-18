Almanach
========
This is a calendar module for zikula. You can create a lot of calendars, witch inherits from each other. There are groups as smalest entity, too. Every date can belongs to a group and can be entered in one ore more calendars. Dates can be colored, too. There is a ranking of different color modes. You can read more in Color- Ranking below.

#Installation
You can download this module and instal it via the zikula- interface. If module needs datafields in the profile module.

#Getting Started!
At first you should create a group:

#Groups
Groups are the smalest entity of the calendar. You can create a group in the 'Group'- tap. You can choose a color for all datse of the group. The date is colored in this color, if there isn't another color for the calendar or the date. Via permissionruels in zikula you can decide, which person can create dates for which group.

In the next Step you should create a calendar:

##Calendars
The calendats or almanachs hold dates. You can create them via the 'Calendar'-tab. You have to input a name for the calendar. Next you can decide if dates can entered in this calendar. If not, the calendar only holds dates given by another calendar. You can descide if there can be two dates at the same time. This is called overlapping. If you want, that dates can overlap you have to put the hook. If you want, you can set an extra template to show this calendar.

After setting the general setting of the calendar, you can choose calendars, which are part of this calendar. You can choose a calendar in the dropdown- list and click to 'new Heredity'. In the dropdown list, there are all calendars, which are not part of this calendar. There aren't calendars having this calendar as part of. 

For each heredity you can choose a color. Each date being part of the other calendar gets the new color for this calendar. If you dont want to overwrite the dates color, you dont input a new color.

You can set extra colors for the date of special groups for this calendar. You can choose the group in the group-dropdown-lost and set a color to the group. All dates of this group gets a new color in this calendar and each calendar having this one as part of.

##Permissions
At the next step you can set the permission for this module. 
- ACCESS_ADMIN: All users which can create, edit and delete groups and calendars and which can set other important settings get ACCESS_ADMIN for the entire module
- ACCESS_ADD: Users administrating a calendar get ACCESS_ADD for the component 'Almanach::Almanach' and the instance '::$aid'. They can create, edit and delete all dates of all groups entered in the calendar with the id aid. 
- ACCESS_EDIT: Users with the permission ACCESS_EDIT for a calendar and a group can create a date for this group and input it into the calendar. They get ACCESS_EDIT for the component 'Almanach::Almanach' and the instance '::$aid' and ACCESS_EDIT for the component 'Almanach::Group' and the instance '::$gid', too. User having ACCESS_EDIT of a group can see protected dates of the group, too. 
- ACCESS_MODERATE: User having ACCESS_MODERATE to a calendar see hidden dates. They get ACCESS_MODERATE for the component 'Almanach::Almanach' and the instance '::$aid'.
- ACCESS_COMMENT: User having ACCESS_COMMENT can create dates for themself.
- ACCESS_READ: User having ACCESS_READ can read the given calendar. They get ACCESS_READ for the component 'Almanach::Almanach' and the instance '::$aid'.

After setting the permissionrules you can create a date:

##Date
You can create a date via the 'Create new Date'- tab. If you have to input the start- and the end- date and a title for this date. You can set the location, where the date take place. In 'description', you can describe your date. You can set a group to which this date beongs to. In the list are all groups shown you have the permission to. You can set a color for this date. In 'Visibility' you can set who can see this date:
- public: Public dates can be seen by all user having the permission to a calendar.
- hidden: Hidden dates can only be seen by users having the extra permission for hidden dates.
- secret: Secret dates can only seen by the group and administrators of the calendar.
If guests are welcome you can set the hook to the last field. This will be shown especially to the other users.

Then you can select the calendars where this date is part of. You do this via the calendar-dorpdown-list and a click to 'Input this Date'. In the dropdown-list you can see all calendars where you can input this date. Calendars you dont have the permission or calendars dont having the option to input a date generelly are excluded from the list. You can set different colors to the calendars.

#Color-Ranking
This shows the ranking of the different colors. The higher color overwrites the deeper one.
1. Color given by the heredity
2. Color given to the calendar by the date
3. Color given to the date
4. Color given to the group
If there should no overwriting, the color field has to be empty.
