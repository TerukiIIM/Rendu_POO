<?php

class Attack
{
    private $name;
    private $type;
    private $power;
    private $precision;

    public function __construct($name, $type, $power, $precision)
    {
        $this->name = $name;
        $this->type = $type;
        $this->power = $power;
        $this->precision = $precision;
    }

    // Getters
    // Get Name
    public function getName()
    {
        return $this->name;
    }

    // Get Type
    public function getType()
    {
        return $this->type;
    }

    // Get Power
    public function getPower()
    {
        return $this->power;
    }

    // Get Precision
    public function getPrecision()
    {
        return $this->precision;
    }

    // Exécuter l'attaque
    public function executeAttack($target)
    {
        // Applique les dégâts au pokémon cible
        $target->takeDamage($this->power);
    }
}
