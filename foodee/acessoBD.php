<?php

  include("../banco/DataBase.php");

  $banco = new DataBase();
  $result = $banco->query("select * from usuarios");
  while($rec = $result->fetch())
  {

  }

?>
