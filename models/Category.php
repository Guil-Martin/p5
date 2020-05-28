<?php
class Category
{
    protected
    $_ID,
    $_name;

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
    public function setID($var)     { $var = (int) $var; $this->_ID = $var; }
    public function setName($var)   { $var = (string) $var; $this->_name = $var; }
   /////////////////////////

    // GETTERS ////////////
    public function getID()         { return $this->_ID; }
    public function getName()       { return $this->_name; }
  /////////////////////////
}