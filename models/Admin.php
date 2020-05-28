<?php
class Admin extends Model
{
    public function getLoginInfo()
    { // Check if data in databse is correct
        $sql = "SELECT userName, password FROM members WHERE ID = :id";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':id', 17, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function checkArticleExists($title) 
    { // Check if there is already an  article with the same title, add it to the database if not
        $sql = 'SELECT TRUE FROM news WHERE title = :title';
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':title', $title);
        $req->execute();
        return $req->fetch() ? true : false;
    }

    public function create($data) 
    {

        $news = new News($data); // Fill a news object witg post data

        if (isset($data['excerpt']) && $data['excerpt'] === '') {
            $news->setExcerpt($this->makeExcerpt($news->getContent()));
        }

        $news->setAuthorID(17);

        $sql = "INSERT INTO news
        (author, authorID, title, category, excerpt, content, image, datePosted) 
        VALUES
        (:author, :authorID, :title, :category, :excerpt, :content, :image, NOW())";

        $req = Database::getBdd()->prepare($sql);
        
        $req->bindValue(':author', 'Jean Forteroche');
        $req->bindValue(':authorID', $news->getAuthorID());
        $req->bindValue(':title', $news->getTitle());
        $req->bindValue(':category', $news->getCategory());
        $req->bindValue(':excerpt', $news->getExcerpt());
        $req->bindValue(':content', $news->getContent());
        $req->bindValue(':image', $news->getImage());

        $req->execute();
 
    }

    public function edit($data)
    {
        extract($data); // extract News & Edited vars

        if (empty($_POST['excerpt'])) {
            $Edited['excerpt'] = $this->makeExcerpt($_POST['content']);
        }

        $sql = "UPDATE news
        SET title = :title, category = :category, excerpt = :excerpt, content = :content, image = :image, dateEdited = NOW() 
        WHERE ID = :id";
        
        $req = Database::getBdd()->prepare($sql);

        $req->bindValue(':id', $News[0]->getID(), PDO::PARAM_INT);
        $req->bindValue(':title', $Edited['title']);
        $req->bindValue(':category', $Edited['category']);
        $req->bindValue(':excerpt', $Edited['excerpt']);
        $req->bindValue(':content', $Edited['content']);
        $req->bindValue(':image', $Edited['image']);

        $req->execute();
    }

    public function delete($ID)
    {
        // Delete all related comments
        $sql = 'DELETE FROM comments WHERE newsID = :id';
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':id', $ID, PDO::PARAM_INT);
        $req->execute();

        // delete article
        $sql = 'DELETE FROM news WHERE ID = :id';
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':id', $ID, PDO::PARAM_INT);
        return $req->execute();
    }

    public function getCategories()
    {
        $sql = "SELECT * FROM tags";
        $req = Database::getBdd()->prepare($sql);
        $req->execute();

        $category = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        { 
            $category[] = new Category($data); 
        }
    
        return (!empty($category)) ? $category : null;
    }

    ////// COMMENTS
    public function listMostReported()
    { // Returns comments on the article
        $sql = "SELECT * FROM comments WHERE reports > 0 ORDER BY reports DESC LIMIT 10";
        $req = Database::getBdd()->prepare($sql);

        $req->execute();

        $comments = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        {   
            $comments[] = new Comment($data);
        }

        return (!empty($comments)) ? $comments : null; 
    }

    public function deleteComment($ID)
    { // Deletes the comment
        $sql = 'DELETE FROM comments WHERE id = ?';
        $req = Database::getBdd()->prepare($sql);
        return $req->execute([$ID]);
    }
    //////
}