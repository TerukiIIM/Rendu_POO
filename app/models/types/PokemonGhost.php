<?php

class PokemonGhost extends Pokemon
{
    public function __construct($name, $hp, $atk, $def, $attacks)
    {
        parent::__construct($name, "Ghost", $hp, $atk, $def, $attacks);
    }

    // Méthode pour obtenir le multiplicateur de type
    private function getMultiplier(Pokemon $adversaire)
    {
        $multiplicateurs = [
            "Steel" => 1,
            "Fighting" => 1,
            "Dragon" => 1,
            "Water" => 1,
            "Electric" => 1,
            "Fairy" => 1,
            "Fire" => 1,
            "Ice" => 1,
            "Bug" => 1,
            "Normal" => 0,
            "Grass" => 1,
            "Poison" => 1,
            "Psychic" => 2,
            "Rock" => 1,
            "Ground" => 1,
            "Ghost" => 2,
            "Dark" => 0.5,
            "Flying" => 1
        ];

        $typeAdversaire = $adversaire->getType();
        return $multiplicateurs[$typeAdversaire] ?? 1;
    }

    // Méthode pour générer le message en fonction du multiplicateur
    private function generateMessage(Pokemon $adversaire, $multiplier)
    {
        $messages = [
            'super_efficace' => "{$this->name} utilise ... ! C'est super efficace contre {$adversaire->name} !<br>",
            'peu_efficace' => "{$this->name} utilise ..., mais ce n'est pas très efficace contre {$adversaire->name}...<br>",
            'aucun_effet' => "{$this->name} utilise ..., mais ça n'a aucun effet sur {$adversaire->name}...<br>",
            'normale' => "{$this->name} utilise ... contre {$adversaire->name}.<br>"
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
