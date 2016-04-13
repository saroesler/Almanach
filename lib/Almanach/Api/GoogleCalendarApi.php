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
}
