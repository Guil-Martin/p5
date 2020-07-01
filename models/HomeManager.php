<?php

class HomeManager extends Model
{

    public function getFiltered($db, $period, $number, $order, $offset = 1)
    {
        $numPerPage = $db == DB_NEWS ? NEWS_PER_PAGE : IMAGES_PER_PAGE;
        $offset = (($offset - 1) * $numPerPage);

        // Sets a var to add filter by views/likes/comments if needed
        $orderByPlus = !empty($number) ? $number : '';

        $period = !empty($period) ? "WHERE db.datePosted >= DATE_ADD(CURDATE(), INTERVAL -" . $period . " DAY)" : "";

        $sql = "SELECT *, db.id AS postId, u.contentId AS userContentId, 
        u.avatar, u.privilege, u.userMail
        FROM " . $db . " AS db
        LEFT JOIN
            users AS u
            ON (db.userId = u.id)
        ".$period."
        ORDER BY ". $orderByPlus ." " . $order . "
        LIMIT :offset, :numElts";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':offset', $offset, PDO::PARAM_INT);
        $req->bindValue(':numElts', $numPerPage, PDO::PARAM_INT);

        $req->execute();

        $posts = [];

        while ($data = $req->fetch()) 
        { 
            $posts[] = $data;
        }

        return $posts;
    }

    public function numElt($db, $toCount = '*')
    { // Returns the total number of images in the database
        $sql = "SELECT COUNT(" . $toCount . ") as total FROM " . $db;
        $req = Database::getBdd()->prepare($sql);
        $req->execute();
        $num = $req->fetch();
        return (int) $num['total'];
    }

    public function getPostMost($db, $orderBy) 
    {

        $sql = "SELECT " . $db . ".*, 
        users.id AS userId, users.contentId AS userContentId,
        users.avatar, users.privilege, users.userMail
        FROM " . $db . ", users
        WHERE " . $db . ".userId = users.id
        ORDER BY " . $db . "." . $orderBy. "
        DESC LIMIT :offset, :numElts";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':offset', 0, PDO::PARAM_INT);
        $req->bindValue(':numElts', 10, PDO::PARAM_INT);

        $req->execute();

        $posts = [];
        while ($data = $req->fetch()) 
        {
            $posts[] = $data;//new News($data);
        }

        //var_dump($news);

        return $posts;
    }

    public function getImgMost($db, $orderBy)
    {
        $sql = "SELECT * FROM " . $db . " ORDER BY ". $orderBy. " DESC LIMIT :offset, :numElts";
        $req = Database::getBdd()->prepare($sql);
        $req->bindValue(':offset', 0, PDO::PARAM_INT);
        $req->bindValue(':numElts', 10, PDO::PARAM_INT);

        $req->execute();
        
        $news = [];
        while ($data = $req->fetch()) 
        { 
            $news[] = new Image($data); 
        }
        return $news;
    }

}