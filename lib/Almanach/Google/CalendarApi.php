<?php

/**
 * This is the admin controller class providing navigation and interaction functionality.
 */
class Almanach_Google_CalendarApi //extends Zikula_AbstractController
{
	/**
	* @brief Main function.
	* @throws Different views according to the access
	* @return template Admin/Main.tpl
	* 
	* @author Sascha Rösler
	*/

	const pathToKeyFile = '/modules/Almanach/secret/google.p12';
	const pathToIncludeFiles = '/modules/Almanach/vendor/google/apiclient/src';

	private $service;
	private $eMail;


	public function __construct ($email)
	{
		$this->eMail = $email;
		if(self::apiExist())
			return false;
		//benötigte Funktionen/Klassen
		set_include_path(getcwd(). self::pathToIncludeFiles);
		require_once 'Google/autoload.php';

		//--------------------------- Main ----------------------------

		// Die Verbindung zum Kalender aufbauen
		$client = new Google_Client();
		$client->setApplicationName("Kalender");
		$this->service = new Google_Service_Calendar($client);
		$key = file_get_contents(getcwd(). self::pathToKeyFile);
		$cred = new Google_Auth_AssertionCredentials($email, array('https://www.googleapis.com/auth/calendar'), $key);
		$client->setAssertionCredentials($cred);
	}	
	
	public function __destruct (){
		
	}
	
	public static function apiExist(){
		return ModUtil::apiFunc('Almanach', 'GoogleCalendarApi', 'apiExist');
	}
	
	public function createEvent($calendarID, $param){
		if(self::apiExist())
			return false;
		return $this->updateEvent(0, $calendarID, $param);
	}
	
	public function updateEvent($eventID, $calendarID, $param){
		if(self::apiExist())
			return false;
		// Event schreiben
		//================
		
		if($eventID == 0 )
			$event = new Google_Service_Calendar_Event();
		else
			$event = $this->service->events->get($calendarID, $eventID);
			
		$event->setSummary($param['titel']);
		$event->setLocation($param['location']);
		$event->setDescription($param['description']);

		$start = new Google_Service_Calendar_EventDateTime();
		$end = new Google_Service_Calendar_EventDateTime();
		
		$start->setTimeZone($param['start']->getTimezone()->getName());
		$end->setTimeZone($param['end']->getTimezone()->getName());
		$start->setDateTime($param['start']->format(\DateTime::RFC3339));
		$event->setStart($start);
		$end->setDateTime($param['end']->format(\DateTime::RFC3339));
		$event->setEnd($end);
		
		$group = new Google_Service_Calendar_EventOrganizer();
		if($param['userId'] != '')
			$group->setDisplayName($param['group'].' ['.$param['groupId'].']'.' | ('.$param['user'].' ['.$param['userId'].'])');
		else
			$group->setDisplayName($param['group'].' ['.$param['groupId'].']');
		if($param['googlePlusId'] != '')
			$group->setId($param['googlePlusId']);
		
		//Auswahl der Wiederholung
		switch($param['recur'])
		{
			// keine
			case 0:
				break;

			// wöchentlich
			case 1:
				$event->setRecurrence(array('RRULE:FREQ=WEEKLY;INTERVAL=1'));
				break;

			// 14 Tage
			case 2:
				$event->setRecurrence(array('RRULE:FREQ=WEEKLY;INTERVAL=2'));
				break;

			// monatlich
			case 3:
				$event->setRecurrence(array('RRULE:FREQ=MONTHLY;INTERVAL=1'));
				break;

			// jährlich
			case 4:
				$event->setRecurrence(array('RRULE:FREQ=YEARLY;INTERVAL=1'));
		}

		switch($param['visibility'])
		{
			// keine
			case 2:
				$event->setVisibility('confidential');
				break;

			// wöchentlich
			case 1:
				$event->setVisibility('private');
				break;

			// 14 Tage
			case 0:
				$event->setVisibility('public');
				break;
		}

		if($param['color'] != '' && $param['color'] != '#')
			$event->setColorId($this->calculateColor($param['color']));
		
		if($eventID == 0)
			$createdEvent = $this->service->events->insert($calendarID, $event);
			
		else
			$createdEvent = $this->service->events->update($calendarID, $event->getId(), $event);
			
		return $createdEvent->getId();
	}
	
	public function deleteEvent($calendarID, $eventID){
		if(self::apiExist())
			return false;
		$this->service->events->delete($calendarID, $eventID);
	}
	
