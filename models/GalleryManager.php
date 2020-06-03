<?php

class GalleryManager extends Model
{

    public function galleryCreate($image)       
    { // Creates a img on the article
        $sql = "INSERT INTO " . DB_IMG . "
        (userId, author, imgTitle, imgContent, imgPath, imgThumbnail, datePosted)
        VALUES 
        (:userId, :author, :imgTitle, :imgContent, :imgPath, :imgThumbnail, NOW())";

        $req = Database::getBdd()->prepare($sql);
        
        $req->bindValue(':userId', $image->getUserId(), PDO::PARAM_INT);
        $req->bindValue(':author', $image->getAuthor(), PDO::PARAM_STR);
        $req->bindValue(':imgTitle', $image->getImgTitle(), PDO::PARAM_STR);
        $req->bindValue(':imgContent', $image->getImgContent(), PDO::PARAM_STR);
        $req->bindValue(':imgPath', $image->getImgPath(), PDO::PARAM_STR); 
        $req->bindValue(':imgThumbnail', $image->getImgThumbnail(), PDO::PARAM_STR); 
        
        $req->execute();

        var_dump($req->errorInfo());
        
        return $req->rowCount() > 0;
    }

    public function getGallery($userId, $offset = 1)
    { // Return offset number of image objects
        $offset = $offset * IMAGES_PER_PAGE;

        $sql = "SELECT * FROM " . DB_IMG . " WHERE userId = :userId ORDER BY datePosted DESC LIMIT :offset";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, PDO::PARAM_INT);
        $req->execute();

        $image = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $image[] = new Image($data); 
        }
        return $image;
    }

    public function getImageContent($imgId)
    { // Displays single image content
        $sql = "SELECT * FROM " . DB_IMG . " WHERE id = :id";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':id', $imgId, PDO::PARAM_INT);
        $req->execute();

        $image = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $image[] = new Image($data); 
        }

        return $image;
    }

    public function countImages($userId)
    { // Returns the total number of images in the database
        $sql = "SELECT COUNT(*) as total FROM " . DB_IMG . " WHERE userId = :userId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function likeImage($imgId, $userId)
    { // Returns the total number of images in the database
        $db = Database::getBdd();

        $sql = "SELECT 1 FROM ". DB_IMG_LIKES . " WHERE imgId = :imgId AND userId = :userId";
        $req =  $db->prepare($sql);
        $req->bindValue(':imgId', $imgId, PDO::PARAM_INT);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->execute();
        $exist = (bool) $req->fetch();

        if (!$exist) 
        { 
            $sql = "INSERT INTO " . DB_IMG_LIKES . " 
            (imgId, userId)
            VALUES
            (:imgId, :userId)";
    
            $req = $db->prepare($sql);
            $req->bindValue(':imgId', $imgId, PDO::PARAM_INT);
            $req->bindValue(':userId', $userId, PDO::PARAM_INT);  
            $req->execute();

            if ($req->rowCount() > 0) {
                $sql = "UPDATE " . DB_IMG . " SET likes = likes + 1 WHERE id = :imgId";
                $req = $db->prepare($sql);
                $req->bindValue(':imgId', $imgId, PDO::PARAM_INT);
                $req->execute();
            }        
        } 
        else
        { // Already exists -> dislike by deleting the row
            $sql = "DELETE FROM " . DB_IMG_LIKES . " WHERE imgId = :imgId";
            $req = $db->prepare($sql);
            $req->bindValue(':imgId', $imgId, PDO::PARAM_INT);
            $req->execute();

            if ($req->rowCount() > 0)  {
                $sql = "UPDATE " . DB_IMG . " SET likes = likes - 1 WHERE id = :imgId";
                $req = $db->prepare($sql);
                $req->bindValue(':imgId', $imgId, PDO::PARAM_INT);
                $req->execute();
            }            
        }
    }
    
    public function liked($imgId, $userId)
    { // Returns if the image is liked by this user
        $sql = "SELECT 1 FROM " . DB_IMG_LIKES . " WHERE imgId = :imgId AND userId = :userId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_STR);
        $req->bindValue(':imgId', $imgId, PDO::PARAM_INT);
        $req->execute();
        return (bool) $req->fetch();
    }

    /////// COMMENTS
    public function createComment($comment)       
    { // Creates a comment on the image
        $sql = "INSERT INTO " . DB_COMMENTS_IMG . "
        (imgId, userName, commentContent, datePosted)
        VALUES 
        (:imgId, :userName, :commentContent, NOW())";

        $req = Database::getBdd()->prepare($sql);
        
        $req->bindValue(':imgId', $comment->getPostId(), PDO::PARAM_INT);
        $req->bindValue(':userName', $comment->getUserName(), PDO::PARAM_STR);
        $req->bindValue(':commentContent', $comment->getCommentContent(), PDO::PARAM_STR);    
        $req->execute();

        return $req->rowCount() > 0;
    }

    public function getComments($imgId)
    { // Returns comments on the image
        $sql = "SELECT * FROM ". DB_COMMENTS_IMG . " WHERE imgId = :imgId ORDER BY datePosted DESC";
        $req = Database::getBdd()->prepare($sql);

        $req->bindValue(':imgId', $imgId, PDO::PARAM_INT);

        $req->execute();

        $comments = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        {   
            $comments[] = new Comment($data);
        }

        return (!empty($comments)) ? $comments : null; 
    }

}