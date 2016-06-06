<?php
    include "DataBase.php";
    $banco = new DataBase();

    $query = "UPDATE contatos SET
              nome='".$_GET["txtNome"]."',
              telefone='".$_GET["txtTel"]."',
              email='".$_GET["txtEmail"]."',
              foto='".$_GET["txtFoto"]."' 
              WHERE id=".$_GET["cod"];
    $result = $banco->queryExec($query);
    header("location: contatosLista.php");
?>
