<?php
class DiplomaCategory
{
    // Connection
    private $conn;
    // Table
    private string $db_table = "diplomaCategories";
    // Columns
    private int $id;

    private string $categoryName;

    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Set all values
    public function setAll($categoryName)
    {
        $this->categoryName = $categoryName;
    }

    // Add diploma category
    public function addDiplomaCategory()
    {
        $query = "INSERT INTO " . $this->db_table . " (categoryName) VALUES (:categoryName)"; // Query to add diploma

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":categoryName", $this->categoryName);

        $stmt->execute(); // Execute query

        // check if diploma was added
        if ($stmt->rowCount() > 0) {
            $response = array(
                "status" => "success",
                "message" => "Catégorie ajoutée avec succès"
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "Erreur lors de l'ajout de la catégorie"
            );
        }

        return $response;
    }

    //modify diploma category
    public function modifyDiplomaCategory($id)
    {
        $query = "UPDATE " . $this->db_table . " 
            SET categoryName = :categoryName 
            WHERE id = :id"; // Query to modify diploma category

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":categoryName", $this->categoryName);
        $stmt->bindParam(":id", $id);

        $stmt->execute(); // Execute query

        // check if diploma category was modified
        if ($stmt->rowCount() > 0) {
            $response = array(
                "status" => "success",
                "message" => "Catégorie modifiée avec succès"
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "Erreur lors de la modification de la catégorie"
            );
        }

        return $response;
    }

    // Delete diploma category
    public function deleteDiplomaCategory($id)
    {
        // check if the diploma category is used in the diplomaTypes table
        $query = "SELECT * FROM diplomaTypes WHERE categoryId = :id"; // Query to check if the diploma category is used in the diplomaTypes table

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":id", $id);

        $stmt->execute(); // Execute query

        // check if the diploma category is used in the diplomaTypes table
        if ($stmt->rowCount() > 0) {
            $response = array(
                "status" => "error",
                "message" => "Vous ne pouvez pas supprimer une catégorie qui est utilisée"
            );

            return $response;
        }

        $query = "DELETE FROM " . $this->db_table . " WHERE id = :id"; // Query to delete diploma

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":id", $id);

        $stmt->execute(); // Execute query

        // check if diploma was deleted
        if ($stmt->rowCount() > 0) {
            $response = array(
                "status" => "success",
                "message" => "Catégorie supprimée avec succès"
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "Erreur lors de la suppression de la catégorie"
            );
        }

        return $response;
    }
}
