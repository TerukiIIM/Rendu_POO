<?php

abstract class Pokemon implements Fighter
{
    use Heal;

    protected string $name;
    protected string $type;
    protected int $hp;
    protected int $max_hp;
    protected int $atk;
    protected int $def;
    protected array $attacks;

    public function __construct($name, $type, $hp, $atk, $def, $attacks)
    {
        $this->name = $name;
        $this->type = $type;
        $this->hp = $hp;
        $this->max_hp = $hp;
        $this->atk = $atk;
        $this->def = $def;
        $this->attacks = $attacks;
    }

    // Getters & Setters
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

    // Get HP
    public function getHp()
    {
        return $this->hp;
    }

    // Get Max HP
    public function getMaxHp()
    {
        return $this->max_hp;
    }

    // Set HP
    public function setHp($hp): void
    {
        $this->hp = min($hp, $this->max_hp);
    }

    // Get Atk
    public function getAtk()
    {
        return $this->atk;
    }

    // Get Def
    public function getDef()
    {
        return $this->def;
    }

    // Methodes
    public function attack(Pokemon $target)
    {
        // Calcul des dégâts de base
        $damage = max(0, $this->atk - $target->def);

        // Appliquer les dégâts à l'target
        $damageMessage = $target->takeDamage($damage);

        // Retourne le message d'attaque
        return "{$this->name} attaque {$target->name} et inflige {$damage} dégâts !<br>" . $damageMessage;
    }

    public function takeDamage(int $damage)
    {
        // Réduit les points de vie en fonction des dégâts reçus
        $this->hp = max(0, $this->hp - $damage);

        // Affichage du message indiquant les dégâts subis
        return "{$this->name} reçoit {$damage} dégâts ! Points de vie restants : {$this->hp}<br>";
    }

    public function isKO()
    {
        // Retourne un booléen, vrai si le Pokemon est KO
        return $this->hp <= 0;
    }

    // Méthode abstraite à implémenter dans les sous-classes.
    abstract public function capaciteSpeciale(Pokemon $target);

    // Méthodes de l'interface Fighter
    public function getFight(Pokemon $target)
    {
        return $this->attack($target);
    }

    public function useSpeAtk(Pokemon $target)
    {
        return $this->capaciteSpeciale($target);
    }
}
