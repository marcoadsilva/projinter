<?php

    class DataBase
    {
        private $handle;
        private $hasRows;

        function __construct()
        {
            try
            {
                $this->handle = new PDO('sqlite:'.dirname(__FILE__).'/banco.db');
                $this->handle->exec( 'PRAGMA foreign_keys = ON;' );
                $this->hasRows = false;
            }
            catch(Exception $e)
            {
                die($e);
            }
        }

        function __destruct() { }

        function hasRows() { return $this->hasRows; }

        function query($query)
        {
            try
            {
                $result = $this->handle->query($query);
                $this->hasRows = false;
                //if($result->fetchArray()) {
                //foreach($result as $row) {
                //    $this->hasRows = true;
                //}
                if($result->fetch()) {
                   $this->hasRows = true;
                }
                //$result->reset();
                $result = $this->handle->query($query);
                return $result;
            }
            catch(Exception $e)
            {
                die($e);
            }
        }

        function queryExec($query)
        {
            try
            {
                //print $this->handle;
                $result = $this->handle->query($query);
                return $result;
            }
            catch(Exception $e)
            {
                die($e);
            }
        }

        function lastAutoInc()
        {
            try
            {
                //print $this->handle;
                //$result = $this->handle->lastInsertRowid();
                $result = $this->handle->lastInsertId();
                return $result;
            }
            catch(Exception $e)
            {
                die($e);
            }
        }

	function printLastError() {
		print_r($this->handle->errorInfo());
	}

    }

    function echox($texto) {
        echo utf8_decode($texto);
    }

?>
