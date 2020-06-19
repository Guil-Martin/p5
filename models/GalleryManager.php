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

        return $req->rowCount() > 0;
    }

    public function galleryUpdate($image)
    { // Update an image

        //var_dump($image);

        $sql = "UPDATE " . DB_IMG . " SET
        imgTitle = :imgTitle, imgContent = :imgContent, imgPath = :imgPath, imgThumbnail = :imgThumbnail, dateEdited = NOW()
        WHERE
        id = :imgId";

        $req = Database::getBdd()->prepare($sql);

        $req->bindValue(':imgId', $image->getId(), PDO::PARAM_INT);
        $req->bindValue(':imgTitle', $image->getImgTitle(), PDO::PARAM_STR);
        $req->bindValue(':imgContent', $image->getImgContent(), PDO::PARAM_STR);
        $req->bindValue(':imgPath', $image->getImgPath(), PDO::PARAM_STR); 
        $req->bindValue(':imgThumbnail', $image->getImgThumbnail(), PDO::PARAM_STR);      

        $req->execute();

        //var_dump($req->errorInfo());
        
        return $req->rowCount() > 0;
    }

    public function getGallery($userId, $offset = 1)
    { // Return offset number of image objects
        
        $numPerPage = IMAGES_PER_PAGE;
        $offset = (($offset - 1) * $numPerPage);

        $sql = "SELECT * FROM " . DB_IMG . " WHERE userId = :userId ORDER BY datePosted DESC LIMIT :offset, :numElts";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, PDO::PARAM_INT);
        $req->bindValue(':numElts', $numPerPage, PDO::PARAM_INT);
        $req->execute();

        $image = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $image[] = new Image($data); 
        }
        return $image;
    }

    public function getImageContent($postId)
    { // Displays single image content
        $sql = "SELECT * FROM " . DB_IMG . " WHERE id = :id";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':id', $postId, PDO::PARAM_INT);
        $req->execute();

        $image = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $image[] = new Image($data); 
        }

        return $image;
    }

    public function numElt($userId, $toCount = '*')
    { // Returns the total number of images in the database
        $sql = "SELECT COUNT(" . $toCount . ") as total FROM " . DB_IMG . " WHERE userId = :userId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->execute();
        $num = $req->fetch(PDO::FETCH_ASSOC);
        return (int) $num['total'];
    }

    public function addOne($postId, $to = 'views', $minus='+')
    { // Add 1 in the specified col
        $sql = "UPDATE " . DB_IMG . " SET ".$to." = ".$to." ".$minus." 1 WHERE id = :postId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();
    }

    public function likeImage($postId, $userId)
    { // Returns the total number of images in the database
        $db = Database::getBdd();

        $sql = "SELECT 1 FROM ". DB_IMG_LIKES . " WHERE postId = :postId AND userId = :userId";
        $req =  $db->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->execute();
        $exist = (bool) $req->fetch();

        if (!$exist) 
        { 
            $sql = "INSERT INTO " . DB_IMG_LIKES . " 
            (postId, userId)
            VALUES
            (:postId, :userId)";
    
            $req = $db->prepare($sql);
            $req->bindValue(':postId', $postId, PDO::PARAM_INT);
            $req->bindValue(':userId', $userId, PDO::PARAM_INT);  
            $req->execute();

            if ($req->rowCount() > 0) {
                $sql = "UPDATE " . DB_IMG . " SET likes = likes + 1 WHERE id = :postId";
                $req = $db->prepare($sql);
                $req->bindValue(':postId', $postId, PDO::PARAM_INT);
                $req->execute();
            }        
        } 
        else
        { // Already exists -> dislike by deleting the row
            $sql = "DELETE FROM " . DB_IMG_LIKES . " WHERE postId = :postId";
            $req = $db->prepare($sql);
            $req->bindValue(':postId', $postId, PDO::PARAM_INT);
            $req->execute();

            if ($req->rowCount() > 0)  {
                $sql = "UPDATE " . DB_IMG . " SET likes = likes - 1 WHERE id = :postId";
                $req = $db->prepare($sql);
                $req->bindValue(':postId', $postId, PDO::PARAM_INT);
                $req->execute();
            }            
        }
    }
    
    public function liked($postId, $userId)
    { // Returns if the image is liked by this user
        $sql = "SELECT 1 FROM " . DB_IMG_LIKES . " WHERE postId = :postId AND userId = :userId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_STR);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();
        return (bool) $req->fetch();
    }

    public function deleteImage($postId)
    { // Returns comments on the image
        $db = Database::getBdd();

        $sql = "DELETE FROM " . DB_COMMENTS_IMG_LIKES . " WHERE postId = :postId";
        $req = $db->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();

        $sql = "DELETE FROM " . DB_COMMENTS_IMG . " WHERE postId = :postId";
        $req = $db->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();

        $sql = "DELETE FROM " . DB_IMG_LIKES . " WHERE postId = :postId";
        $req = $db->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();  

        $sql = "DELETE FROM " . DB_IMG . " WHERE id = :postId";
        $req = $db->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();

        return $req->rowCount() > 0;
    }
    
}