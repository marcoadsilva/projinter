<?php
    include "DataBase.php";
    $banco = new DataBase();

    $query = "INSERT INTO contatos
              (nome,telefone,email,foto)
              VALUES (
                '".$_GET["txtNome"]."',
                '".$_GET["txtTel"]."',
                '".$_GET["txtEmail"]."',
                '".$_GET["txtFoto"]."'
              )";
    $result = $banco->queryExec($query);
    header("location: contatosLista.php");
?>
