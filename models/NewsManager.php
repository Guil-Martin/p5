<?php

class NewsManager extends Model
{
    /////// ARTICLES
    public function createNews($news)       
    { // Creates a news on the article
        $sql = "INSERT INTO " . DB_NEWS . "
        (userid, author, newstitle, newscontent, datePosted)
        VALUES 
        (:userid, :author, :newstitle, :newscontent, NOW())";
        //WHERE EXISTS
        //(SELECT id FROM " . DB_USERS . " WHERE userId = ". $news->getAuthorId();

        $req = Database::getBdd()->prepare($sql);
        
        $req->bindValue(':userid', $news->getAuthorId(), PDO::PARAM_INT);
        $req->bindValue(':author', $news->getAuthor(), PDO::PARAM_STR);
        $req->bindValue(':newstitle', $news->getNewsTitle(), PDO::PARAM_STR);
        $req->bindValue(':newscontent', $news->getNewsContent(), PDO::PARAM_STR);       

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
    public function getNewsContent($userId, $newsId)
    { // Displays single image content
        $sql = "SELECT * FROM news WHERE (userId = " . $userId . " AND newsId = " . $newsId . ")";

        $req = Database::getBdd()->prepare($sql);

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











    public function showAllNews()
    {
        $sql = "SELECT * FROM news ORDER BY datePosted DESC";
        $req = Database::getBdd()->prepare($sql);
        $req->execute();

        $news = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $news[] = new News($data); 
        }
    
        return (!empty($news)) ? $news : null;
    }

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