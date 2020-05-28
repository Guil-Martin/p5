<?php
class User
{
    protected
    $_id,
    $_contentId,
    $_privilege,
    $_name,
    $_password,
    $_email,
    $_dateRegistered,
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
    public function setId($var)             { $var = (int) $var; $this->_id = $var; }
    public function setContentId($var)      { $var = (string) $var;  $this->_contentId = $var; }
    public function setPrivilege($var)      { $var = (string) $var;  $this->_privilege = $var; }
    public function setUserName($var)       { $var = (string) $var;  $this->_name = $var; }
    public function setUserPassword($var)   { $var = (string) $var;  $this->_password = $var; }
    public function setUserMail($var)       { $var = (string) $var;  $this->_email = $var; }
    public function setDateRegistered($var) { $this->_dateRegistered = $var; }
    public function setReports($var)        { $var = (int) $var; $this->_reports = $var; }
    
    /////////////////////////

    // GETTERS ////////////
    public function getId()                { return $this->_id; }
    public function getContentId()         { return $this->_contentId; }
    public function getPrivilege()         { return $this->_privilege; }
    public function getUserName()          { return $this->_name; }
    public function getUserPassword()      { return $this->_password; }
    public function getUserMail()          { return $this->_email; }
    public function getDateRegistered()    { return $this->_dateRegistered; }
    public function getReports()           { return $this->_reports; }
    /////////////////////////
}