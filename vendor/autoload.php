<?php

class Autoload
{
	public static $nb = 0; // permet de compter le nombre de fois que l'on passe ici
    public static function className($className)
    {
		//echo '<pre>' . self::$nb . ' - Autoload : ' . $className;
        $tab = explode('\\', $className); // explode permet de prendre la chaine (string) et de la transformer en tableau ARRAY. on cherche le caract�re \ mais si on en met qu'un seul c'est comme si on voulais �chaper la quote (') en php, alors dans ce cas pr�cis il faut mettre 2 anti slash \\.
		// print '<pre>'; print_r($tab); print '</pre>';
		// if(in_array(end($tab), get_declared_classes())){ echo '<h1>' . end($tab) . '</h1>'; $path = " "; }
		
        if($tab[0] == 'Backoffice') // l'explode nous permet de savoir si l'on doit reculer d'un dossier pour aller cherche un bundle (c'est � dire un module sp�cifique)
            $path = __DIR__ . '/../src/' . implode('/', $tab) . '.php'; // on remet chaque �l�ment du tableau avec un /
        else // sinon on repart forc�ment de la racine
			$path = __DIR__ . '/' . implode('/', $tab) . '.php'; // s'il y a le namespace backoffice je suis dans le if et je vais vers src, sinon je reste dans le else et dans le dossier vendor.
		//echo "\n    => $path</pre><hr />";   // permet de voir les classes instanci� gr�ce � l'autoload
		
		require_once $path;
			
		self::$nb++;
    }
}

spl_autoload_register(array('Autoload', 'className')); // Lorsque l'on utilise l'autoload sur une classe, il faut passer un array et la m�thode doit �tre static.

/*
	Permet de faire le test :
	$u = new Application\User
	nous allons recevoir Application\User que nous transformons en tableau array comme ceci :
	array{
	0 => Application
	1 => User
	}
	Nous testons l'indice 0 pour voir s'il s'agit de backoffice ou d'un autre namespace.
	Nous garderons toujours le format nomdunamespace\nomdelaclasse.
	Le namespace d�clar� sera toujours le m�me que le nom du dossier, cela permet � l'autoload de s'orienter vers les diff�rents endroits.
*/