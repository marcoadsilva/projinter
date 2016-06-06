<?php
    include "DataBase.php";
    $banco = new DataBase();

    $query = "DELETE FROM contatos WHERE id=".$_GET["cod"];
    $result = $banco->queryExec($query);
    header("location: contatosLista.php");
?>
