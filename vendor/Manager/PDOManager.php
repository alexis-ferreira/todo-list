<?php
// Cette classe représentera la connexion à la BDD. L'approche Singleton nous permettra d'être certain qu'il n'y est pas plusieurs connexion initialisée.
namespace Manager;
// print '<pre>'; print_r($GLOBALS); print '</pre>';
// use Config; // use PDO; // use PDOException;

class PDOManager
{
    private static $instance = null ; // Contiendra l'instance de notre classe.
    protected $pdo; // contiendra l'instance de PDO
//-------
	private function __construct(){}
	private function __clone(){}
//-------	
    public static function getInstance()
    {
        // Si on n'a pas encore instancié notre classe.
        if(is_null(self::$instance))  // On s'instancie nous-mêmes
            self::$instance = new self; // revient à faire un new PDOManager
        return self::$instance; // je retournerais toujours le même objet, référence [1]
    }
//-------
    public function getPdo()
    {
        include_once __DIR__ . '/../../app/Config.php'; // on ressort d'ici et on se dirige au bon endroit
		$config = new \Config(); // Config a été déclaré sans namespace dans l'espace global d'où l'utilisation du \
        $connect = $config->getParametersConnect(); // on récup les params de connexion à travers la config
        try
		{
            $this->pdo = new \PDO("mysql:dbname=" . $connect['dbname'] . ";host=" . $connect['host'], $connect['user'], $connect['password'], array(\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION));
        }	// PDO est une classe existante déclaré dans l'espace global d'où l'utilisation du \
        catch(\PDOException $e)	// PDOException est une classe existant déclaré dans l'espace global d'où l'utilisation du \
		{
                echo 'Connexion échouée : ' . $e->getMessage();
        }
        return $this->pdo;
    }
}
//---------------------------------------------------------------
// $instance = PDOManager::getInstance();
// var_dump($instance); // même objet

// $instance2 = PDOManager::getInstance();
// var_dump($instance2); // même objet
// echo __NAMESPACE__ ;
// $pdo = PDOManager::getInstance()->getPdo();
// var_dump($pdo);