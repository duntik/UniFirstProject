<?php
/*
Registration
*/

include('db/simpleDB.php');
include('layouts/HTMLcomponents.php');


if ($_POST['enter'] ) {
    $_POST['login'] = FormChars($_POST['login']);
    $_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);

    if (!$_POST['login'] or !$_POST['password']) exit('DATA ENTRED ERROR');
      if ($CONNECT){
      $Roww = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `password` FROM `users` WHERE `username` = '$_POST[login]'"));
      if ($Roww['password'] != $_POST['password']) exit('wrong login or password');
      $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `user_id`, `clubAdmin`,`nkpag`,`siteAdmin`,`username`, `blocked`  FROM `users` WHERE `username` = '$_POST[login]'"));


      if ($Roww['password'] = $_POST['password'] ){
      $_SESSION['USER_ID'] = $Row['user_id'];
      $_SESSION['USER_CLUBADMIN'] = $Row['clubAdmin'];
      $_SESSION['USER_NKPAG'] = $Row['nkpag'];
      $_SESSION['USER_SITEADMIN'] = '1';
      $_SESSION['USER_LOGIN'] = $Row['username'];
      $_SESSION['USER_LOGIN_IN'] = '1';
      $_SESSION["BLOCK"] = $Row['blocked'];
    }
      exit(header('Location: /users/'. $_SESSION['USER_LOGIN']));
  }
}
?>
