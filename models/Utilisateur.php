<?php

namespace Models;

use PDO;

class Utilisateur extends DbConnect
{

    private $idUtilisateur;
    private $pseudo;
    private $passwd;
    private $nom;
    private $prenom;
    private $mail;

    public function __construct()
    {
        parent::__construct();
        $this->idUtilisateur = -1;
        $this->pseudo = '';
        $this->passwd = '';
        $this->nom = '';
        $this->prenom = '';
        $this->mail = '';
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $name)
    {
        $this->nom = $name;
    }

    public function getPrenom(): string
    {
        return $this->nom;
    }

    public function setPrenom(string $name)
    {
        $this->prenom = $name;
    }

    public function getMail(): string
    {
        return $this->nom;
    }

    public function setMail(string $email)
    {
        $this->mail = $email;
    }

    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $id)
    {
        $this->idUtilisateur = $id;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo)
    {
        $this->pseudo = $pseudo;
    }

    public function getPasswd(): string
    {
        return $this->passwd;
    }

    public function setPasswd(string $passwd)
    {
        $this->passwd = $passwd;
    }

    public function insert()
    {
        $query = "INSERT INTO utilisateur(nom, prenom, pseudo, password, mail)
                    VALUES('$this->nom', '$this->prenom', '$this->pseudo', '$this->passwd', '$this->mail')";

        $result = $this->pdo->prepare($query);
        $result->execute();

        $this->id = $this->pdo->lastInsertId();
        return $this;
    }



    public function verify_user(): self
    {

        $query = "SELECT id_user, password FROM utilisateur WHERE pseudo = '$this->pseudo'";
        $result =  $this->pdo->prepare($query);
        $result->execute();

        $data = $result->fetch();
        if ($data) {
            $this->passwd = $data['password'];
            $this->idUtilisateur = $data['id_user'];
        }
        return $this;
    }

    function delete()
    {
        $query = "DELETE FROM utilisateur WHERE id_user = $this->idUtilisateur AND pseudo = '$this->pseudo'";

        $result = $this->pdo->prepare($query);
        $result->execute();
    }

    function update()
    {
    }

    function selectAll()
    {
    }

    function select()
    {
    }
}
