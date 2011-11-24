<?php
class Mage_Config
{
    private $_environment = null;
    private $_scm = null;
    
    public function loadEnvironment($environment)
    {
        if (($environment != '') && file_exists('.mage/config/environment/' . $environment . '.yaml')) {
            $this->_environment = @yaml_parse_file('.mage/config/environment/' . $environment . '.yaml');            
        }
    }
    
    public function loadSCM()
    {
        if (file_exists('.mage/config/scm.yaml')) {
            $this->_scm = @yaml_parse_file('.mage/config/scm.yaml');            
        }
    }
    
    public function getEnvironment()
    {
        return $this->_environment;
    }
    
    public function getSCM()
    {
        return $this->_scm;
    }

    public function getHosts()
    {
        $config = $this->getEnvironment();
        $hosts = array();
        
        if (isset($config['hosts'])) {
            $hosts = (array) $config['hosts'];
        }
        
        return $hosts;
    }
    
    public function getTasks($type = 'tasks')
    {
        switch ($type) {
            case 'pre':
                $type = 'pre-tasks';
                break;
                
            case 'post':
                $type = 'post-tasks';
                break;
                
            case 'tasks':
            default:
                $type = 'tasks';
                break;
        }
        
        $tasks = array();
        $config = $this->getEnvironment();

        if (isset($config[$type])) {
            $tasks = (array) $config[$type];
        }

        return $tasks;
    }
    
    public function getConfig($host = false)
    {
        $taskConfig = array();
        $taskConfig['deploy'] = $this->getEnvironment();
        $taskConfig['deploy']['host'] = $host;
        $taskConfig['scm'] = $this->getSCM();
        
        unset($taskConfig['deploy']['tasks']);
        unset($taskConfig['deploy']['hosts']);
        
        return $taskConfig;
    }
}