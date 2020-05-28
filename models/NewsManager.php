<?php

class NewsManager extends Model
{
    /////// ARTICLES
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

        return $req->execute();
    }

    /////// NEWS
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
        $sql = "SELECT * FROM news WHERE id = :id";
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
        return (int) $req->fetch(PDO::FETCH_ASSOC);
    }
    ///////
    /////// COMMENTS
    public function createComment($comment)       
    { // Creates a news on the article
        $sql = "INSERT INTO " . DB_COMMENTS_NEWS . "
        (newsId, userName, commentContent, datePosted)
        VALUES 
        (:newsId, :userName, :commentContent, NOW())";

        $req = Database::getBdd()->prepare($sql);
        
        $req->bindValue(':newsId', $comment->getNewsId(), PDO::PARAM_INT);
        $req->bindValue(':userName', $comment->getUserName(), PDO::PARAM_STR);
        $req->bindValue(':commentContent', $comment->getCommentContent(), PDO::PARAM_STR);    

        return $req->execute();
    }
    public function getComments($newsId)
    { // Returns comments on the article
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
    ///////

    public function getCategoryList() {
        $tags = [];

        $sql = "SELECT name, id FROM tags";
        $req = Database::getBdd()->prepare($sql);

        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $tags[$data['id']] = $data['name']; 
        }

        return (!empty($tags)) ? $tags : false;
    }

    public function getCategory($ID) {
        $sql = "SELECT name FROM tags WHERE ID = ".$ID;
        $req = Database::getBdd()->prepare($sql);
        $data = $req->fetch(PDO::FETCH_ASSOC);

        return (!empty($data)) ? $data['name'] : false;
    }

    public function showNewsByCategory($category) {

        $sql = "SELECT * FROM news WHERE category = :category ORDER BY datePosted DESC";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':category', $category);
   
        $req->execute();

        $news = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $news[] = new News($data); 
        }

        return (!empty($news)) ? $news : null;
    }
    ///////

    /////// COMMENTS
    public function countComments($ID)
    { // Returns the total number of comments for this article
        $sql = "SELECT COUNT(*) as total FROM comments WHERE ID = :id";
        $req = Database::getBdd()->prepare($sql);

        $req->bindValue(':id', $ID, PDO::PARAM_INT);
    
        $req->execute();

        $data = $req->fetch(PDO::FETCH_ASSOC);

        return (int) $data['total']; 
    }

}