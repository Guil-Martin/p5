<?php

class UserManager extends Model
{

    /////// REGISTER / LOGIN / LOGOUT
    public function registerUser($user)
    { // Register user in the member database
        $sql = "INSERT INTO " . DB_USERS . "
        (contentId, userName, avatar, bio, userPassword, userMail, dateRegistered)
        VALUES
        (:contentId, :userName, :avatar, :bio, :userPassword, :userMail, NOW())";

        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':contentId', $user->getContentId(), PDO::PARAM_STR);
        $req->bindValue(':userName', $user->getUserName(), PDO::PARAM_STR);
        $req->bindValue(':avatar', $user->getAvatar(), PDO::PARAM_STR);
        $req->bindValue(':bio', '', PDO::PARAM_STR);
        $req->bindValue(':userMail', $user->getUserMail(), PDO::PARAM_STR);
        $req->bindValue(':userPassword', $user->getUserPassword(), PDO::PARAM_STR);

        $req->execute();
        //var_dump($req->errorInfo());

        return $req->rowCount() > 0;
    }

    public function updateUser($user)
    { // Register user in the member database

        //var_dump($user);

        $sql = "UPDATE " . DB_USERS . " 
        SET avatar = :avatar, bio = :bio, userPassword = :userPassword
        WHERE id = :id";

        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':id', $user->getId(), PDO::PARAM_INT);
        $req->bindValue(':bio', $user->getBio(), PDO::PARAM_STR);
        $req->bindValue(':avatar', $user->getAvatar(), PDO::PARAM_STR);
        $req->bindValue(':userPassword', $user->getUserPassword(), PDO::PARAM_STR);

        return $req->execute();
    }

    public function existing($value, $type = 'userName') 
    { // Check if there is already an username with the same name
        $sql = "SELECT TRUE FROM " . DB_USERS . " WHERE " . $type . " = :val";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':val', $value, PDO::PARAM_STR);
        $req->execute();
        return $req->fetch() ? true : false;
    }

    public function getUserInfoBy($data, $by = 'userName')
    { // Return user object by the selector specified
        $sql = "SELECT * FROM " . DB_USERS . " WHERE " . $by . " = :by";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':by', $data, PDO::PARAM_STR);
        $req->execute();
        $data = $req->fetch(PDO::FETCH_ASSOC);
        if ($data) 
        { // Data found, hydrate a user object and return it
            $user = new User($data);
            return $user;
        }  
        return false;
    }
    ///////

    function getNum($userId, $toCount, $db) 
    {
        $sql = "SELECT SUM(" . $toCount . ") as total FROM " . $db . " WHERE userId = :userId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->execute();
        $num = $req->fetch(PDO::FETCH_ASSOC);
        return (int) $num['total'];
    }


}