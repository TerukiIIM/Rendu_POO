<?php

class BattleController
{
    use Render;

    // Méthode pour afficher la page de combat initiale
    public function index()
    {
        // Vérifie si un Pokémon a été sélectionné, sinon redirige vers la page de sélection
        if (!isset($_SESSION['selected_pokemon'])) {
            header('Location: /home/selectPokemon');
            exit;
        }

        // Vérifie si le Pokémon sélectionné a des PV, sinon redirige vers la page de soins
        if ($_SESSION['selected_pokemon']['stats']['hp'] <= 0) {
            header('Location: /heal');
            exit;
        }

        // Réinitialise les données de combat dans la session
        unset($_SESSION['fight'], $_SESSION['trainer1'], $_SESSION['trainer2'], $_SESSION['logs']);

        // Récupère les informations du Pokémon sélectionné
        $selectedPokemon = $_SESSION['selected_pokemon'];
        $pokemonType = 'Pokemon' . ucfirst($selectedPokemon['types'][0]);
        $pokemon1 = new $pokemonType(
            $selectedPokemon['name'],
            $selectedPokemon['stats']['hp'],
            $selectedPokemon['stats']['attack'],
            $selectedPokemon['stats']['defense'],
            $selectedPokemon['moves'] ?? ['Charge'],
            $selectedPokemon['id']
        );

        // Récupère un Pokémon aléatoire pour l'adversaire
        $pokemon2 = $this->getRandomPokemon();

        // Crée les dresseurs avec leurs Pokémon respectifs
        $trainer1 = new Trainer("Red", [$pokemon1]);
        $trainer2 = new Trainer("Blue", [$pokemon2]);

        // Initialise et démarre le combat
        $fight = new Fight($pokemon1, $pokemon2);
        $fight->startFight();

        // Sauvegarde les données de combat dans la session
        $_SESSION['fight'] = $fight;
        $_SESSION['trainer1'] = $trainer1;
        $_SESSION['trainer2'] = $trainer2;
        $_SESSION['logs'] = $fight->getBattleLogs();

        // Affiche la vue de combat
        $this->renderView('battle/index', [
            'title' => 'Combat Pokémon',
            'trainer1' => $trainer1,
            'trainer2' => $trainer2,
            'logs' => $fight->getBattleLogs()
        ]);
    }

    // Méthode pour gérer les attaques pendant le combat
    public function attack()
    {
        // Vérifie si un combat est en cours, sinon redirige vers la page de combat
        if (!isset($_SESSION['fight'])) {
            header('Location: /battle');
            exit;
        }

        // Récupère les données de combat depuis la session
        $fight = $_SESSION['fight'];
        $trainer1 = $_SESSION['trainer1'];
        $trainer2 = $_SESSION['trainer2'];

        $pokemon1 = $trainer1->getCurrentPokemon();
        $pokemon2 = $trainer2->getCurrentPokemon();

        // Si une attaque est envoyée via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Avant attaque - Joueur HP: " . $pokemon1->getHp() . " DEF: " . $pokemon1->getDef());
            error_log("Avant attaque - Adversaire ATK: " . $pokemon2->getAtk());

            // Récupère le nom de l'attaque ou utilise "Charge" par défaut
            $attackName = $_POST['action'] ?? 'Charge';
            $message = $pokemon1->attack($pokemon2);
            $fight->addBattleLog($message);

            // Vérifie si le Pokémon 2 est KO
            if ($pokemon2->isKO()) {
                $fight->addBattleLog("⚔️ {$pokemon2->getName()} est KO !");
                $fight->addBattleLog("🏆 {$trainer1->getName()} remporte le combat !");
                $_SESSION['fight'] = $fight;
                $_SESSION['logs'] = $fight->getBattleLogs();
                $_SESSION['selected_pokemon']['stats']['hp'] = $pokemon1->getHp();
                $_SESSION['selected_pokemon']['stats']['max_hp'] = $pokemon1->getMaxHp();

                // Affiche la vue de fin de combat
                return $this->renderView('battle/index', [
                    'title' => 'Combat Pokémon',
                    'trainer1' => $trainer1,
                    'trainer2' => $trainer2,
                    'logs' => $fight->getBattleLogs(),
                    'gameOver' => true,
                    'winner' => $trainer1->getName()
                ]);
            }

            // Le Pokémon 2 utilise sa capacité spéciale
            $message = $pokemon2->capaciteSpeciale($pokemon1);
            $fight->addBattleLog($message);

            error_log("Après attaque - Joueur HP: " . $pokemon1->getHp() . " DEF: " . $pokemon1->getDef());

            // Vérifie si le Pokémon 1 est KO
            if ($pokemon1->isKO()) {
                $fight->addBattleLog("⚔️ {$pokemon1->getName()} est KO !");
                $fight->addBattleLog("🏆 {$trainer2->getName()} remporte le combat !");
                $_SESSION['fight'] = $fight;
                $_SESSION['logs'] = $fight->getBattleLogs();
                $_SESSION['selected_pokemon']['stats']['hp'] = $pokemon1->getHp();
                $_SESSION['selected_pokemon']['stats']['max_hp'] = $pokemon1->getMaxHp();

                // Affiche la vue de fin de combat
                return $this->renderView('battle/index', [
                    'title' => 'Combat Pokémon',
                    'trainer1' => $trainer1,
                    'trainer2' => $trainer2,
                    'logs' => $fight->getBattleLogs(),
                    'gameOver' => true,
                    'winner' => $trainer2->getName()
                ]);
            }

            // Sauvegarde les données de combat dans la session
            $_SESSION['fight'] = $fight;
            $_SESSION['trainer1'] = $trainer1;
            $_SESSION['trainer2'] = $trainer2;
            $_SESSION['logs'] = $fight->getBattleLogs();
        }

