<?php
require_once "../model/database.php";
require_once "../model/userModel.php";

class UserManager {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->connectDB();
        $this->userModel = new UserModel($db);
    }

    public function registerFunc($firstName, $lastName, $email, $password, $confirmPassword, $role) {
        if ($password !== $confirmPassword) {
            echo "Passwords do not match.";
            return;
        }
        
        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

        if ($this->userModel->registerUser($firstName, $lastName, $email, $hashedPassword, $role)) {
            echo "Success!"; 
        } else {
            echo "Email is already registered or an error occurred.";
        }
    }

    public function loginFunc($email, $password) {
        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION["user_firstName"] = $user['firstName'];
            $_SESSION["user_lastName"]  = $user['lastName'];
            $_SESSION["user_email"]     = $user['email'];
            $_SESSION["user_role"]      = $user['role']; 
            echo "Success!"; 
        } else {
            echo "Invalid email or password.";
        }
    }

    public function getAllUsersFunc() {
   
        $users = $this->userModel->getAllUsers();
        
        //puts data to string then echo
        echo json_encode($users);
    }

    public function deleteUserFunc($id) {
        try{
        if ($this->userModel->deleteUser($id)) {
            echo "User has been deleted.";
        } else {
            echo "An error occured.";
        }
            }
            catch(PDOException $ex){
                http_response_code(501);
                    echo $ex ->getMessage();
                    exit;
            }
    }

    public function updateUserFunc($id, $firstName, $lastName, $email) {
        try{
        if ($this->userModel->updateUser($id, $firstName, $lastName, $email)) {
            echo "Success!";
        } else {
            echo "An error occured.";
        }
    }catch(PDOException $ex){
        http_response_code(501);
                    echo $ex ->getMessage();
                    exit;
    }
}
}