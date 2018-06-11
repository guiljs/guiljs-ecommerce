<?php

namespace hcode\model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Product extends Model
{
    public static function listAll()
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_products ORDER BY desproduct  ");
        return $results;
    }

    public static function checkList($list)
    {
        foreach ($list as &$row) {
            $p = new Product();
            $p->setData($row);
            $row = $p->getValues();
        }

        return $list;
    }

    public function save()
    {
        $sql = new Sql();

        // pidproduct int(11),
        // pdesproduct varchar(64),
        // pvlprice decimal(10,2),
        // pvlwidth decimal(10,2),
        // pvlheight decimal(10,2),
        // pvllength decimal(10,2),
        // pvlweight decimal(10,2),
        // pdesurl varchar(128)

        $results = $sql->select("CALL sp_products_save (
            :pidproduct,
            :pdesproduct,
            :pvlprice,
            :pvlwidth,
            :pvlheight,
            :pvllength,
            :pvlweight,
            :pdesurl
        )", array(
            ":pidproduct" => $this->getidproduct(),
            ':pdesproduct' => $this->getdesproduct(),
            ':pvlprice' => $this->getvlprice(),
            ':pvlwidth' => $this->getvlwidth(),
            ':pvlheight' => $this->getvlheight(),
            ':pvllength' => $this->getvllength(),
            ':pvlweight' => $this->getvlweight(),
            ':pdesurl' => $this->getdesurl(),
        ));

        $this->setData($results[0]);
    }

    public function get($idproduct)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * from tb_products where idproduct = :idproduct;", array(
            ":idproduct" => $idproduct,
        ));

        $this->setData($results[0]);
    }

    public function delete()
    {
        $sql = new Sql();
        $sql->query("delete from tb_products where idproduct = :id", array(
            ":id" => $this->getidproduct(),
        ));
    }

    public function checkPhoto()
    {
        if (file_exists(
            $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR .
            "res" . DIRECTORY_SEPARATOR .
            "site" . DIRECTORY_SEPARATOR .
            "img" . DIRECTORY_SEPARATOR .
            "products" . DIRECTORY_SEPARATOR .
            $this->getidproduct() . ".jpg")) {
            $url = "/res/site/img/products/" . $this->getidproduct() . ".jpg";
        } else {
            $url = "/res/site/img/product.jpg";
        }

        return $this->setdesphoto($url);
    }
    public function getValues()
    {
        $this->checkPhoto();

        $values = parent::getValues();

        return $values;
    }

    public function setPhoto($file)
    {
        $extension = explode('.', $file['name']);
        $extension = end($extension); //Pega a extensão.

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($file["tmp_name"]);
                break;
            case 'gif':
                $image = imagecreatefromgif($file["tmp_name"]);
                break;
            case 'png':
                $image = imagecreatefrompng($file["tmp_name"]);
                break;
        }

        $dest = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR .
        "res" . DIRECTORY_SEPARATOR .
        "site" . DIRECTORY_SEPARATOR .
        "img" . DIRECTORY_SEPARATOR .
        "products" . DIRECTORY_SEPARATOR .
        $this->getidproduct() . ".jpg";

        imagejpeg($image, $dest);

        imagedestroy($image);

        $this->checkPhoto();

    }

    public function getFromUrl($desurl)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_products WHERE desurl = :desurl LIMIT 1", [
            ':desurl' => $desurl,
        ]);

        $this->setData($results[0]);
    }
    public function getCategories()
    {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_categories a INNER JOIN tb_productscategories b on a.idcategory = b.idcategory WHERE b.idproduct = :idproduct", [
            ':idproduct' => $this->getidproduct(),
        ]);
    }
}
