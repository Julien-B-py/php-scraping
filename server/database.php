<?php

class DB
{
  // LOCAL DB
  private $servername = "localhost";
  private $username = "root";
  private $password = "";
  private $dbName;
  private $tableName;

  private $conn;

  public function createDatabaseIfNotExists($dbName)
  {

    // Create connection
    $this->conn = new mysqli($this->servername, $this->username, $this->password);


    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
    if ($this->conn->query($sql) === TRUE) {
      // echo "Database $dbName created successfully <br>";
      $this->dbName = $dbName;
    } else {
      // echo "Error creating database: " . $this->conn->error;
    }
  }


  public function createTableIfNotExists($tableName)
  {



    // Create connection
    $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbName);
    // Check connection
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }

    // Create table
    $sql = "CREATE TABLE IF NOT EXISTS $tableName (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  model VARCHAR(128) NOT NULL,
  version VARCHAR(128) NOT NULL,
  price INT(10) NOT NULL,
  good_deal VARCHAR(64),
  location VARCHAR(128),
  city VARCHAR(128),
  km INT(10) NOT NULL,
  year INT(4) NOT NULL,
  url VARCHAR(128) NOT NULL
  )";


    if ($this->conn->query($sql) === TRUE) {
      // echo "Table $tableName created successfully <br>";
      $this->tableName = $tableName;
    } else {
      // echo "Error creating table: " . $this->conn->error;
    }
  }





  public function createEntry($model, $version, $price, $goodDeal, $location, $city, $km, $year, $url)
  {

    $sql = "INSERT INTO $this->tableName (model, version, price, good_deal, location, city, km, year, url)
  VALUES (?,?,?,?,?,?,?,?,?)";

    if ($stmt = $this->conn->prepare($sql)) {

      // Binds variables to the prepared statement as parameters
      $stmt->bind_param("ssisssiis", $model, $version, $price, $goodDeal, $location, $city, $km, $year, $url);

      // Executes the prepared statement
      $stmt->execute();


      // Closes the prepared statement
      $stmt->close();
    }
  }



  public function entryExists($url)
  {
    $sql = "SELECT * FROM $this->tableName WHERE url = ? ;";

    if ($stmt = $this->conn->prepare($sql)) {

      // Binds variables to the prepared statement as parameters
      $stmt->bind_param("s", $url);

      // Executes the prepared statement
      $stmt->execute();

      $result = $stmt->get_result();

      // Frees stored result memory for the given statement handle
      $stmt->free_result();
      // Closes the prepared statement
      $stmt->close();

      // If exists
      if ($result->fetch_assoc()) {
        return true;
      }

      // If doesnt exists
      return false;
    }
  }
}
