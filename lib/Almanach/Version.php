<?php
/**
 * Version.
 */
class Almanach_Version extends Zikula_AbstractVersion
{
    /**
     * Module meta data.
     *
     * @return array Module metadata.
     */
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = $this->__('Almanach');
        $meta['description']    = $this->__("A calendar module to manage many calendars"); ///@todo description
        $meta['url']            = $this->__('Almanach');
        $meta['version']        = '0.0.8';
        $meta['official']       = 0;
        $meta['author']         = 'Sascha Rösler';
        $meta['contact']        = 'sa-roelser@t-online.de';
        $meta['admin']          = 1;
        $meta['user']           = 1;
        $meta['securityschema'] = array(); ///@todo Security schema
        $meta['core_min'] = '1.3.0';
        $meta['core_max'] = '1.4.1';
        
        $meta['capabilities'] = array();
        $meta['capabilities'][HookUtil::SUBSCRIBER_CAPABLE] = array('enabled' => true);
        
        return $meta;
    }
    
    protected function setupHookBundles()
    {
	//$bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.vermeldungen.ui_hooks.main', 'ui_hooks', $this->__('vermeldungen main Hooks'));
	//$bundle->addEvent('form_edit', 'vermeldungen.ui_hooks.main.main');
	//$this->registerHookSubscriberBundle($bundle);
	
	// $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.vermeldungen.filter_hooks.users_view', 'filter_hooks', $this->__('Users view'));
        //$bundle->addEvent('filter', 'vermeldungen.filter_hooks.users_view');
       // $this->registerHookSubscriberBundle($bundle);
    }
}
