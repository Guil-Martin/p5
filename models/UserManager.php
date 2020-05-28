<?php

class UserManager extends Model
{

    /////// REGISTER / LOGIN / LOGOUT
    public function registerUser($user)
    { // Register user in the member database
        $sql = "INSERT INTO " . DB_USERS . "
        (contentId, userName, userPassword, userMail, dateRegistered)
        VALUES
        (:contentId, :userName, :userPassword, :userMail, NOW())";

        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':contentId', $user->getContentId(), PDO::PARAM_STR);
        $req->bindValue(':userName', $user->getUserName(), PDO::PARAM_STR);
        $req->bindValue(':userMail', $user->getUserMail(), PDO::PARAM_STR);
        $req->bindValue(':userPassword', $user->getUserPassword(), PDO::PARAM_STR);

        return $req->execute();
    }

    public function existing($value, $type = 'userName') 
    { // Check if there is already an username with the same name
        $sql = "SELECT TRUE FROM " . DB_USERS . " WHERE " . $type . " = :val";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':val', $value, PDO::PARAM_STR);
        $req->execute();
        return $req->fetch();
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

    /////// GALLERY
    public function getImages($userId, $offset = 1)
    { // Return offset number of Image objects
        $offset = $offset * IMAGES_PER_PAGE;

        $sql = "SELECT * FROM " . DB_IMG . " WHERE userId = :userId ORDER BY datePosted DESC LIMIT :offset";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, PDO::PARAM_INT);
        $req->execute();

        $images = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $images[] = new Image($data); 
        }
        return $images;
    }
    public function getImageContent($userId)
    { // Displays single image content

    }
    ///////

    /////// COMMENTS
    public function showComments($ID)
    { // Returns comments on the article
        $sql = "SELECT * FROM comments WHERE newsID = ? ORDER BY datePosted DESC";
        $req = Database::getBdd()->prepare($sql);

        $req->execute([$ID]);
        //$arr = $req->errorInfo(); print_r($arr);

        $comments = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        {   
            $comments[] = new Comment($data);
        }

        return (!empty($comments)) ? $comments : null; 
    }

    public function countComments($ID)
    { // Returns the total number of comments for this article
        $sql = "SELECT COUNT(*) as total FROM comments WHERE ID = :id";
        $req = Database::getBdd()->prepare($sql);

        $req->bindValue(':id', $ID, PDO::PARAM_INT);
    
        $req->execute();

        $data = $req->fetch(PDO::FETCH_ASSOC);

        return (int) $data['total']; 
    }

    public function createComment($data)       
    { // Creates a comment on the article

        // Creates a comment object to add it to the db
        $comment = new Comment($data);

        $sql = "INSERT INTO comments
        (author, content, newsID, datePosted)
        VALUES
        (:author, :content, :newsID, NOW())";

        $req = Database::getBdd()->prepare($sql);
        
        $req->bindValue(':author', $comment->getAuthor());
        $req->bindValue(':content', $comment->getContent());
        $req->bindValue(':newsID', $comment->getNewsID());

        $req->execute();

        // set id
        $comment->setID(Database::getBdd()->lastInsertId());
    }

    public function addReportOnComment($data)       
    { // Adds a report to the total of report on the comment

        $ID = (int) $data[0];
        $newsID = (int) $data[1];

        $sql =  "UPDATE comments SET reports = reports + 1 WHERE ID = ? AND newsID = ?";
        $req = Database::getBdd()->prepare($sql);
        return $req->execute([$ID, $newsID]);
    }
    ///////

}