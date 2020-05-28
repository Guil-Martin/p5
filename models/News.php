<?php
class News
{
    protected 
    $_id,
    $_author,
    $_userId,
    $_title,
    $_category,
    $_excerpt,
    $_content,
    $_thumbnail,
    $_image,
    $_likes,
    $_numComments,
    $_datePosted,
    $_dateEdited;

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
    public function setId($var)            { $var = (int) $var;     $this->_id = $var; }
    public function setUserId($var)        { $var = (int) $var;     $this->_userId = $var; }
    public function setAuthor($var)        { $var = (string) $var;  $this->_author = $var; }
    public function setNewsTitle($var)     { $var = (string) $var;  $this->_title = $var; }
    public function setCategory($var)      { $var = (string) $var;  $this->_category = $var; }
    public function setExcerpt($var)       { $var = (string) $var;  $this->_excerpt = $var; }
    public function setNewsContent($var)   { $var = (string) $var;  $this->_content = $var; }
    public function setImage($var)         { $var = (string) $var;  $this->_image = $var; }
    public function setLikes($var)         { $var = (int) $var; $this->_likes = $var; }
    public function setNumComments($var)   { $var = (int) $var;     $this->_numComments = $var; }
    public function setDatePosted($var)    { $this->_datePosted = $var; }
    public function setDateEdited($var)    { $this->_dateEdited = $var; }
    /////////////////////////

    // GETTERS ////////////
    public function getId()                { return $this->_id; }
    public function getUserId()            { return $this->_userId; }
    public function getAuthor()            { return $this->_author; }
    public function getNewsTitle()         { return $this->_title; }
    public function getCategory()          { return $this->_category; }
    public function getExcerpt()           { return $this->_excerpt; }
    public function getNewsContent()       { return $this->_content; }
    public function getImage()             { return $this->_image; }
    public function getLikes()             { return $this->_likes; }
    public function getNumComments()       { return $this->_numComments; }
    public function getDatePosted()        { return $this->_datePosted; }
    public function getDateEdited()        { return $this->_dateEdited; }
    /////////////////////////

    // public function move($var) { $this->_loc = $var; }
}