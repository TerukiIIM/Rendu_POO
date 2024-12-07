<?php

class HealController
{
    use Render;

    // Méthode pour afficher la page de soins
    public function index()
    {
        // Récupère le Pokémon sélectionné depuis la session
        $pokemon = $_SESSION['selected_pokemon'] ?? null;

        // Vérifie si le Pokémon a besoin de soins
        if ($pokemon && $pokemon['stats']['hp'] < $pokemon['stats']['max_hp']) {
            // Affiche la vue de soins avec les informations du Pokémon
            $this->renderView('heal/index', [
                'title' => 'Centre Pokémon',
                'pokemon' => $pokemon
            ]);
        } else {
            // Affiche la vue de soins avec un message indiquant qu'aucun Pokémon n'a besoin de soins
            $this->renderView('heal/index', [
                'title' => 'Centre Pokémon',
                'message' => 'Aucun Pokémon n\'a besoin de soins.'
            ]);
        }
    }

    // Méthode pour soigner le Pokémon
    public function heal()
    {
        // Vérifie si un Pokémon est sélectionné dans la session
        if (isset($_SESSION['selected_pokemon'])) {
            // Restaure les PV du Pokémon au maximum
            $_SESSION['selected_pokemon']['stats']['hp'] = $_SESSION['selected_pokemon']['stats']['max_hp'];
            // Affiche la vue de soins avec un message indiquant que le Pokémon a été soigné
            $this->renderView('heal/index', [
                'title' => 'Centre Pokémon',
                'message' => 'Votre Pokémon a été soigné !'
            ]);
        } else {
            // Affiche la vue de soins avec un message indiquant qu'aucun Pokémon n'a été trouvé
            $this->renderView('heal/index', [
                'title' => 'Centre Pokémon',
                'message' => 'Aucun Pokémon trouvé.'
            ]);
        }
    }
}
