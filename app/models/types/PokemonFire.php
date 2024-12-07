<?php

class PokemonFire extends Pokemon
{
    public function __construct($name, $hp, $atk, $def, $attacks)
    {
        parent::__construct($name, "Fire", $hp, $atk, $def, $attacks);
    }

    // Méthode pour obtenir le multiplicateur de type
    private function getMultiplier(Pokemon $adversaire)
    {
        $multiplicateurs = [
            "Steel" => 2,
            "Fighting" => 1,
            "Dragon" => 0.5,
            "Water" => 0.5,
            "Electric" => 1,
            "Fairy" => 1,
            "Fire" => 0.5,
            "Ice" => 2,
            "Bug" => 2,
            "Normal" => 1,
            "Grass" => 2,
            "Poison" => 1,
            "Psychic" => 1,
            "Rock" => 0.5,
            "Ground" => 1,
            "Ghost" => 1,
            "Dark" => 1,
            "Flying" => 1
        ];

        $typeAdversaire = $adversaire->getType();
        return $multiplicateurs[$typeAdversaire] ?? 1;
    }

    // Méthode pour générer le message en fonction du multiplicateur
    private function generateMessage(Pokemon $adversaire, $multiplier)
    {
        $messages = [
            'super_efficace' => "{$this->name} utilise Lance-Flammes ! C'est super efficace contre {$adversaire->name} !<br>",
            'peu_efficace' => "{$this->name} utilise Lance-Flammes, mais ce n'est pas très efficace contre {$adversaire->name}...<br>",
            'aucun_effet' => "{$this->name} utilise Lance-Flammes, mais ça n'a aucun effet sur {$adversaire->name}...<br>",
            'normale' => "{$this->name} utilise Lance-Flammes contre {$adversaire->name}.<br>"
        ];

        if ($multiplier > 1) {
            return $messages['super_efficace'];
        } elseif ($multiplier < 1) {
            return $multiplier === 0 ? $messages['aucun_effet'] : $messages['peu_efficace'];
        }

        return $messages['normale'];
    }

    public function capaciteSpeciale(Pokemon $adversaire)
    {
        // Obtient le multiplicateur en fonction du type de l'adversaire
        $multiplier = $this->getMultiplier($adversaire);

        // Génère le message à afficher
        $message = $this->generateMessage($adversaire, $multiplier);

        // Calcul des dégâts et application
        $degatsInfliges = (int)($this->atk * $multiplier);
        $damageMessage = $adversaire->takeDamage($degatsInfliges);

        // Retourne le message de la capacité spéciale et le message des dégâts
        return $message . $damageMessage;
    }
}
