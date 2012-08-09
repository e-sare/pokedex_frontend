<?php

class Application_Model_Pokemon
{
    protected $_id;
    protected $_name;
    protected $_nationalID;
    protected $_mainType;
    protected $_subtype;
    protected $_description;
    protected $_height;
    protected $_weight;
    protected $_hp;
    protected $_attack;
    protected $_defense;
    protected $_spAtk;
    protected $_spDef;
    protected $_speed;


    public function __construct(array $pkm_json = null){
        if(is_array($pkm_json)){
            $this->setMetaData($pkm_json['metadata']);
        }
    }

    public function setMetaData(array $pkm_meta_data){
        $methods = get_class_methods($this);
        foreach($pkm_meta_data as $k => $v){
            $method = 'set' . ucfirst($k);
            if(in_array($method, $methods)){
                $this->$method($v);
            }
        }
    }

    public function setAttack($attack)
    {
        $this->_attack = $attack;
    }

    public function getAttack()
    {
        return $this->_attack;
    }

    public function setDefense($defense)
    {
        $this->_defense = $defense;
    }

    public function getDefense()
    {
        return $this->_defense;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setHeight($height)
    {
        $this->_height = $height;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setHp($hp)
    {
        $this->_hp = $hp;
    }

    public function getHp()
    {
        return $this->_hp;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setMainType($mainType)
    {
        $this->_mainType = $mainType;
    }

    public function getMainType()
    {
        return $this->_mainType;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setNationalID($nationalID)
    {
        $this->_nationalID = $nationalID;
    }

    public function getNationalID()
    {
        return $this->_nationalID;
    }

    public function setSpAtk($spAtk)
    {
        $this->_spAtk = $spAtk;
    }

    public function getSpAtk()
    {
        return $this->_spAtk;
    }

    public function setSpDef($spDef)
    {
        $this->_spDef = $spDef;
    }

    public function getSpDef()
    {
        return $this->_spDef;
    }

    public function setSpeed($speed)
    {
        $this->_speed = $speed;
    }

    public function getSpeed()
    {
        return $this->_speed;
    }

    public function setSubtype($subtype)
    {
        $this->_subtype = $subtype;
    }

    public function getSubtype()
    {
        return $this->_subtype;
    }

    public function setWeight($weight)
    {
        $this->_weight = $weight;
    }

    public function getWeight()
    {
        return $this->_weight;
    }
}

