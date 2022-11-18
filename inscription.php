<?php
// ######### ETAPE 1-Inclusion de init.php
require_once "inc/init.php";

//########## ETAPE 2 -Traitement des données du formulaire
// On vérifie si le formulaire a été validé. 
// S'il a été validé, on peut traiter les données.
// ATTENTION : on ne peux pas traiter les données si le formulaire n'a pas été validé

if(!empty($_POST)){
    debug($_POST);
// ETAPE DE VERIFICATION DES DONNEES
    if(empty($_POST['username'])){
        $errorMessage="Merci d'indiquer un Pseudo <br>";
    }
// 'strlen' récupère la longueur d'une chaîne de caractères. 
// Les caractères spéciaux et accents comptent pour deux espaces.
// avec 'iconv_strleng' en revanche, chaque caractère comptera pour un seul caractère 
// (les accents ne seront plus compter comme caractères).
    if(iconv_strlen(trim($_POST['username']))<3 || iconv_strlen($_POST['username'])>20){
        $errorMessage.="Le pseudo doit contenir entre 3 et 20 caractères <br>";
    }
    if(empty($_POST['password'])|| iconv_strlen(trim($_POST['password']))<8){
        $errorMessage.="Merci d'indiquer un mot de passe d'un minimum de 8 caractères<br>";
    }
    if(empty($_POST['lastname'])|| iconv_strlen(trim($_POST['lastname']))>70){
        $errorMessage.="Merci d'indiquer un nom d'un maximum de 70 caractères<br>";
    }
    if(empty($_POST['firstname'])|| iconv_strlen(trim($_POST['firstname']))>70){
        $errorMessage.="Merci d'indiquer un prénom d'un maximum de 70 caractères<br>";
    }
    if(empty($_POST['email'])|| !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $errorMessage.="Adresse e-mail invalide<br>";

    }
// ETAPE DE SECURISATION DES DONNEES
//mauvaise méthode , car il faudrait repeter la manoeuve pour autant de champ de forulaire:
//$_POST['username']=htmlspecialchars($_POST['username']);

//Bonne méthode, une boucle FOREACH :
foreach($_POST as $key=> $value){
    $_POST[$key]= htmlspecialchars($value,ENT_QUOTES);
}

// FIN DE SECURISATION DES DONNEES

// FIN DE VERIFICATION DES DONNEES
// ETAPE ENVOI DES DONNEES

// Cette doncition vérifie si il n'y a pas de message d'erreur
// Si c'et le cas, les données inscrites par l'utilisateur sont correctes 
// et peuvent être envoyées en base de données 
    if(empty($errorMessage)){
        $requete =$bdd->prepare("INSERT INTO membre VALUES (NULL, :username, :password, 
    :lastname, :firstname, :email, :status)");
    $success=$requete->execute([
        ":username" => $_POST['username'],
        ":password" => password_hash($_POST['password'], PASSWORD_DEFAULT), 
        //la fonction password_hash masque dans la bdd le mot de passe enregistré par l'utilisateur.
        //On doit lui indiquer en paramètre le type d'algorithme.
        ":lastname" => $_POST['lastname'],
        ":firstname" => $_POST['firstname'],
        ":email" => $_POST['email'],
        ":status"=> "user"
    ]);

    if ($success){
        $successMessage="vous êtes enregistré";
        // SI la requête a fonctionné, l'utilisateur est redirigé vers la page de connexion
        header("location:connexion.php");
        exit;
    }else {
        $errorMessage ="Erreur lors de l'enregistrement";
    }

    }

// FIN envoi des données
}

require_once "inc/header.php";
?>
<h1 class="text-center">Inscription</h1>

<?php if( !empty($successMessage) ){ ?>
            <div class="alert alert-success col-md-6 mx-auto text-center">
                <?php echo $successMessage; ?>
            </div>
        <?php } ?>

        <?php if( !empty($errorMessage) ){ ?>
            <div class="alert alert-danger col-md-6 mx-auto text-center">
                <?php echo $errorMessage; ?>
            </div>
        <?php } ?>

<form action="" method="post" class="col-md-6 mx-auto">

    <label for="username" class="form-label">Pseudo</label>
    <input 
        type="text" 
        name="username" 
        id="username" 
        class="form-control"
        value="<?php echo $_POST ['username'] ??"" ?>">
        <!-- Si $_POST['username'] existe alors j'affiche sa valeur 
        SINON j'affiche une chaîne de caratère vide.
        On utilise ici l'opérateur NULL COALESCENT. -->
    <div class="invalid-feedback"></div>

    <label for="password" class="form-label">Mot de Passe</label>
    <input 
        type="password" 
        name="password" 
        id="password" 
        class="form-control">

    <div class="invalid-feedback"></div>

    <label for="lastname" class="form-label">Nom</label>
    <input 
        type="text" 
        name="lastname" 
        id="lastname" 
        class="form-control"
        value="<?= $_POST ['lastname'] ??"" ?>">
        
    <div class="invalid-feedback"></div>

    <label for="firstname" class="form-label">Prénom</label>
    <input 
        type="text" 
        name="firstname" 
        id="firstname" 
        class="form-control"
        value="<?= $_POST ['firstname'] ??"" ?>">
    <div class="invalid-feedback"></div>

    <label for="email" class="form-label">Email</label>
    <input 
        type="email" 
        name="email" 
        id="email" 
        class="form-control"
        value="<?= $_POST ['email'] ??"" ?>">
    <div class="invalid-feedback"></div>

    <button class="btn btn-success d-block mx-auto mt-3">S'inscrire</button>

</form>

<?php
require_once "inc/footer.php";
