<?php

namespace hcode\model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model
{
    const SESSION = "User";
    public static function login($login, $password)
    {
        $sql = new Sql();

        $results = $sql->select("select * from tb_users where deslogin = :login", array(":login" => $login));

        if (count($results) === 0) {
            throw new \Exception("Usuário inexistente ou inválida.");
        }

        $data = $results[0];

        if (password_verify($password, $data["despassword"])) {
            $user = new User();

            $user->setdata($data);

            $_SESSION[User::SESSION] = $user->getValues();

            return $user;

        } else {
            throw new \Exception("Usuário inexistente ou inválida.");
        }
    }

    public static function verifyLogin($inadmin = true)
    {
        if (
            !isset($_SESSION[User::SESSION])
            || !$_SESSION[User::SESSION]
            || !(int) $_SESSION[User::SESSION]["iduser"] > 0
            || (bool) $_SESSION[User::SESSION]["inadmin"] !== $inadmin
        ) {
            header("Location: /admin/login"); //redirect
            exit;
        }
    }

    public static function logOut()
    {
        $_SESSION[User::SESSION] = null;
    }

    public static function listAll()
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
        return $results;
    }

    public function save()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            "desperson" => $this->getdesperson(),
            "deslogin" => $this->getdeslogin(),
            "despassword" => $this->getdespassword(),
            "desemail" => $this->getdesemail(),
            "nrphone" => $this->getnrphone(),
            "inadmin" => $this->getinadmin(),
        ));

        $this->setData($results[0]);
    }

    public function get($iduser)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * from tb_users a INNER JOIN tb_persons b USING (idperson) WHERE a.iduser = :ID", array(
            ":ID" => $iduser,
        ));

        $this->setData($results[0]);
    }

    public function update()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            "iduser" => $this->getiduser(),
            "desperson" => $this->getdesperson(),
            "deslogin" => $this->getdeslogin(),
            "despassword" => $this->getdespassword(),
            "desemail" => $this->getdesemail(),
            "nrphone" => $this->getnrphone(),
            "inadmin" => $this->getinadmin(),
        ));

        $this->setData($results[0]);
    }

    public function delete()
    {
        $sql = new Sql();
        $sql->query("CALL sp_users_delete(:iduser)", array(
            ":iduser" => $this->getiduser()
        ));
    }
}
