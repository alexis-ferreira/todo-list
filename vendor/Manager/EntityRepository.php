<?php
// 	Un repository centralise tout ce qui touche � la r�cup�ration de vos entit�s. Concr�tement donc, vous ne devez pas faire la moindre requ�te SQL ailleurs que dans un repository, c'est la r�gle. 
// Entity Repository ne connait pas "employes" ou autre, il connait que des entit�s. cela doit rester g�n�ric afin que cela soit r�-utilisable.
namespace Manager;

// Nous mettons des USE lorsque nous utilisons des classes sans faire de new directement dans le fichier courant, exemple avec un extends, un new d'un autre fichier, etc.

use Manager\PDOManager; // nous avons besoin de PDOManager car en utilisant ce namespace, nous aurons acc�s � tout ce qui est static de Manager/PDOManager. //  USE d�clenche l'autoload pour que la classe soit charg�e et ainsi �viter une erreur.
use PDO; // nous avons besoin d'utiliser ce namespace car on utilise les constante et autres propri�t�, function static de la classe PDO. //  USE d�clenche l'autoload pour que la classe soit charg�e et ainsi �viter une erreur.

class EntityRepository
{
    private $db;
    public function __construct(){}
//-------
    public function getDb()
    {
        if(!$this->db)
		{
            $this->db = PDOManager::getInstance()->getPdo(); // getInstance est retourne un objet, on peux donc remettre une fl�che pour appeler une m�thode.
        }
        return $this->db;
    }
//-------
    private function getTableName()
    {
		// echo get_called_class() . '<br />';
		// return 'employe'; // permet de faire les tests pendant la construction avant de revenir ici plus tard pour le faire dynamiquement.
		// echo strtolower(str_replace(array('Repository\\', 'Repository'), '', get_called_class()));
        return strtolower(str_replace(array('Repository\\', 'Repository'), '', get_called_class())); // je veux retirer Repository\\ et repository de Repository\EmployeRepository pour garder seulement Employe.
    }
//-------
    public function find($id)
    {
        $q = $this->getDb()->query('SELECT * FROM ' . $this->getTableName() . ' WHERE id' . ucfirst($this->getTableName()) . '= ' . (int) $id); // id'Employe' le premier champ est toujours le nom de la table. Caster en int permet d'�viter des erreurs de requete sql.
        $q->setFetchMode(PDO::FETCH_CLASS, 'Entity\\' . $this->getTableName());  // PDO::FETCH_CLASS permet d'instancier un objet, dans notre cas Entity\Employe, setFetchMode permet de prendre les resultats de la requ�te et d'affecter les propri�t�s de l'objet (il faut pour cela que l'orthographe des noms des colonnes/champs SQL correspondent aux propri�t� de l'objet)
        $r = $q->fetch(); 

        if(!$r) {
            return false;
        }
        else {
            // return $q->fetch(PDO::FETCH_ASSOC);
            return $r;
        }
    }
//-------
    public function findBy($req, $field)
    {
        $table=$this->getTableName();
        $q = $this->getDb()->query("SELECT * FROM  $table WHERE  $field='".$req."'");
        $q->setFetchMode(PDO::FETCH_CLASS, 'Entity\\' . $this->getTableName());  
        $r = $q->fetchAll(); 

        if(!$r) {
            return false;
        }
        else {
            // return $q->fetch(PDO::FETCH_ASSOC);
            return $r;
        }

    }

    public function findAll() // permet d'aller chercher toutes les informations sur un employe - c'est � ce moment l� que PDO est instanci�!
    {
        $q = $this->getDb()->query('SELECT * FROM ' . $this->getTableName()); // FROM employe
		// echo PDO::FETCH_PROPS_LATE;
		// echo PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE;
		// echo $this->getEntityClass();
		
        $q->setFetchMode(PDO::FETCH_CLASS, 'Entity\\' . $this->getTableName()); 
		 // PDO::FETCH_CLASS permet d'instancier un objet, dans notre cas Entity\Employe, setFetchMode permet de prendre les resultats de la requ�te et d'affecter les propri�t�s de l'objet (il faut pour cela que l'orthographe des noms des colonnes/champs SQL correspondent aux propri�t� de l'objet) PDO::FETCH_CLASS : retourne une instance de la classe d�sir�. c'est � ce moment l� que la classe 'employe' est instanci� tel un 'new'. Ca remplie directement les propri�t�s mais sans appeler les setteurs! est-ce un probl�me ? il faudrait plut�t appel� les setter lors de l'inscription des donn�es, comme cela pas de prob pour remplir directement les propri�t�s sans utiliser les setteurs. sinon il faut utiliser une m�thode assigne!
		 // var_dump(PDO::FETCH_CLASS);

        $r = $q->fetchAll(); // on r�cup�re un tableau array compos� d'objets
        if(!$r) { // si la query ne fonctionne pas
            return false;
        }
        else { // sinon, on retourne les r�sultats
            return $r;
        }
    }
//-------
	public function register()
	{
		// echo implode(',',array_keys($_POST)) . '<hr />' ;
		// echo  "'" . implode("','", $_POST) . "'";
        if(isset($_POST['password'])):
          $mdp=password_hash($_POST['password'], PASSWORD_DEFAULT) ;
          $_POST['password']=$mdp;     
        endif;

		$q = $this->getDb()->query('INSERT INTO '. $this->getTableName() . '(' . implode(',',array_keys($_POST)) . ') VALUES (' . "'" . implode("','", $_POST) . "'" . ')'); // array_keys me permet de parcourrir les indices plutot que les valeur pour annoncer les champs.
		return $this->getDb()->lastInsertId(); // dernier identifiant g�n�r�
	}
}





/**********
        // $q->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Entity\\' . $this->getEntityClass()); 
		// $q->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		/* Sans l'option PDO::FETCH_PROPS_LATE, on peut constater que l'ordre est le suivant : initialisation des propri�t�s de l'objet puis appel du constructeur alors qu'avec, c'est le contraire (appel du constructeur puis initialisation des variables).
		
		Quand tu fais FETCH_CLASS, tu n'as pas besoin de passer tes arguments au constructeur... Tu peux quand m�me en passer en sp�cifiant un tableau d'argument � utiliser dans le troisi�me argument de setFetchMode(), mais avec FETCH_CLASS, les variables sont remplies quels que soient leur visibilit� (private, protected, etc...).
		Si la variable n'existe pas, alors elle est cr�� automatiquement avec une visibilit� publique. 

		$stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,
                           'classname', 
                            <array of parameter names(in order) used in constructor>);
							
			$utils->setFetchMode(PDO::FETCH_INTO, new Utilisateur);
			Le fonctionnement de PDO::FETCH_INTO est totalement diff�rent. Son but n'est pas de cr�er un objet pour chaque r�sultat mais de n'en utiliser qu'un, qui est mis � jour pour chaque r�sultat.
			
			http://www.julp.fr/articles/2-4-exploitation-des-donnees-de-requetes-select.html
********/
		