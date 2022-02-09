<?php

class User
{
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;

    protected $bdd;

    //Constructeur sans paramètre

    public function __construct()
    {
        $this->bdd = mysqli_connect('localhost', 'root', '', 'classes');
        mysqli_set_charset($this->bdd, 'UTF8');

        return $this->bdd;
    }
    // Créée l’utilisateur en bdd et retourne un tableau des info de ce même utilisateur

    public function register($login, $password, $email, $firstname, $lastname)
    {

        $sqlVerif = "SELECT * FROM utilisateurs WHERE login = '$login'";
        $select = mysqli_query($this->bdd, $sqlVerif);

        if (mysqli_num_rows($select)) {
            return "Ce login existe déjà, choisissez en un autre";
        } else {

            $sql = "INSERT INTO `utilisateurs`(`login`, `password`, `email`, `firstname`, `lastname`) VALUES ('$login','$password','$email','$firstname','$lastname')";
            $requete = mysqli_query($this->bdd, $sql);
            return '
                    <table>
                        <thead>
                            <th>Login</th>
                            <th>Password</th>
                            <th>Email</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>' . $login . '</td>
                                <td>' . $password . '</td>
                                <td>' . $email . '</td>
                                <td>' . $firstname . '</td>
                                <td>' . $lastname . '</td>
                            </tr>
                        </tbody>
                    </table>';
        }
    }

    // Connecte l’utilisateur 

    public function connect($login, $password)
    {

    //donne aux attributs de la classe les valeurs de l’utilisateur connecté

        $bdd = mysqli_connect('localhost', 'root', '', 'classes');
        $stmt = mysqli_query($bdd, "SELECT * FROM utilisateurs WHERE login = '$login' AND password = '$password'");
        $count = mysqli_num_rows($stmt);
        $req = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
        

        if($count==1){
            foreach($req as $value){
            $id = $value['id'];
            $login = $value['login'];
            $email = $value['email'];
            $firstname = $value['firstname'];
            $lastname = $value['lastname'];
            
            $this->id = $id;
            $this->login = $login;
            $this->email = $email;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            
            session_start();
            $_SESSION['login'] = $login;
            $_SESSION['connect_user'] = $id;
            echo 'Connecter';
            }
        }
        else{
            echo 'Introuvable';
        }   

}
    // Déconnection 
    public function disconnect()
{
    session_destroy();
    echo 'Déconnecter';
}
    // Supprime ET déconnecte un utilisateur
    public function delete($login)
{
    $bdd = mysqli_connect('localhost', 'root', '', 'classes');
    mysqli_query($bdd, "DELETE FROM `utilisateurs` WHERE `login`='$login'");
    echo 'Supprimer';
} 
    
    // Mise a jour des attributs de l’objet, modifie informations en BDD

    public function update($login, $password, $email, $firstname, $lastname)
{
    $user = $_SESSION['connect_user'];
    $bdd = mysqli_connect('localhost', 'root', '', 'classes');
    mysqli_query($bdd, "UPDATE utilisateurs SET login='$login', password ='$password',  email ='$email', firstname ='$firstname', lastname ='$lastname' WHERE id = '$user'");
    echo 'Modifier';
}

    // Savoir si un utilisateur est connecté

    public function isConnected()
    {
        if (!empty($_SESSION['connect_user'])) {
            return true;
        } else {
            return false;
        }
    }

    // Tableau contenant l’ensemble des informations de l’utilisateur

    public function getAllInfo()
{

    $user = $_SESSION['connect_user'];
    $bdd = mysqli_connect('localhost', 'root', '', 'classes');
    $stmt = mysqli_query($bdd, "SELECT * FROM utilisateurs WHERE id = '$user'");
    $req = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
    foreach($req as $value){
        $login = $value['login'];
        $password = $value['password'];
        $email = $value['email'];
        $firstname = $value['firstname'];
        $lastname = $value['lastname'];

    return array($login, $password, $email, $firstname, $lastname);
}

}

    // Retourne Login , email,prenom, nom
    public function getLogin(){
    return $this->login;
 }
    
    public function getEmail(){
     return $this->email;
  }
    
    public function getFirstname(){
     return $this->firstname;
  }
    
    public function getLastname(){
     return $this->lastname;
     
  }

}
?>