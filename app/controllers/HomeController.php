<?php

class HomeController
{
    use Render;

    // Méthode pour afficher la page d'accueil
    public function index()
    {
        try {
            // Prépare les données pour la vue
            $data = [
                "title" => "Page d'accueil Pokémon",
                "message" => "Bienvenue dans le monde Pokémon !"
            ];

            // Affiche la vue de la page d'accueil
            $this->renderView("home/index", $data);
        } catch (\Exception $e) {
            // En cas d'erreur, enregistre le message d'erreur et affiche la vue d'erreur
            error_log($e->getMessage());
            $this->renderView("error/index", ["message" => $e->getMessage()]);
        }
    }

    // Méthode pour soigner le Pokémon
    public function heal()
    {
        // Vérifie si un Pokémon est sélectionné dans la session
        if (!isset($_SESSION['selected_pokemon'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Aucun Pokémon trouvé']);
            exit;
        }

        try {
            // Restaure les PV du Pokémon au maximum
            $_SESSION['selected_pokemon']['current_hp'] = $_SESSION['selected_pokemon']['stats']['max_hp'];

            // Retourne une réponse JSON indiquant que le Pokémon a été soigné
            echo json_encode([
                'success' => true,
                'message' => 'Votre Pokémon a été soigné !',
                'currentHp' => $_SESSION['selected_pokemon']['current_hp'],
                'maxHp' => $_SESSION['selected_pokemon']['stats']['max_hp']
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur, retourne une réponse JSON avec le message d'erreur
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Méthode pour sélectionner un Pokémon
    public function selectPokemon()
    {
        // Si la requête est de type POST, traite la sélection du Pokémon
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['pokemonName'])) {
                return $this->storePokemon($data['pokemonName']);
            }
        }

        // Affiche la vue de sélection de Pokémon
        $this->renderView("home/selectPokemon", [
            "title" => "Changer de Pokémon",
            "message" => "Sélectionnez un nouveau Pokémon."
        ]);
    }

    // Méthode pour stocker le Pokémon sélectionné dans la session
    private function storePokemon($pokemonName)
    {
        try {
            // Récupère les données du Pokémon depuis l'API
            $url = "https://pokeapi.co/api/v2/pokemon/" . strtolower($pokemonName);
            $pokemonData = json_decode(file_get_contents($url), true);

            // Stocke les données du Pokémon dans la session
            $_SESSION['selected_pokemon'] = [
                'name' => $pokemonData['name'],
                'types' => array_map(function ($type) {
                    return $type['type']['name'];
                }, $pokemonData['types']),
                'stats' => [
                    'hp' => $pokemonData['stats'][0]['base_stat'],
                    'max_hp' => $pokemonData['stats'][0]['base_stat'],
                    'attack' => $pokemonData['stats'][1]['base_stat'],
                    'defense' => $pokemonData['stats'][2]['base_stat']
                ],
                'sprites' => [
                    'front_default' => $pokemonData['sprites']['front_default'],
                    'back_default' => $pokemonData['sprites']['back_default'],
                    'front_shiny' => $pokemonData['sprites']['front_shiny'],
                    'back_shiny' => $pokemonData['sprites']['back_shiny'],
                    'official_artwork' => $pokemonData['sprites']['other']['official-artwork']['front_default']
                ],
                'sprite' => $pokemonData['sprites']['front_default'],
                'id' => $pokemonData['id']
            ];

            // Retourne une réponse JSON indiquant que le Pokémon a été sélectionné avec succès
            echo json_encode(['success' => true]);
            exit;
        } catch (\Exception $e) {
            // En cas d'erreur, retourne une réponse JSON avec le message d'erreur
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    // Méthode pour sélectionner les attaques d'un Pokémon
    public function selectAttacks()
    {
        // Vérifie si un Pokémon est sélectionné dans la session
        if (!isset($_SESSION['selected_pokemon'])) {
            header('Location: /home/selectPokemon');
            exit;
        }

        try {
            // Récupère les données du Pokémon sélectionné
            $pokemon = $_SESSION['selected_pokemon'];
            $moves = $this->getPokemonMoves($pokemon['name']);

            // Affiche la vue de sélection des attaques
            $this->renderView("home/selectAttacks", [
                "title" => "Changer les Attaques",
                "message" => "Sélectionnez les attaques pour " . ucfirst($pokemon['name']),
                "pokemon" => $pokemon,
                "moves" => $moves
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur, affiche la vue d'erreur
            $this->renderView("error/index", ["message" => $e->getMessage()]);
        }
    }

    // Méthode pour récupérer les attaques d'un Pokémon depuis l'API
    private function getPokemonMoves($pokemonName)
    {
        $url = "https://pokeapi.co/api/v2/pokemon/" . strtolower($pokemonName);
        $pokemonData = json_decode(file_get_contents($url), true);

        $moves = [];
        foreach ($pokemonData['moves'] as $moveData) {
            $moveUrl = $moveData['move']['url'];
            $moveDetails = json_decode(file_get_contents($moveUrl), true);

            $moves[] = [
                'name' => $moveDetails['name'],
                'type' => $moveDetails['type']['name'],
                'power' => $moveDetails['power'],
                'accuracy' => $moveDetails['accuracy'],
                'description' => $this->getMoveDescription($moveDetails)
            ];
        }

        return $moves;
    }

    // Méthode pour récupérer la description d'une attaque
    private function getMoveDescription($moveDetails)
    {
        foreach ($moveDetails['effect_entries'] as $effect) {
            if ($effect['language']['name'] === 'en') {
                return $effect['effect'];
            }
        }
        return "No description available.";
    }

    // Méthode pour sauvegarder les attaques sélectionnées
    public function saveAttacks()
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data || !isset($data['moves'])) {
                throw new Exception('Données invalides');
            }

            $moves = $data['moves'];

            if (empty($moves)) {
                throw new Exception('Aucune attaque sélectionnée');
            }

            // Sauvegarde les attaques sélectionnées dans la session
            $_SESSION['selected_pokemon']['moves'] = $moves;

            // Retourne une réponse JSON indiquant que les attaques ont été sauvegardées avec succès
            echo json_encode([
                'success' => true,
                'message' => 'Attaques sauvegardées avec succès'
            ]);
            exit;
        } catch (Exception $e) {
            // En cas d'erreur, retourne une réponse JSON avec le message d'erreur
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    // Méthode pour obtenir le Pokémon actuellement sélectionné
    public function getCurrentPokemon()
    {
        if (isset($_SESSION['selected_pokemon'])) {
            // Retourne une réponse JSON avec les données du Pokémon sélectionné
            echo json_encode([
                'success' => true,
                'pokemon' => $_SESSION['selected_pokemon']
            ]);
        } else {
            // Retourne une réponse JSON indiquant qu'aucun Pokémon n'est sélectionné
            echo json_encode([
                'success' => false,
                'error' => 'No Pokemon selected'
            ]);
        }
        exit;
    }
}
