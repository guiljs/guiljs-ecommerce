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

        Category::updateFile();
    }

    public function get($idcategory)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * from tb_categories where idcategory = :idcategory;", array(
            ":idcategory" => $idcategory,
        ));

        $this->setData($results[0]);
    }

    public function delete()
    {
        $sql = new Sql();
        $sql->query("delete from tb_categories where idcategory = :id", array(
            ":id" => $this->getidcategory(),
        ));

        Category::updateFile();
    }

    public static function updateFile()
    {
        // $category = new Category();
        // $results = $category->listAll();
        $html =[];

        $categories = Category::listAll();

        foreach ($categories as $row) {
            # code...

            //<li><a href="#">Categoria Um</a></li>

            array_push($html, '<li><a href="/categories/'.$row["idcategory"].'">'.$row["descategory"].'</a></li>');
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'categories-menu.html', implode('', $html));
    }
}