	public function getEvents($calendarID, $maxResults, $timeMin){
		if(self::apiExist())
			return false;
		// Übergeben der Daten der Suchanfrage
		$startDate = new DateTime();
		$startDate->sub(new DateInterval('P'. $timeMin .'D'));
		$optParams = array('singleEvents'     => True,
				                 'timeMin'             => $startDate->format(DateTime::RFC3339),
				                'orderBy'             => 'startTime',
				                'maxResults'        => $maxResults);

		// Die Suchanfrage ausführen
		$events = $this->service->events->listEvents($calendarID, $optParams);

		$returnData = array();
		// Das Ergebnis durchsuchen
		foreach ($events->getItems() as $event)
		{
			$thisData = array();
			
			$thisData['eventId'] = $event->getId();
			// Auslesen der Startzeit
			if($event->getStart()->dateTime <> "")
			{
			   $thisData['start']     = $event->getStart()->dateTime;
			   $thisData['end']       = $event->getEnd()->dateTime;
			}
			else
			{
			    $thisData['start']   = $event->getStart()->date;
			    $thisData['end']     = $event->getEnd()->date;
			}
			
			$thisData['titel'] = $event->getSummary();
			$thisData['location'] = $event->getLocation();
			$thisData['description'] = $event->getDescription();

			$group = $event->getOrganizer();
			$thisData['googlePlusId'] = $group->getId();
			
			$groupIdStart = strpos($group->getDisplayName(), '[');
			$groupIdEnd = strpos($group->getDisplayName(), ']');
			
			// Beachten Sie die Verwendung von ===
			if ($groupIdStart === false || $groupIdEnd === false) {
				//throw new Exception('There is no valid group Id and user Id');
			} else{
			
				$userStart = strpos($group->getDisplayName(), '| (', $groupIdEnd) + 3;
			
				if($groupIdStart < $userStart){
			
					$groupIdStart += 1;
					$groupIdEnd -= 1;
		
					$thisData['group'] = substr($group->getDisplayName(), 0, $groupIdStart -2);
					$thisData['groupId'] = substr($group->getDisplayName(), $groupIdStart, $groupIdEnd- $groupIdStart);
				} else {
					$thisData['group'] = 'no Group';
					$thisData['groupId'] = -1;
				}
		
				if($userStart === true){
					$userIdStart = strpos($group->getDisplayName(), '[',$userStart) + 1;
					$userIdEnd = strpos($group->getDisplayName(), ']', $userStart) - 1;
			
					$thisData['user'] = substr($group->getDisplayName(), $userStart, $userIdStart -2);
					$thisData['groupId'] = substr($group->getDisplayName(), $userIdStart, $userIdEnd- $userIdStart);
				}
			}
			
					
			//Auswahl der Wiederholung
			switch($event->getRecurrence()[0])
			{
				// wöchentlich
				case 'RRULE:FREQ=WEEKLY;INTERVAL=1':
					$thisData['recur'] = 1;
					break;

				// 14 Tage
				case 'RRULE:FREQ=WEEKLY;INTERVAL=2':
					$thisData['recur'] = 2;
					break;

				// monatlich
				case 'RRULE:FREQ=MONTHLY;INTERVAL=1':
					$thisData['recur'] = 3;
					break;

				// jährlich
				case 'RRULE:FREQ=YEARLY;INTERVAL=1':
					$thisData['recur'] = 4;
					break;
					
				default:
					//keine
					$thisData['recur'] = 0;
				break;
			}

			switch($event->getVisibility())
			{
				// keine
				case 'confidential':
					$thisData['visibility'] = 2;
					break;

				// wöchentlich
				case 'private':
					$thisData['visibility'] = 1;
					break;

				// 14 Tage
				case 'public':
					$thisData['visibility'] = 0;
					break;
			}
			
			$returnData[] = $thisData;
		}
		return $returnData;
	}
	
	public function calculateColor($mycolor){
		$colors = $this->service->colors->get();
		$difference = array();
	
		// Print available event colors.
		foreach ($colors->getEvent() as $key => $color) {
		  $differenceR = hexdec(substr($color->getBackground(), 1,2)) - hexdec(substr($myColor, 1,2));
		  $differenceG = hexdec(substr($color->getBackground(), 3,2)) - hexdec(substr($myColor, 3,2));
		  $differenceB = hexdec(substr($color->getBackground(), 5,2)) - hexdec(substr($myColor, 5,2));
		  $difference[] = array('difference' => $differenceR + $differenceG + $differenceB, 'key' => $key);
		}

		usort($difference, array("Almanach_Google_CalendarApi", "differenceCmp"));

		return $difference[0]['key'];
	}
	
	public function differenceCmp($a, $b)
	{
		if($a['difference'] == $b['difference'])
			return 0;
		return ($a['difference'] < $b['difference']) ? -1: 1;
	}
}
