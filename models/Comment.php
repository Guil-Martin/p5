<?php
class Comment
{
    protected
    $_id,
    $_postId,
    $_userName,
    $_content,
    $_datePosted,
    $_likes,
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
    public function setUserName($var)      { $var = (string) $var;  $this->_userName = $var; }
    public function setCommentContent($var){ $var = (string) $var;  $this->_content = $var; }
    public function setDatePosted($var)    { $this->_datePosted = $var; }
    public function setLikes($var)         { $var = (int) $var; $this->_likes = $var; }
    public function setReports($var)       { $var = (int) $var; $this->_reports = $var; }
    /////////////////////////

    // GETTERS ////////////
    public function getId()                { return $this->_id; }
    public function getPostID()            { return $this->_postId; }
    public function getUserName()          { return $this->_userName; }
    public function getCommentContent()    { return $this->_content; }
    public function getDatePosted()        { return $this->_datePosted; }
    public function getLikes()             { return $this->_likes; }
    public function getReports()           { return $this->_reports; }
    /////////////////////////
}