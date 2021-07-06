<?php
// Controller général de l'application. Permet d'appeler des repository et contient notamment la méthode render() qui affiche un rendu à l'écran. de manière générique.
namespace Controller;

// use Manager\EntityRepository; //  USE déclenche l'autoload pour que la classe soit chargée et ainsi éviter une erreur.
// use Manager\PDOManager; //  USE déclenche l'autoload pour que la classe soit chargée et ainsi éviter une erreur.

class Controller {
	protected $table;
	
    public function __construct(){} // $this->getError();
    // public function getError() {$class = 'Manager\\' . 'Error'; return new $class; }

    public function getRepository($table) // on récup "employe" --- méthode qui permet d'instancier un objet EmployeRepository!!
    {
        $class = 'Repository\\' . $table . 'Repository';
        if(!isset($this->$table))
		{
            $this->table = new $class; // on instancie un objet "employe" (s'il n'existe pas) - donc la 1ere fois, oui!
        }
		
        return $this->table; // sinon on retourne l'objet déjà instancié auparavant!
    }

	// $layout : le design général du site -- $template : la view qui rentre à l'intérieur -- $parameters : les parametres disponible dans le layout et template.
    public function render($layout, $template, $parameters = array()) // permet de rendre un affichage
    {
		// echo __DIR__ ; echo __DIR__ . '/../../src/';
		// echo get_called_class(); // get_called_class() : Retourne le nom de la classe depuis laquelle une méthode statique a été appelée
        $dirViews = __DIR__ . '/../../src/' . str_replace('\\', '/', get_called_class() . '/../../Views');
		// echo $dir_views ;
        $ex = explode('\\', get_called_class()); // explode transforme la chaine en tableau dès qu'il y a un anti slash \ et j'en met 2 sinon php croira que je veux échaper une quote '.
		// print '<pre>'; print_r($ex); print '</pre>';
        $dirFile = str_replace('Controller', '', $ex[2]);  // on retire le mot Controller grâce à str_replace car dans la views il y a un dossier au nom du module "Employe" et non pas "ControllerEmploye".
		// echo $dir_file;

        $__template__ = $dirViews . '/' . $dirFile . '/' . $template; // chemin pour aller au bon endroit du template
        $__layout__ = $dirViews . '/' . $layout; // chemin pour aller au bon endroit du layout

        extract($parameters, EXTR_SKIP); //  extract permet de créer des variables au noms des indices (également pratique dans le cas $_POST). EXTR_SKIP: permet lors d'une collision, ne pas réécrire la variable existante. c'est à dire que si la variable $title avait été déclaré, elle aurait pris le dessus sur l'extraction du tableau de parametres.
		// cela me rends disponible la variable $title plutot que $parameters['title'], pareil pour la variable $employes.
		
		// print '<pre>'; print_r(get_defined_vars()); print '</pre>'; // voir les variables définies
		
        ob_start(); // Enclenche la temporisation de sortie, c'est à dire que ce qui suit ne se produit pas tout de suite, nous retenons l'affichage pour être totalement MVC - ob_start enclenche la bufferisation de sortie, permet de mettre tout ton site en "tampon" avant de l'afficher grâce à ob_end_flush --- on veut le faire en dernier pour respecter le MVC
			require $__template__; // permet de mettre le contenu dans une variable avec la ligne du dessous, l'envoi des données est retenue.
        $content = ob_get_clean(); // le template sera représenter par $content. cette variable est utilisé dans le layout. $content sera le require. la variable $content représente le contenu du fichier indiqué par $template

//        extract($parameters, EXTR_SKIP);
        ob_start(); // cette ligne est importante pour éviter une erreur sous mac et certainement en ligne.
			require $__layout__; // Explication : ob_start va retenir l’envoi de données, et ob_end_flush les liberera en dernier.
        return ob_end_flush(); // Envoie le contenu du tampon de sortie (s'il existe) et éteint la temporisation de sortie. Si vous voulez continuer à manipuler la valeur du tampon, vous pouvez appeler ob_get_contents() avant ob_end_flush() car le contenu du tampon est détruit après un appel à ob_end_flush(). Termine la temporisation de sortie.
    }
}