<?php
/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class Almanach_Api_GoogleCalendarApi extends Zikula_AbstractApi
{
	const pathToKeyFile = '/modules/Almanach/secret/google.p12';
	const apiEmailAddressField = 'googleApiAddress';
	
	public function getCalendarIdByAid($aid){
		$calendar = $this->entityManager->find('Almanach_Entity_Almanach', $aid);
		return $calendar->getGoogleCalendarId();
	}
	
	public static function apiExist()
	{
		if(!file_exists(getcwd(). self::pathToKeyFile))
			return 1;
		if(ModUtil::getVar('Almanach', self::apiEmailAddressField) == '')
			return 2; 
		return 0;
	}
	
	public function getApi(){
		$test =  new Almanach_Google_CalendarApi(ModUtil::getVar('Almanach', self::apiEmailAddressField));
		return $test;
	}
	
	public function pushCalendar($aid){
		$googleApi = $this->getApi();
		
		$connections = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('aid'=>$aid));
		$almanach = $this->entityManager->find('Almanach_Entity_Almanach', $aid);
        foreach($connections as $key => $item){
        	$date = $this->entityManager->find('Almanach_Entity_Date', $item->getDid());
        	
        	$item->setGoogleId(
				$googleApi->createEvent($this->getCalendarIdByAid($aid),
					array('titel' => $date->getTitle(),
					'location' => $date->getLocation(),
					'description' => $date->getDescription(),
					'start' => $date->getStartdate(),
					'end' => $date->getEnddate(),
					'user' => $date->getUserName(),
					'userId'=> $date->getUid(),
					'group' => $date->getGroupName(),
					'groupId' => $date->getGid(),
					'googlePlusId' => false,
					'recur' => false,
					'visibility' =>  $date->getVisibility()))
				);
			$this->entityManager->persist($item);
			$this->entityManager->flush();
        }
	}
	
	public function pullCalendar(){
		$googleApi = $this->getApi();
		$oldGoogleEvents = array();
		
		$em = $this->getService('doctrine.entitymanager');
		$qb = $em->createQueryBuilder();
		$qb->select('p')
		   ->from('Almanach_Entity_Almanach', 'p')
		   ->where('p.googleCalendarId <> \'\'');
	    $calendars = $qb->getQuery()->getArrayResult();
	    
		foreach($calendars as $calendar){
			if($calendar['pullGid'] < 1 || $calendar['pullUid'] < 1 )
				continue;
			
			//get all google events are part of this almanach of the local db
			$qb = $em->createQueryBuilder();
			$qb->select('p')
			   ->from('Almanach_Entity_AlmanachElement', 'p')
			   ->where('p.googleId <> \'\'')
			   ->where('p.aid LIKE :aid')
			   ->setParameter('aid', $calendar['aid']);
			$myEvents = $qb->getQuery()->getArrayResult();
	    
			//get list of all google events of this almanach
			$events = $googleApi->getEvents($calendar['googleCalendarId'], 200, 0);
			
			foreach($events as $event){
				
				$myEventIndex = $this->getEventId($myEvents, $event['eventId']);
				$connection = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('googleId'=>$event['eventId']));
				
				if(!isset($connection[0])){
					$date = new Almanach_Entity_Date();
					$date->setShortDescription($event['description']);
				}else{
					$date =$this->getDateByGoogleId($event['eventId']);
				}
				$date->setTitle($event['titel']);
				$date->setStartdate($event['start']);
				$date->setEnddate($event['end']);
				$date->setCreationdate('');
				$date->setLocation($event['location']);
				$date->setVisibility($event['visibility']);
				$date->setDescription($event['description']);
				$date->setUid($calendar['pullUid']);
				$date->setGid($calendar['pullGid']);
				
				if($date->getShortDescription() == ''){
					$date->setShortDescription($event['description']);
				}
				
				
				$this->entityManager->persist($date);
    			$this->entityManager->flush();
    			
    			//write the date in a new calendar
    			if($myEventIndex < 0){
					$connection = new Almanach_Entity_AlmanachElement();
					$connection->setDid($date->getDid());
					$connection->setAid($calendar['aid']);
					$connection->setGoogleId($event['eventId']);
					$this->entityManager->persist($connection);
					$this->entityManager->flush();
				} else
					unset($myEvents[$myEventIndex]);
			}
			$oldGoogleEvents[] = $myEvents;		
		}
		
		//delete old events
		foreach($oldGoogleEvents as $myEvents){
			foreach($myEvents as $item){
				$connection = $this->entityManager->find('Almanach_Entity_AlmanachElement', $item['eid']);
				$this->entityManager->remove($connection);
				$this->entityManager->flush();
			}
		}
	}
	
	private function getEventId($array, $googleId){
		foreach($array as $key => $item){
			if($item['googleId'] == $googleId)
				return $key;
		}
		return -1;
	}
	
	public function getDateByGoogleId($googleId){
		$element = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('googleId' => $googleId));
		return $this->entityManager->find('Almanach_Entity_Date', $element[0]->getDid());
	}
}
