<?php

class Database {

    private $dbHost = 'localhost';
    private $dbName = 'doctorsdatabase';
    private $dbUser = 'r1q31a0l21p2';
    private $dbPass = 'Bellaria79@@';
    private $db;

    // Make DB connect and check if it's working fine
    public function __construct()
    {
        try {
            
            $this->db = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName;charset=UTF8", $this->dbUser, $this->dbPass);

        } catch(PDOException $ex) {
            die(json_encode(array('outcome' => false, 'message' => 'Unable to connect' . $ex->getMessage())));
        }
    }
    
    public function checkIfEmailExist($email)
    {
        try {
			$db = $this->db;
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM User WHERE email LIKE '$email'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                session_start();
                $_SESSION['payload'] = json_encode($user);

                return false;
            }

            return true;

		} catch(PDOException $e) {
			return "PDO Error: " . $e->getMessage();
		} catch(Exception $e) {
			return "Error: " . $e->getMessage();
		}
    }

    public function getOrginizations()
    {
        try {
			$db = $this->db;
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM Organization";
			$stmt = $db->prepare($sql);
			$stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			return "PDO Error: " . $e->getMessage();
		} catch(Exception $e) {
			return "Error: " . $e->getMessage();
		}
    }
    
    public function getRoles()
    {
        try {
			$db = $this->db;
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM `Role`";
			$stmt = $db->prepare($sql);
			$stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			return "PDO Error: " . $e->getMessage();
		} catch(Exception $e) {
			return "Error: " . $e->getMessage();
		}
    }

    public function getLastUserDetail()
    {
        try {
			$db = $this->db;
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM `User` ORDER BY `userID` DESC LIMIT 1";
			$stmt = $db->prepare($sql);
			$stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			return "PDO Error: " . $e->getMessage();
		} catch(Exception $e) {
			return "Error: " . $e->getMessage();
		}
    }

    public function fetchOrgnization($id)
    {
        try {
			$db = $this->db;
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT `name` FROM `Organization` WHERE organizationID = $id";
			$stmt = $db->prepare($sql);
			$stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data['name'];
		} catch(PDOException $e) {
			return "PDO Error: " . $e->getMessage();
		} catch(Exception $e) {
			return "Error: " . $e->getMessage();
		}
    }

    public function getFilesData()
    {
        try {
			$db = $this->db;
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM `Test_Session`";
			$stmt = $db->prepare($sql);
			$stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			return "PDO Error: " . $e->getMessage();
		} catch(Exception $e) {
			return "Error: " . $e->getMessage();
		}
    }
    
    public function fetchRole($id)
    {
        try {
			$db = $this->db;
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT `name` FROM `Role` WHERE roleID = $id";
			$stmt = $db->prepare($sql);
			$stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data['name'];
		} catch(PDOException $e) {
			return "PDO Error: " . $e->getMessage();
		} catch(Exception $e) {
			return "Error: " . $e->getMessage();
		}
    }

    public function createUser($auth, $name, $username, $email, $roleId, $orgnizationId)
    {
        try {
			$db = $this->db;
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT userID FROM `User` WHERE `username` = '$username' OR `email` = '$email'";
			$stmt = $db->prepare($sql);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                $sql = "INSERT INTO User(`auth`, `name`, `username`, `email`, `Role_IDrole`, `Organization`) VALUES('$auth', '$name', '$username', '$email', $roleId, $orgnizationId);";
                $stmt = $db->prepare($sql);
                $stmt->execute();

                return true;
            }
            
            return false;
		} catch(PDOException $e) {
			return "PDO Error: " . $e->getMessage();
		} catch(Exception $e) {
			return "Error: " . $e->getMessage();
		}
    }
}
