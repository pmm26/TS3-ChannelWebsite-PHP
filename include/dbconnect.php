<?php 
    require_once __DIR__ . '/config.php';

    function mysqlConnection() {
        global $config;
        $host   = $config['database']['host'];
        $user  = $config['database']['username'];
        $pass = $config['database']['password'];
        $db  = $config['database']['database'];
        try {
           return new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                
        } 
        catch (PDOException $e) {
            // The PDO constructor throws an exception if it fails
            die('Error Connecting to Database: ' . $e->getMessage());
        }
    }   



    function dbquery($dbhandle, $sql, $params) {
          
        $query = $dbhandle->prepare($sql);

        if ( $query->execute($params) === FALSE ) {
        die('Error Running Query: ' . implode($query->errorInfo(),' ')); 
        } else {
            echo "Sucessfull! <br> Query: " . $sql . "<br> <br>" ;
        }

        // Put the results into a nice big associative array
        return $query->fetchAll();
    }

    /**
 * [createDatabaseConnection connect to tabase]
 * @return [Connection]
 */
    function createDatabaseConnection() {
        global $config;
        $host   = $config['database']['host'];
        $login  = $config['database']['username'];
        $passwd = $config['database']['password'];
        $database  = $config['database']['database'];
    
        //Create a new MySQL Connection
        $conn = new mysqli($host, $login, $passwd, $database);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
  
  
  
  /**
   * [simpleQuery - Queries Database]
   * @param  $conn  [Connection to the Database]
   * @param  $query [SQL Querry]
   * @return $data  [Output of the Querry ]
   */
  
  //TODO Remove Query when finished
  
  function simpleQuery($conn, $query) {
    if (! $data = $conn->query($query)) {
        echo "Error: SQL! <br> Query: " . $query . "<br> Error: " . $conn->error . "<br> <br>" ;
    } else {
        echo "Sucessfull! <br> Query: " . $query . "<br> <br>" ;
    }
    return $data;
  }
  
?>
