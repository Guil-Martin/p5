<?php
class Comment
{
    protected
    $_id,
    $_postId,
    $_userId,
    $_userName,
    $_content,
    $_datePosted,
    $_dateEdited,
    $_likes,
    $_whoLikedId,
    $_reports;


    public function __construct(array $data)
    {
        foreach ($data as $key => $value) 
        {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }

    // SETTERS ////////////
    public function setId($var)            { $var = (int) $var; $this->_id = $var; }
    public function setPostId($var)        { $var = (int) $var; $this->_postId = $var; }
    public function setUserId($var)        { $var = (int) $var; $this->_userId = $var; }
    public function setUserName($var)      { $var = (string) $var;  $this->_userName = $var; }
    public function setCommentContent($var){ $var = (string) $var;  $this->_content = $var; }
    public function setDatePosted($var)    { $this->_datePosted = $var; }
    public function setDateEdited($var)    { $this->_dateEdited = $var; }
    public function setLikes($var)         { $var = $var; $this->_likes = $var; }
    public function setWhoLikedId($var)    { $var = (int) $var; $this->_whoLikedId = $var; }
    public function setReports($var)       { $var = (int) $var; $this->_reports = $var; }
    /////////////////////////

    // GETTERS ////////////
    public function getId()                { return $this->_id; }
    public function getPostID()            { return $this->_postId; }
    public function getUserID()            { return $this->_userId; }
    public function getUserName()          { return $this->_userName; }
    public function getCommentContent()    { return $this->_content; }
    public function getDatePosted()        { return $this->_datePosted; }
    public function getDateEdited()        { return $this->_dateEdited; }
    public function getLikes()             { return $this->_likes; }
    public function getWhoLikedId()        { return $this->_whoLikedId; }
    public function getReports()           { return $this->_reports; }
    /////////////////////////
}