        // Affiche la vue de combat
        $this->renderView('battle/index', [
            'title' => 'Combat Pokémon',
            'trainer1' => $trainer1,
            'trainer2' => $trainer2,
            'logs' => $fight->getBattleLogs(),
            'gameOver' => false
        ]);
    }

    // Méthode pour obtenir un Pokémon aléatoire
    private function getRandomPokemon()
    {
        try {
            // Récupère une liste de Pokémon depuis l'API
            $allPokemon = json_decode(file_get_contents('https://pokeapi.co/api/v2/pokemon?limit=100'), true)['results'];

            // Extrait les IDs des Pokémon
            $pokemonIds = array_map(function ($pokemon) {
                preg_match('/\/pokemon\/(\d+)\//', $pokemon['url'], $matches);
                return (int)$matches[1];
            }, $allPokemon);

            // Sélectionne un ID aléatoire et récupère les données du Pokémon correspondant
            $randomId = $pokemonIds[array_rand($pokemonIds)];
            $pokemonData = json_decode(file_get_contents("https://pokeapi.co/api/v2/pokemon/{$randomId}"), true);

            // Détermine le type et crée une instance de la classe correspondante
            $type = ucfirst($pokemonData['types'][0]['type']['name']);
            $className = "Pokemon{$type}";

            $hp = $pokemonData['stats'][0]['base_stat'];
            $attack = $pokemonData['stats'][1]['base_stat'];
            $defense = $pokemonData['stats'][2]['base_stat'];

            error_log("Pokemon adverse - ATK: $attack, DEF: $defense");

            // Crée une instance du Pokémon avec les données récupérées
            $pokemon = new $className($pokemonData['name'], $hp, $attack, $defense, ["Charge"], $randomId);

            // Sauvegarde les sprites du Pokémon dans la session
            $_SESSION['opponent_sprites'] = [
                'front_default' => $pokemonData['sprites']['front_default']
            ];

            return $pokemon;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return new PokemonNormal("Evoli", 100, 55, 50, ["Charge"], 133);
        }
    }

    // Méthode pour terminer le combat
    public function endBattle($trainer1, $trainer2, $fight)
    {
        $pokemon1 = $trainer1->getCurrentPokemon();
        $pokemon2 = $trainer2->getCurrentPokemon();

        // Vérifie si l'un des Pokémon est KO
        if ($pokemon1->isKO() || $pokemon2->isKO()) {
            if ($pokemon1->isKO()) {
                // Ajoute des logs de combat indiquant que le Pokémon 1 est KO et que le dresseur 2 a gagné
                $fight->addBattleLog("{$pokemon1->getName()} est KO !");
                $fight->addBattleLog("{$trainer2->getName()} remporte le combat !");
            } else {
                // Ajoute des logs de combat indiquant que le Pokémon 2 est KO et que le dresseur 1 a gagné
                $fight->addBattleLog("{$pokemon2->getName()} est KO !");
                $fight->addBattleLog("{$trainer1->getName()} remporte le combat !");
            }

            // Met à jour les PV actuels et max du Pokémon 1 dans la session
            $_SESSION['selected_pokemon']['stats']['hp'] = $pokemon1->getHp();
            $_SESSION['selected_pokemon']['stats']['max_hp'] = $pokemon1->getMaxHp();

            // Sauvegarde l'état du combat et les logs dans la session
            $_SESSION['fight'] = $fight;
            $_SESSION['logs'] = $fight->getBattleLogs();

            // Redirige vers la page de soins si le Pokémon 1 a perdu des PV
            if ($pokemon1->getHp() < $pokemon1->getMaxHp()) {
                header('Location: /heal');
                exit;
            }

            // Affiche la vue de fin de combat avec les informations pertinentes
            return $this->renderView('battle/index', [
                'title' => 'Combat Pokémon',
                'trainer1' => $trainer1,
                'trainer2' => $trainer2,
                'logs' => $fight->getBattleLogs(),
                'gameOver' => true,
                'winner' => $pokemon1->isKO() ? $trainer2->getName() : $trainer1->getName()
            ]);
        }

        // Sauvegarde l'état du combat dans la session
        $_SESSION['fight'] = $fight;
    }
}
