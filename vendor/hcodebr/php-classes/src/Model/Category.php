<?php

namespace hcode\model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Category extends Model
{
    public static function listAll()
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
        return $results;
    }

    public function save()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_categories_save (:id, :descategory)", array(
            ":descategory" => $this->getdescategory(),
            ":id" => $this->getidcategory()
        ));

        // $sql->query("INSERT INTO tb_categories  (descategory, dtregister) VALUES(:descategory,now())", array(
        //     ":descategory" => $this->getdescategory(),
        // ));

        // $results = $sql->select("SELECT * from tb_categories where idcategory = LAST_INSERT_ID();");

        $this->setData($results[0]);
    }

    public function get($idcategory)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * from tb_categories where idcategory = :idcategory;", array(
            ":idcategory" => $idcategory,
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
        $sql->query("delete from tb_categories where idcategory = :id", array(
            ":id" => $this->getidcategory(),
        ));
    }
}
