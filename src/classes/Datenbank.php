<?php

namespace Classes;

use mysqli;
require_once dirname(__FILE__, 2) . '/config/config.php';

class Datenbank
{
    protected $connection;
    protected $query;

    # DB-zugangsdaten können beim Erstellen des Obj überschrieben werden.
    public function __construct($dbhost = DB_HOST, $dbuser = DB_USER, $dbpasswd = DB_PASSWORD, $dbname = DB_NAME, $charset = 'utf8')
    {
        $this->connection = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
        if ($this->connection->connect_error) {
            die('Failed to connect to MySQL - ' . $this->connection->connect_error);
        }
        $this->connection->set_charset($charset);
    }


    # func. zum Ausführen einer gegebenen SQL-Abfrage ggf. mit args.
    public function execute_query($query)
    {
        $this->query = $this->connection->prepare($query);
        if ($this->query) {
            if (func_num_args() > 1) {          # z.b. bei Insert, select/update(with parameter)
                $x = func_get_args();
                $args = array_slice($x, 1);
                $types = '';
                $query_args = array();          # parameter für die sql-statement
                foreach ($args as $key => &$arg) {
                    $types .= $this->_gettype($args[$key]);
                    $query_args[] = &$arg;
                }
                array_unshift($query_args, $types);
                call_user_func_array(array($this->query, 'bind_param'), $query_args);
                #   $this->query->execute();
            }
            if (!$this->query->execute()) {
                echo ('Unable to process MySQL query - ' . $this->query->error);
            }
        } else {
            echo ('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
        }
        return $this;
    }


    public function fetchAll($callback = null)
    {
        $params = array();
        $col_names = array();                          # spaltennamen in der Tabelle
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {       # fetch meta data for all fields to get the name (spaltenname).
            $params[] = &$col_names[$field->name];    # $params refers to $col_names-array
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);   #$params passed as a Reference
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($col_names as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break') break;
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        return $result;
    }


    public function fetchArray()
    {
        $params = array();
        $col_names = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$col_names[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            foreach ($col_names as $key => $val) {
                $result[$key] = $val;
            }
        }
        $this->query->close();
        return $result;
    }


    public function close()
    {
        return $this->connection->close();
    }

    public function numRows()
    {
        $this->query->store_result();
        return $this->query->num_rows;
    }

    public function affectedRows()
    {
        return $this->query->affected_rows;
    }

    private function _gettype($var)
    {
        if (is_string($var)) return 's';
        if (is_float($var)) return 'd';
        if (is_double($var)) return 'd';
        if (is_int($var)) return 'i';
        if (is_bool($var)) return 'b';
    }

    public function get_conn()
    {
        return $this->connection;
    }

    public function get_query()
    {
        return $this->query;
    }
}



/*
Beispiel:
###################################################################
$mitarbeiter = $db->execute_query("SELECT  vertragsart FROM mitarbeiter WHERE vorname=? AND name=?", $vorname, $nachname)->fetchArray();
echo "<br>". $mitarbeiter["vertragsart"]. "<br>";
 
$mitarbeiter = $db->execute_query('SELECT * FROM mitarbeiter')->fetchAll();
foreach ($mitarbeiter as $i) {
	echo $i['vorname'] . $i['name'] . '<br>';
    print_r($i );  # as array
    echo '<br>';
}

$mitarbeiter = $db->execute_query('SELECT * FROM mitarbeiter');
echo "numRows: ".$mitarbeiter->numRows();


$insert = $db->execute_query('INSERT INTO mitarbeiter (account,vorname,name) VALUES (?,?,?)', 'test', 'test1', 'test2');
echo "affectedRows: ".$insert->affectedRows();

$db->execute_query( "UPDATE `mitarbeiter` SET `vorname` = 'test', `name` = 'test' WHERE `mitarbeiter`.`account` = 'test' ");


*/