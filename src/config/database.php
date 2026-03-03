<?php

class Database{
    public static function getConnection(): PDO {
        /** 
         * This file is responsible for establishing a connection to the database using PDO (PHP Data Objects).
         * It retrieves the database connection parameters from environment variables defined in a .env file.
         * The connection is established within a try-catch block to handle any potential connection errors.
         */
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
           $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') === false) {
                   [$key, $value] = explode('=', $line, 2);
                   $_ENV[trim($key)] = trim($value);
               }
           }
        }

        /** 
         * We retrieve the database connection parameters from the environment variables for security reasons, 
         * so that they are not hardcoded in the code and can be easily changed without modifying the code.
         */
        $localhost = $_ENV['DB_HOST'];
        $db_name = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        try{
            $connexion = new PDO ("mysql:host=$localhost;dbname=$db_name;",$username,$password) or die ();
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Connection successful - no need to echo in production
            return $connexion;
        }catch(PDOException $connexionError) {
            echo "Connexion error : " . $connexionError->getMessage();
            die();
        }
    }
}

