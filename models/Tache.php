<?php

namespace Models;

use PDO;

class Tache extends DbConnect
{

    private $idTache;
    private $description;
    private $creation;
    private $deadline;
    private $idUtilisateur;

    public function __construct(?int $id = null)
    {
        parent::__construct($id);
    }

    public function getIdTache(): int
    {
        return $this->idTache;
    }

    public function setIdTache(int $id)
    {
        $this->idTache = $id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $desc)
    {
        $this->description = $desc;
    }

    public function setCreation(string $create)
    {
        $this->creation = $create;
    }

    public function getCreation(): string
    {
        return $this->creation;
    }
    public function getDeadline(): string
    {
        return $this->deadline;
    }

    public function setDeadline(string $dead)
    {
        $this->deadline = $dead;
    }

    public function getIdUtilisateur(): int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $id)
    {
        $this->idUtilisateur = $id;
    }

    public function selectByUser()
    {

        $query  = "SELECT id_tache, description, date_insertion, date_deadline, id_user FROM taches WHERE id_user = $this->idUtilisateur";
        $result = $this->pdo->prepare($query);
        $result->execute();

        $datas = $result->fetchAll();

        $tab = [];
        if ($datas) {
            foreach ($datas as $data) {
                $new = new Tache();
                $new->setId($data['id_tache']);
                $new->setDescription($data['description']);
                $new->setCreation($data['date_insertion']);
                $new->setDeadline($data['date_deadline']);

                array_push($tab, $new);
            }
        }
        var_dump($this->idUtilisateur);
        return $tab;
    }


    public function insert()
    {
        $query = "INSERT INTO taches (description, date_insertion, date_deadline, id_user)
                    VALUES (:description, NOW(), :deadline, :id_user)";

        $result = $this->pdo->prepare($query);
        $result->bindValue('description', $this->description, PDO::PARAM_STR);
        $result->bindValue('deadline', $this->deadline, PDO::PARAM_STR);
        $result->bindValue('id_user', $this->idUtilisateur, PDO::PARAM_INT);
        $result->execute();

        $this->id = $this->pdo->lastInsertId();
        return $this;
    }

    public function delete()
    {
        $query = "DELETE FROM taches WHERE id_tache = $this->id AND id_user = $this->idUtilisateur";

        $result = $this->pdo->prepare($query);
        $result->execute();
    }

    function update()
    {
        $query = "UPDATE taches SET description = '$this->description', date_deadline = '$this->deadline'
                    WHERE id_tache = $this->id AND id_user = $this->idUtilisateur";
        $result = $this->pdo->prepare($query);
        $result->execute();
    }

    function selectAll()
    {
        $query = "SELECT description, date_insertion, date_deadline, id_user FROM taches";
        $result = $this->pdo->prepare($query);
        $result->execute();
        $datas = $result->fetch();

        if($datas)
        {
            foreach ($datas as $data) {
                $new = new Tache();
                $new->setId($data['id_tache']);
                $new->setDescription($data['description']);
                $new->setCreation($data['date_insertion']);
                $new->setDeadline($data['date_deadline']);

                array_push($tab, $new);
            }
        }
        var_dump($this->idUtilisateur);
        return $tab;
    }

    function select()
    {

    }

}