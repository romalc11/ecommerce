<?php

namespace Hcode\DB;

class Sql
{

    const HOSTNAME = "127.0.0.1";
    const USERNAME = "root";
    const PASSWORD = "";
    const DBNAME = "db_ecommerce";

    public static function getConnection()
    {
        return new \PDO(
            "mysql:dbname=" . Sql::DBNAME . ";host=" . Sql::HOSTNAME,
            Sql::USERNAME,
            Sql::PASSWORD


        );
    }

}

?>