<?php
// Controller g�n�ral de l'application. Permet d'appeler des repository et contient notamment la m�thode render() qui affiche un rendu � l'�cran. de mani�re g�n�rique.
namespace Controller;

// use Manager\EntityRepository; //  USE d�clenche l'autoload pour que la classe soit charg�e et ainsi �viter une erreur.
// use Manager\PDOManager; //  USE d�clenche l'autoload pour que la classe soit charg�e et ainsi �viter une erreur.

class Controller {
	protected $table;
	
    public function __construct(){} // $this->getError();
    // public function getError() {$class = 'Manager\\' . 'Error'; return new $class; }

    public function getRepository($table) // on r�cup "employe" --- m�thode qui permet d'instancier un objet EmployeRepository!!
    {
        $class = 'Repository\\' . $table . 'Repository';
        if(!isset($this->$table))
		{
            $this->table = new $class; // on instancie un objet "employe" (s'il n'existe pas) - donc la 1ere fois, oui!
        }
		
        return $this->table; // sinon on retourne l'objet d�j� instanci� auparavant!
    }

	// $layout : le design g�n�ral du site -- $template : la view qui rentre � l'int�rieur -- $parameters : les parametres disponible dans le layout et template.
    public function render($layout, $template, $parameters = array()) // permet de rendre un affichage
    {
		// echo __DIR__ ; echo __DIR__ . '/../../src/';
		// echo get_called_class(); // get_called_class() : Retourne le nom de la classe depuis laquelle une m�thode statique a �t� appel�e
        $dirViews = __DIR__ . '/../../src/' . str_replace('\\', '/', get_called_class() . '/../../Views');
		// echo $dir_views ;
        $ex = explode('\\', get_called_class()); // explode transforme la chaine en tableau d�s qu'il y a un anti slash \ et j'en met 2 sinon php croira que je veux �chaper une quote '.
		// print '<pre>'; print_r($ex); print '</pre>';
        $dirFile = str_replace('Controller', '', $ex[2]);  // on retire le mot Controller gr�ce � str_replace car dans la views il y a un dossier au nom du module "Employe" et non pas "ControllerEmploye".
		// echo $dir_file;

        $__template__ = $dirViews . '/' . $dirFile . '/' . $template; // chemin pour aller au bon endroit du template
        $__layout__ = $dirViews . '/' . $layout; // chemin pour aller au bon endroit du layout

        extract($parameters, EXTR_SKIP); //  extract permet de cr�er des variables au noms des indices (�galement pratique dans le cas $_POST). EXTR_SKIP: permet lors d'une collision, ne pas r��crire la variable existante. c'est � dire que si la variable $title avait �t� d�clar�, elle aurait pris le dessus sur l'extraction du tableau de parametres.
		// cela me rends disponible la variable $title plutot que $parameters['title'], pareil pour la variable $employes.
		
		// print '<pre>'; print_r(get_defined_vars()); print '</pre>'; // voir les variables d�finies
		
        ob_start(); // Enclenche la temporisation de sortie, c'est � dire que ce qui suit ne se produit pas tout de suite, nous retenons l'affichage pour �tre totalement MVC - ob_start enclenche la bufferisation de sortie, permet de mettre tout ton site en "tampon" avant de l'afficher gr�ce � ob_end_flush --- on veut le faire en dernier pour respecter le MVC
			require $__template__; // permet de mettre le contenu dans une variable avec la ligne du dessous, l'envoi des donn�es est retenue.
        $content = ob_get_clean(); // le template sera repr�senter par $content. cette variable est utilis� dans le layout. $content sera le require. la variable $content repr�sente le contenu du fichier indiqu� par $template

//        extract($parameters, EXTR_SKIP);
        ob_start(); // cette ligne est importante pour �viter une erreur sous mac et certainement en ligne.
			require $__layout__; // Explication : ob_start va retenir l�envoi de donn�es, et ob_end_flush les liberera en dernier.
        return ob_end_flush(); // Envoie le contenu du tampon de sortie (s'il existe) et �teint la temporisation de sortie. Si vous voulez continuer � manipuler la valeur du tampon, vous pouvez appeler ob_get_contents() avant ob_end_flush() car le contenu du tampon est d�truit apr�s un appel � ob_end_flush(). Termine la temporisation de sortie.
    }
}