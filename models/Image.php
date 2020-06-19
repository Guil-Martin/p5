<?php
class Image
{
    protected 
    $_id,
    $_author,
    $_userId,
    $_title,
    $_imgPath,
    $_thumbPath,
    $_category,
    $_excerpt,
    $_content,
    $_thumbnail,
    $_image,
    $_views,
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
    public function setImgTitle($var)      { $var = (string) $var;  $this->_title = $var; }
    public function setImgPath($var)       { $var = (string) $var;  $this->_imgPath = $var; }
    public function setImgThumbnail($var)  { $var = (string) $var;  $this->_thumbPath = $var; }
    public function setCategory($var)      { $var = (string) $var;  $this->_category = $var; }
    public function setExcerpt($var)       { $var = (string) $var;  $this->_excerpt = $var; }
    public function setImgContent($var)    { $var = (string) $var;  $this->_content = $var; }
    public function setImage($var)         { $var = (string) $var;  $this->_image = $var; }
    public function setViews($var)         { $var = (int) $var; $this->_views = $var; }
    public function setLikes($var)         { $var = (int) $var; $this->_likes = $var; }
    public function setComments($var)      { $var = (int) $var; $this->_numComments = $var; }
    public function setDatePosted($var)    { $this->_datePosted = $var; }
    public function setDateEdited($var)    { $this->_dateEdited = $var; }
    /////////////////////////

    // GETTERS ////////////
    public function getId()                { return $this->_id; }
    public function getUserId()            { return $this->_userId; }
    public function getAuthor()            { return $this->_author; }
    public function getImgTitle()          { return $this->_title; }
    public function getImgPath()           { return $this->_imgPath; }
    public function getImgThumbnail()      { return $this->_thumbPath; }    
    public function getCategory()          { return $this->_category; }
    public function getExcerpt()           { return $this->_excerpt; }
    public function getImgContent()        { return $this->_content; }
    public function getImage()             { return $this->_image; }
    public function getViews()             { return $this->_views; }
    public function getLikes()             { return $this->_likes; }
    public function getComments()          { return $this->_numComments; }
    public function getDatePosted()        { return $this->_datePosted; }
    public function getDateEdited()        { return $this->_dateEdited; }
    /////////////////////////

}