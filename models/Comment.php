<?php
class Comment
{
    protected
    $_id,
    $_newsID,
    $_author,
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
    public function setNewsID($var)        { $var = (int) $var; $this->_newsID = $var; }
    public function setAuthor($var)        { $var = (string) $var;  $this->_author = $var; }
    public function setContent($var)       { $var = (string) $var;  $this->_content = $var; }
    public function setDatePosted($var)    { $this->_datePosted = $var; }
    public function setLikes($var)         { $var = (int) $var; $this->_likes = $var; }
    public function setReports($var)       { $var = (int) $var; $this->_reports = $var; }
    /////////////////////////

    // GETTERS ////////////
    public function getId()                { return $this->_id; }
    public function getNewsID()            { return $this->_newsID; }
    public function getAuthor()            { return $this->_author; }
    public function getContent()           { return $this->_content; }
    public function getDatePosted()        { return $this->_datePosted; }
    public function getLikes()             { return $this->_likes; }
    public function getReports()           { return $this->_reports; }
    /////////////////////////
}