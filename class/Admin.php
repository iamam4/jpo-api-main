<?php
class Admin
{
    // Connection
    private $conn;
    // Table
    private string $db_table = "admins";
    // Columns
    private $id;

    private string $displayName;

    private string $login;

    private string $password;

    // Constructor
    public function __construct($db, $login, $password)
    {
        $this->conn = $db;
        $this->login = $login;
        $this->password = $password;
    }

    // Login method
    public function login()
    {

        $query = "SELECT * FROM " . $this->db_table . " WHERE login = :login"; // Query to get admin with the given login

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":login", $this->login);
        $stmt->execute();

        // Check if a rown is returned
        if ($stmt->rowCount() > 0) {

            // Get user details
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check password
            if (password_verify($this->password, $row["password"])) {

                // Create session token 
                $token = bin2hex(random_bytes(32));

                // Insert token into database
                $query = "INSERT INTO sessions (adminId, token) VALUES (:adminId, :token)";
                $stmt = $this->conn->prepare($query);

                // Bind parameters
                $stmt->bindParam(":adminId", $row["id"]);
                $stmt->bindParam(":token", $token);

                // Execute query
                $stmt->execute();

                $userPreferencesQuery = "SELECT * FROM userPreferences WHERE adminId = :adminId"; // Query to get the user preferences
                $userPreferencesStmt = $this->conn->prepare($userPreferencesQuery);
                $userPreferencesStmt->bindParam(":adminId", $row["id"]);
                $userPreferencesStmt->execute();
                $row["userPreferences"] = $userPreferencesStmt->fetch(PDO::FETCH_ASSOC); // Get the user preferences

                // Go through each user preference except the first one and set the value to true if it is 1 and false if it is 0
                foreach ($row["userPreferences"] as $key => $value) {
                    if ($key != "adminId") { // Check if the key is not id or adminId
                        if ($key == 'defaultTheme') { // Check if the key is defaultTheme
                            $row["userPreferences"][$key] = $value; // Set the value to the value in the database
                        } else {
                            if ($value == 1) {
                                $row["userPreferences"][$key] = true; // Set the value to true
                            } else {
                                $row["userPreferences"][$key] = false; // Set the value to false
                            }
                        }
                    }
                }

                // Store user details in response
                $response = array(
                    'status' => 'success',
                    'message' => 'Connexion réussie',
                    'displayName' => $row["displayName"],
                    'token' => $token,
                    'userPreferences' => $row["userPreferences"]
                );

                return $response; // Return response
            } else {
                // Password is incorrect
                $response = array(
                    'status' => 'error',
                    'message' => 'Identifiants incorrects'
                );
                return $response; // Return response
            }
        } else {
            // Admin does not exist
            $response = array(
                'status' => 'error',
                'message' => 'Identifiants incorrects'
            );
            return $response; // Return response
        }
    }

    // logout
    public function logout()
    {
        // Get the token from headers
        foreach (getallheaders() as $name => $value) {
            if ($name == "Authorization") {
                $token = $value;
            }
        }

        // delete token from database
        $query = "DELETE FROM sessions WHERE token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();

        // send response
        $response = array(
            'status' => 'success',
            'message' => 'Déconnexion réussie'
        );

        return $response;
    }
}
