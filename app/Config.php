<?php
class Config {

    protected $parameters;

    public function __construct()
    {
		// echo 'config!';
        require	__DIR__ . '/config/parameters.php'; // éviter le require_once
		// global $parameters; // dans certains cas, global peut faire fonctionner ou dysfonctionner...
        $this->parameters = $parameters;
    }
    public function getParametersConnect()
    {
        return $this->parameters['connect'];
    }
}
//---------------------------------
// $conf = new Config ;
// print '<pre>'; print_r($conf->getParametersConnect()); print '</pre>';