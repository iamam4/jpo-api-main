<?php
class Attendee
{
    // Database connection
    private $conn;

    // Table
    private string $db_table = "attendees";

    // Columns
    private int $id;

    private string $firstName;

    private string $lastName;

    private string $email;

    private int $diplomaId;

    private int $diplomaCategoryId;

    private int $isIrlAttendee;

    private string $regionalCode;

    private ?int $virtualTourSatisfaction;

    private ?int $websiteSatisfaction;

    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function setAllValues($firstName, $lastName, $email, $diplomaId, $diplomaCategoryId, $isIrlAttendee, $regionalCode, $virtualTourSatisfaction, $websiteSatisfaction)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->diplomaId = $diplomaId;
        $this->diplomaCategoryId = $diplomaCategoryId;
        $this->isIrlAttendee = $isIrlAttendee;
        $this->regionalCode = $regionalCode;

        if ($virtualTourSatisfaction !== null) {
            // If the virtualTourSatisfaction value is set then set it to an integer otherwise set it to null
            $this->virtualTourSatisfaction = $virtualTourSatisfaction;
        } else {
            $this->virtualTourSatisfaction = null;
        }

        if ($websiteSatisfaction !== null) {
            // If the websiteSatisfaction value is set then set it to an integer otherwise set it to null
            $this->websiteSatisfaction = $websiteSatisfaction;
        } else {
            $this->websiteSatisfaction = null;
        }
    }

    // Create attendee with
    public function createAttendee()
    {
        // Check if attendee already exists in the database by checking if an attendee with the same email exists
        $query = "SELECT * FROM " . $this->db_table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        // Check if a rown is returned
        if ($stmt->rowCount() > 0) {
            // Attendee already exists
            $response = array(
                'status' => 'error',
                'message' => 'Cette adresse email est déjà utilisée'
            );
            return $response; // Return error message
        } else {
            // Attendee does not exist
            // Create attendee
            $query = "INSERT INTO " . $this->db_table . " (firstName, lastName, email, diplomaId, diplomaCategoryId, isIrlAttendee, regionalCode, virtualTourSatisfaction, websiteSatisfaction) VALUES (:firstName, :lastName, :email, :diplomaId, :diplomaCategoryId, :isIrlAttendee, :regionalCode, :virtualTourSatisfaction, :websiteSatisfaction)";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(":firstName", $this->firstName);
            $stmt->bindParam(":lastName", $this->lastName);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":diplomaId", $this->diplomaId);
            $stmt->bindParam(":diplomaCategoryId", $this->diplomaCategoryId);
            $stmt->bindParam(":isIrlAttendee", $this->isIrlAttendee);
            $stmt->bindParam(":regionalCode", $this->regionalCode);
            $stmt->bindParam(":virtualTourSatisfaction", $this->virtualTourSatisfaction);
            $stmt->bindParam(":websiteSatisfaction", $this->websiteSatisfaction);

            // Execute query
            if ($stmt->execute()) {
                // Attendee created
                $response = array(
                    'status' => 'success',
                    'message' => 'Informations soumises avec succès'
                );
                return $response; // Return success message
            } else {
                // Attendee not created
                $response = array(
                    'status' => 'error',
                    'message' => 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.'
                );
                return $response; // Return error message
            }
        }
    }

    // Update attendee
    public function updateAttendee($id)
    {
        // Get original data
        $query = "SELECT * FROM " . $this->db_table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $originalData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($originalData['email'] == $this->email) { // Email is the same
            // Update attendee query
            $query = "UPDATE " . $this->db_table . " 
                SET firstName = :firstName, lastName = :lastName, diplomaId = :diplomaId, diplomaCategoryId = :diplomaCategoryId, isIrlAttendee = :isIrlAttendee, regionalCode = :regionalCode 
                WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(":id", $originalData['id']);
            $stmt->bindParam(":firstName", $this->firstName);
            $stmt->bindParam(":lastName", $this->lastName);
            $stmt->bindParam(":diplomaId", $this->diplomaId);
            $stmt->bindParam(":diplomaCategoryId", $this->diplomaCategoryId);
            $stmt->bindParam(":isIrlAttendee", $this->isIrlAttendee);
            $stmt->bindParam(":regionalCode", $this->regionalCode);

            // Execute query
            if ($stmt->execute()) {
                // Attendee updated
                $response = array(
                    'status' => 'success',
                    'message' => 'Participant modifié avec succès'
                );
                return $response; // Return success message
            } else {
                // Attendee not created
                $response = array(
                    'status' => 'error',
                    'message' => 'Une erreur est survenue lors de la modification. Veuillez réessayer.'
                );
                return $response; // Return error message
            }
        } else {
            // Email is different
            // Check if attendee already exists in the database by checking if an attendee with the same email exists
            $query = "SELECT * FROM " . $this->db_table . " WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $this->email);

            // Check if attendee already exists in the database by checking if an attendee with the same email exists
            $query = "SELECT * FROM " . $this->db_table . " WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $this->email);
            $stmt->execute();
            // Check if a rown is returned
            if ($stmt->rowCount() > 0) {
                // Attendee already exists
                $response = array(
                    'status' => 'error',
                    'message' => 'Cette adresse email est déjà utilisée'
                );
                return $response; // Return error message
            } else {
                // Attendee does not exist
                // Update attendee
                $query = "UPDATE " . $this->db_table . " 
                SET firstName = :firstName, lastName = :lastName, email = :email, diplomaId = :diplomaId, diplomaCategoryId = :diplomaCategoryId, isIrlAttendee = :isIrlAttendee, regionalCode = :regionalCode 
                WHERE id = :id";
                $stmt = $this->conn->prepare($query);

                // Bind parameters
                $stmt->bindParam(":id", $originalData['id']);
                $stmt->bindParam(":firstName", $this->firstName);
                $stmt->bindParam(":lastName", $this->lastName);
                $stmt->bindParam(":email", $this->email);
                $stmt->bindParam(":diplomaId", $this->diplomaId);
                $stmt->bindParam(":diplomaCategoryId", $this->diplomaCategoryId);
                $stmt->bindParam(":isIrlAttendee", $this->isIrlAttendee);
                $stmt->bindParam(":regionalCode", $this->regionalCode);

                // Execute query
                if ($stmt->execute()) {
                    // Attendee created
                    $response = array(
                        'status' => 'success',
                        'message' => 'Participant modifié avec succès'
                    );
                    return $response; // Return success message
                } else {
                    // Attendee not created
                    $response = array(
                        'status' => 'error',
                        'message' => 'Une erreur est survenue lors de la modification. Veuillez réessayer.'
                    );
                    return $response; // Return error message
                }
            }
        }
    }

    // Delete attendee
    public function deleteAttendee($id)
    {
        // Query to delete attendee
        $query = "DELETE FROM " . $this->db_table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":id", $id);

        // Execute query
        if ($stmt->execute()) {
            // Attendee deleted
            $response = array(
                'status' => 'success',
                'message' => 'Participant supprimé'
            );
            return $response; // Return success message
        } else {
            // Attendee not deleted
            $response = array(
                'status' => 'error',
                'message' => 'Une erreur est survenue lors de la suppression. Veuillez réessayer.'
            );
            return $response; // Return error message
        }
    }
}
