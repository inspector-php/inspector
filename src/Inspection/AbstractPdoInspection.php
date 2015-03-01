<?php

namespace Inspector\Inspection;

use PDO;
use Inspector\Inspection\InspectionInterface;

abstract class AbstractPdoInspection
{
    protected $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * A utility function
     */
    protected function queryRows($sql, $args = null)
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($args);
        return $statement->fetchAll();
    }

    protected function getDoubleValues($tablename, $columnname, $keyname)
    {
        $sql = "SELECT " . $columnname . ", count(id) as c 
            FROM " . $tablename . "
            WHERE isnull(r_d_s) AND " . $columnname . "!='' AND !isnull(" . $columnname . ")
            GROUP BY " . $columnname . "
            HAVING c>1
            ORDER BY c";
        $rows = $this->queryRows(
            $sql,
            array(
                "columnname" => $columnname
            )
        );

        $doubles = array();
        foreach ($rows as $row) {
            $value = $row[$columnname];
            $sql = "SELECT " . $keyname . " FROM " . $tablename . " WHERE " . $columnname . " =:value";
            
            $krows = $this->queryRows(
                $sql,
                array('value' => $value)
            );

            $keys = array();
            foreach ($krows as $krow) {
                $keys[$krow[$keyname]] = $value;
            }
            $double = new DoubleValue($value, $keys);
            $doubles[] = $double;
        }
        return $doubles;
    }
}
