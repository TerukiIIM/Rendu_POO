<?php

class Fight
{
    private Pokemon $pokemon1;
    private Pokemon $pokemon2;
    private array $battleLogs = [];
    private bool $isStarted = false;

    public function __construct($pokemon1, $pokemon2)
    {
        $this->pokemon1 = $pokemon1;
        $this->pokemon2 = $pokemon2;
    }

    // Démarrer le combat
    public function startFight()
    {
        $this->isStarted = true;
        $this->addBattleLog("Le combat entre {$this->pokemon1->getName()} et {$this->pokemon2->getName()} commence !");
    }

    // Tour de l'attaquant
    public function attackerTurn(Pokemon $attacker, Pokemon $defender)
    {
        $attackMessage = $attacker->attack($defender);
        $this->addBattleLog($attackMessage);

        $damageMessage = $defender->takeDamage($attacker->getAtk());
        $this->addBattleLog($damageMessage);

        // Vérifier si le défenseur est KO
        if ($defender->isKO()) {
            $this->addBattleLog("{$defender->getName()} est KO !");
            return;
        }

        // Si le défenseur est l'IA, c'est son tour d'attaquer
        if ($defender === $this->pokemon2) {
            $this->iaTurn();
        }
    }

    // Tour de l'IA
    public function iaTurn()
    {
        // L'IA attaque automatiquement
        $this->attackerTurn($this->pokemon2, $this->pokemon1);
    }

    // Obtenir le gagnant
    public function getWinner()
    {
        if ($this->pokemon1->getHp() <= 0) {
            return "{$this->pokemon2->getName()} a gagné le combat !";
        } elseif ($this->pokemon2->getHp() <= 0) {
            return "{$this->pokemon1->getName()} a gagné le combat !";
        } else {
            return null;
        }
    }

    // Obtenir les logs de combat
    public function getBattleLogs()
    {
        return $this->battleLogs;
    }

    // Ajouter un log de combat
    public function addBattleLog($log)
    {
        $this->battleLogs[] = $log;
    }
}
