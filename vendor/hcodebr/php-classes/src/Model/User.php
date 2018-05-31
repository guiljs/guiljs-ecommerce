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

    $results = $sql->select("select * from tb_users where deslogin = :login", array(":login"=>$login));

    if (count($results) === 0) {
      throw new \Exception("Usuário inexistente ou inválida.");
    }

    $data = $results[0];

    if(password_verify($password, $data["despassword"]))
    {
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
      || !(int)$_SESSION[User::SESSION]["iduser"] > 0
      || (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
    ) {
      header("Location: /admin/login"); //redirect
      exit;
    }
  }

  public static function logOut()
  {
    $_SESSION[User::SESSION]=NULL;
  }

}



  ?>
