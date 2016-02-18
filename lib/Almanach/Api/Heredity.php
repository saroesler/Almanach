<?php
/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class Almanach_Api_Heredity extends Zikula_AbstractApi
{
	/*
	* This function tests, if an almanach inherits the given one
	* @return: true, if it inherits, false, if there is no heritage
	*/
	public function isHeredity($args)
	{
		return $this->isHeredityRekrusiv($args['aid'], $args['paid']);
	}
	
	/*
	* This function tests, if the given almanach is inherited by the parent almanach 
	* or of his childs.
	* @param: aid: id of the almanach witch is searched
	* @param: paid: almanach id of the entree point
	* @return: true if there is an heredity, false, if there is no one
	*/
	protected function isHeredityRekrusiv($aid, $paid){
		$heredities = $this->entityManager->getRepository('Almanach_Entity_Heredity')->findBy(array('paid'=>$paid));
		
		foreach($heredities as $heredity){
			//if the given almanach is child
			if($heredity->getCaid() == $aid)
				return true;
				
			// the cild has a child witch is the given almanach
			if($this->isHeredityRekrusiv($aid, $heredity->getCaid()) == true)
				return true;
		}
		
		return false;
	}
}
