<?php

class NewsManager extends Model
{

    public function newsCreate($news)       
    { // Creates a news on the article
        $sql = "INSERT INTO " . DB_NEWS . "
        (userId, author, newsTitle, newsContent, datePosted)
        VALUES 
        (:userId, :author, :newsTitle, :newsContent, NOW())";
        //WHERE EXISTS
        //(SELECT id FROM " . DB_USERS . " WHERE userId = ". $news->getAuthorId();

        $req = Database::getBdd()->prepare($sql);
        
        $req->bindValue(':userId', $news->getUserId(), PDO::PARAM_INT);
        $req->bindValue(':author', $news->getAuthor(), PDO::PARAM_STR);
        $req->bindValue(':newsTitle', $news->getNewsTitle(), PDO::PARAM_STR);
        $req->bindValue(':newsContent', $news->getNewsContent(), PDO::PARAM_STR);       

        $req->execute();

        //var_dump($req->errorInfo());
        
        return $req->rowCount() > 0;
    }

    public function newsUpdate($news)
    { // Update a news
        $sql = "UPDATE " . DB_NEWS . " SET
        newsTitle = :newsTitle, newsContent = :newsContent, dateEdited = NOW()
        WHERE
        id = :newsId";

        $req = Database::getBdd()->prepare($sql);
        
        $req->bindValue(':newsId', $news->getId(), PDO::PARAM_INT);
        $req->bindValue(':newsTitle', $news->getNewsTitle(), PDO::PARAM_STR);
        $req->bindValue(':newsContent', $news->getNewsContent(), PDO::PARAM_STR);       

        $req->execute();
        
        return $req->rowCount() > 0;
    }

    public function getNews($userId, $offset = 1)
    { // Return offset number of News objects
        $numPerPage = NEWS_PER_PAGE;
        $offset = (($offset - 1) * $numPerPage);

        $sql = "SELECT * FROM " . DB_NEWS . " WHERE userId = :userId ORDER BY datePosted DESC LIMIT :offset, :numElts";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, PDO::PARAM_INT);
        $req->bindValue(':numElts', $numPerPage, PDO::PARAM_INT);
        $req->execute();

        $news = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $news[] = new News($data); 
        }
        return $news;
    }

    public function getNewsContent($postId)
    { // Displays single image content
        $sql = "SELECT * FROM " . DB_NEWS . " WHERE id = :id";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':id', $postId, PDO::PARAM_INT);
        $req->execute();

        $news = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $news[] = new News($data); 
        }

        return $news;
    }

    public function numElt($userId, $toCount = '*')
    { // Returns the total number of images in the database
        $sql = "SELECT COUNT(" . $toCount . ") as total FROM " . DB_NEWS . " WHERE userId = :userId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->execute();
        $num = $req->fetch(PDO::FETCH_ASSOC);
        return (int) $num['total'];
    }

    public function addOne($postId, $to = 'views', $minus='+')
    { // Add 1 in the specified col
        $sql = "UPDATE " . DB_NEWS . " SET ".$to." = ".$to." ".$minus." 1 WHERE id = :postId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();
    }

    public function likeNews($postId, $userId)
    { // Returns the total number of news in the database
        $db = Database::getBdd();

        $sql = "SELECT 1 FROM ". DB_NEWS_LIKES . " WHERE postId = :postId AND userId = :userId";
        $req =  $db->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->execute();
        $exist = (bool) $req->fetch();

        if (!$exist) 
        { 
            $sql = "INSERT INTO " . DB_NEWS_LIKES . " 
            (postId, userId)
            VALUES
            (:postId, :userId)";
    
            $req = $db->prepare($sql);
            $req->bindValue(':postId', $postId, PDO::PARAM_INT);
            $req->bindValue(':userId', $userId, PDO::PARAM_INT);  
            $req->execute();

            if ($req->rowCount() > 0) {
                $sql = "UPDATE " . DB_NEWS . " SET likes = likes + 1 WHERE id = :postId";
                $req = $db->prepare($sql);
                $req->bindValue(':postId', $postId, PDO::PARAM_INT);
                $req->execute();
            }        
        } 
        else
        { // Already exists -> dislike by deleting the row
            $sql = "DELETE FROM " . DB_NEWS_LIKES . " WHERE postId = :postId";
            $req = $db->prepare($sql);
            $req->bindValue(':postId', $postId, PDO::PARAM_INT);
            $req->execute();

            if ($req->rowCount() > 0)  {
                $sql = "UPDATE " . DB_NEWS . " SET likes = likes - 1 WHERE id = :postId";
                $req = $db->prepare($sql);
                $req->bindValue(':postId', $postId, PDO::PARAM_INT);
                $req->execute();
            }            
        }
    }

    public function liked($postId, $userId)
    { // Returns if the news is liked by this user
        $sql = "SELECT 1 FROM " . DB_NEWS_LIKES . " WHERE postId = :postId AND userId = :userId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_STR);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();
        return (bool) $req->fetch();
    }

    public function deletePost($postId)
    { // Returns comments on the image
        $db = Database::getBdd();

        $sql = "DELETE FROM " . DB_COMMENTS_NEWS_LIKES . " WHERE postId = :postId";
        $req = $db->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();

        $sql = "DELETE FROM " . DB_COMMENTS_NEWS . " WHERE postId = :postId";
        $req = $db->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();

        $sql = "DELETE FROM " . DB_NEWS_LIKES . " WHERE postId = :postId";
        $req = $db->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();        

        $sql = "DELETE FROM " . DB_NEWS . " WHERE id = :postId";
        $req = $db->prepare($sql);
        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->execute();

        return $req->rowCount() > 0;
    }

}