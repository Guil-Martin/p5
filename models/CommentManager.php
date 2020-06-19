<?php

class CommentManager extends Model
{

    public function createComment($comment, $db)       
    { // Creates a comment on the image
        $sql = "INSERT INTO " . $db . "
        (postId, userName, userId, commentContent, datePosted)
        VALUES 
        (:postId, :userName, :userId, :commentContent, NOW())";

        $req = Database::getBdd()->prepare($sql);
        
        $req->bindValue(':postId', $comment->getPostId(), PDO::PARAM_INT);
        $req->bindValue(':userId', $comment->getUserId(), PDO::PARAM_INT);
        $req->bindValue(':userName', $comment->getUserName(), PDO::PARAM_STR);
        $req->bindValue(':commentContent', $comment->getCommentContent(), PDO::PARAM_STR);    
        $req->execute();

        return $req->rowCount() > 0;
    }

    public function getComments($postId, $connectedUserId, $db)
    { // Returns comments on the post

        $dbLike = $db == DB_COMMENTS_NEWS ? DB_COMMENTS_NEWS_LIKES : DB_COMMENTS_IMG_LIKES;

        $sql = "SELECT *, n.id, u.id AS userId, u.avatar, u.contentId, l.userId AS likedComment
        FROM  " . $db . " n
        LEFT JOIN
            users AS u
            ON (n.userId = u.id)
        LEFT OUTER JOIN
            " . $dbLike . " AS l
            ON (l.userId = :connectedUserId AND n.id = l.commentId)
        WHERE n.postId = :postId ORDER BY datePosted DESC
        ";

        $req = Database::getBdd()->prepare($sql);

        $req->bindValue(':postId', $postId, PDO::PARAM_INT);
        $req->bindValue(':connectedUserId', $connectedUserId, PDO::PARAM_INT);

        $req->execute();        

        $comments = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) 
        {   
            $comments[] = $data;
        }
        return $comments;

        return (!empty($comments)) ? $comments : null; 
    }

    public function likeComment($commentId, $postId, $userId, $dbSrc)
    { // Returns the total number of news in the database

        $dbLike = $dbSrc == DB_COMMENTS_NEWS ? DB_COMMENTS_NEWS_LIKES : DB_COMMENTS_IMG_LIKES;

        $db = Database::getBdd();

        $sql = "SELECT 1 FROM ". $dbLike . " WHERE commentId = :commentId AND userId = :userId";
        $req =  $db->prepare($sql);
        $req->bindValue(':commentId', $commentId, PDO::PARAM_INT);
        $req->bindValue(':userId', $userId, PDO::PARAM_INT);
        $req->execute();
        $exist = (bool) $req->fetch();

        if (!$exist) 
        { 
            $sql = "INSERT INTO " . $dbLike . " 
            (commentId, postId, userId)
            VALUES
            (:commentId, :postId, :userId)";
    
            $req = $db->prepare($sql);
            $req->bindValue(':commentId', $commentId, PDO::PARAM_INT);
            $req->bindValue(':postId', $postId, PDO::PARAM_INT);  
            $req->bindValue(':userId', $userId, PDO::PARAM_INT);  
            $req->execute();

            if ($req->rowCount() > 0) {
                $sql = "UPDATE " . $dbSrc . " SET likes = likes + 1 WHERE id = :commentId";
                $req = $db->prepare($sql);
                $req->bindValue(':commentId', $commentId, PDO::PARAM_INT);
                $req->execute();
            }        
        } 
        else
        { // Already exists -> dislike by deleting the row
            $sql = "DELETE FROM " . $dbLike . " WHERE userId = :userId";
            $req = $db->prepare($sql);
            $req->bindValue(':userId', $userId, PDO::PARAM_INT);
            $req->execute();

            if ($req->rowCount() > 0)  {
                $sql = "UPDATE " . $dbSrc . " SET likes = likes - 1 WHERE id = :commentId";
                $req = $db->prepare($sql);
                $req->bindValue(':commentId', $commentId, PDO::PARAM_INT);
                $req->execute();
            }            
        }
    }

    public function deleteComment($comId, $postId, $userId, $dbSrc)
    { // Delete comment

        $db = Database::getBdd();

        $dbLike = $dbSrc == DB_COMMENTS_NEWS ? DB_COMMENTS_NEWS_LIKES : DB_COMMENTS_IMG_LIKES;

        echo '$dbLike - ' . $dbLike . '<br>';

        $sql = "DELETE FROM " . $dbLike . " WHERE commentId = :comId";
        $req = $db->prepare($sql);
        $req->bindValue(':comId', $comId, PDO::PARAM_INT);
        $req->execute(); // Delete all likes

        $sql = "DELETE FROM " . $dbSrc . " WHERE id = :comId";
        $req = $db->prepare($sql);
        $req->bindValue(':comId', $comId, PDO::PARAM_INT);
        $req->execute(); // Delete all comments liked to the post

        //var_dump($req->errorInfo());

        return $req->rowCount() > 0;
    }

    public function addReportOnComment($data, $db)       
    { // Adds a report to the total of report on the comment

    }

}