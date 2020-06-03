<?php

class NewsManager extends Model
{

    public function createNews($news)       
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
        
        return $req->rowCount() > 0;
    }

    public function getNews($userId, $offset = 1)
    { // Return offset number of News objects
        $offset = $offset * NEWS_PER_PAGE;

        $sql = "SELECT * FROM " . DB_NEWS . " WHERE userId = :userId ORDER BY datePosted DESC LIMIT :offset";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->bindValue(':offset', $offset, PDO::PARAM_INT);
        $req->execute();

        $news = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $news[] = new News($data); 
        }
        return $news;
    }

    public function getNewsContent($newsId)
    { // Displays single image content
        $sql = "SELECT * FROM " . DB_NEWS . " WHERE id = :id";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':id', $newsId, PDO::PARAM_INT);
        $req->execute();

        $news = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $news[] = new News($data); 
        }

        return $news;
    }

    public function countNews($userId)
    { // Returns the total number of news in the database
        $sql = "SELECT COUNT(*) as total FROM " . DB_NEWS . " WHERE userId = :userId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function likeNews($newsId, $userId)
    { // Returns the total number of news in the database
        $db = Database::getBdd();

        $sql = "SELECT 1 FROM ". DB_NEWS_LIKES . " WHERE newsId = :newsId AND userId = :userId";
        $req =  $db->prepare($sql);
        $req->bindValue(':newsId', $newsId, PDO::PARAM_INT);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->execute();
        $exist = (bool) $req->fetch();

        if (!$exist) 
        { 
            $sql = "INSERT INTO " . DB_NEWS_LIKES . " 
            (newsId, userId)
            VALUES
            (:newsId, :userId)";
    
            $req = $db->prepare($sql);
            $req->bindValue(':newsId', $newsId, PDO::PARAM_INT);
            $req->bindValue(':userId', $userId, PDO::PARAM_INT);  
            $req->execute();

            if ($req->rowCount() > 0) {
                $sql = "UPDATE " . DB_NEWS . " SET likes = likes + 1 WHERE id = :newsId";
                $req = $db->prepare($sql);
                $req->bindValue(':newsId', $newsId, PDO::PARAM_INT);
                $req->execute();
            }        
        } 
        else
        { // Already exists -> dislike by deleting the row
            $sql = "DELETE FROM " . DB_NEWS_LIKES . " WHERE newsId = :newsId";
            $req = $db->prepare($sql);
            $req->bindValue(':newsId', $newsId, PDO::PARAM_INT);
            $req->execute();

            if ($req->rowCount() > 0)  {
                $sql = "UPDATE " . DB_NEWS . " SET likes = likes - 1 WHERE id = :newsId";
                $req = $db->prepare($sql);
                $req->bindValue(':newsId', $newsId, PDO::PARAM_INT);
                $req->execute();
            }            
        }
    }

    public function liked($newsId, $userId)
    { // Returns if the news is liked by this user
        $sql = "SELECT 1 FROM " . DB_NEWS_LIKES . " WHERE newsId = :newsId AND userId = :userId";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':userId', $userId, PDO::PARAM_STR);
        $req->bindValue(':newsId', $newsId, PDO::PARAM_INT);
        $req->execute();
        return (bool) $req->fetch();
    }

    /////// COMMENTS
    public function createComment($comment)       
    { // Creates a comment on the news
        $sql = "INSERT INTO " . DB_COMMENTS_NEWS . "
        (newsId, userName, commentContent, datePosted)
        VALUES 
        (:newsId, :userName, :commentContent, NOW())";

        $req = Database::getBdd()->prepare($sql);
        
        $req->bindValue(':newsId', $comment->getPostId(), PDO::PARAM_INT);
        $req->bindValue(':userName', $comment->getUserName(), PDO::PARAM_STR);
        $req->bindValue(':commentContent', $comment->getCommentContent(), PDO::PARAM_STR);    
        $req->execute();

        return $req->rowCount() > 0;
    }

    public function getComments($newsId)
    { // Returns comments on the news
        $sql = "SELECT * FROM ". DB_COMMENTS_NEWS . " WHERE newsId = :newsId ORDER BY datePosted DESC";
        $req = Database::getBdd()->prepare($sql);

        $req->bindValue(':newsId', $newsId, PDO::PARAM_INT);

        $req->execute();

        $comments = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        {   
            $comments[] = new Comment($data);
        }

        return (!empty($comments)) ? $comments : null; 
    }

}