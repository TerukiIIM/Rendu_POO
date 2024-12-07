<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification et initialisation des données
$selectedPokemon = $_SESSION['selected_pokemon'] ?? null;
$sprites = $selectedPokemon['sprites'] ?? ['front_default' => '', 'back_default' => ''];
$moves = $selectedPokemon['moves'] ?? ['Charge'];
$currentHp = $selectedPokemon['current_hp'] ?? $selectedPokemon['stats']['hp'];
$maxHp = $selectedPokemon['stats']['max_hp'] ?? $selectedPokemon['stats']['hp'];
?>

<section class="battle-container">
    <div class="background-image"></div>
    <div class="menu-overlay"></div>

    <div class="content">
        <div class="battle-scene">
            <!-- Adversaire -->
            <div class="enemy-section">
                <div class="pokemon-info enemy">
                    <div class="info-box">
                        <h3><?= $trainer2->getCurrentPokemon()->getName() ?></h3>
                        <div class="health-container">
                            <div class="health-bar">
                                <div class="health-fill" style="width: <?= ($trainer2->getCurrentPokemon()->getHp() / $trainer2->getCurrentPokemon()->getMaxHp()) * 100 ?>%"></div>
                            </div>
                            <div class="health-text"><?= $trainer2->getCurrentPokemon()->getHp() ?>/<?= $trainer2->getCurrentPokemon()->getMaxHp() ?> PV</div>
                        </div>
                    </div>
                </div>
                <img src="<?= $_SESSION['opponent_sprites']['front_default'] ?>"
                    alt="<?= $trainer2->getCurrentPokemon()->getName() ?>"
                    class="pokemon-sprite enemy">
            </div>

            <!-- Joueur -->
            <div class="player-section">
                <div class="pokemon-info player">
                    <div class="info-box">
                        <h3><?= $selectedPokemon['name'] ?></h3>
                        <div class="health-container">
                            <div class="health-bar">
                                <div class="health-fill" style="width: <?= ($currentHp / $maxHp) * 100 ?>%"></div>
                            </div>
                            <div class="health-text"><?= $currentHp ?>/<?= $maxHp ?> PV</div>
                        </div>
                    </div>
                </div>
                <img src="<?= $sprites['back_default'] ?>"
                    alt="<?= $selectedPokemon['name'] ?>"
                    class="pokemon-sprite player">
            </div>
        </div>

        <!-- Interface de combat -->
        <div class="battle-interface">
            <div class="battle-log-container">
                <div class="battle-log">
                    <?php foreach ($logs as $log): ?>
                        <p><?= $log ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if ($trainer1->getCurrentPokemon()->isKO() || $trainer2->getCurrentPokemon()->isKO()): ?>
                <div class="battle-result">
                    <a href="/" class="menu-button">Retour à l'accueil</a>
                </div>
            <?php else: ?>
                <div class="battle-actions">
                    <form action="/battle/attack" method="POST" class="battle-form">
                        <div class="action-buttons">
                            <?php foreach ($moves as $move): ?>
                                <button type="submit" name="action" value="<?= $move ?>" class="menu-button">
                                    <?= ucfirst($move) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    .battle-container {
        position: fixed;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        font-family: 'Press Start 2P', cursive;
    }

    .background-image {
        position: fixed;
        inset: 0;
        background: url('https://moewalls.com/wp-content/uploads/2023/06/rayquaza-flying-in-the-clouds-pokemon-thumb.jpg') center/cover no-repeat;
        filter: brightness(0.9);
    }

    .menu-overlay {
        position: fixed;
        inset: 0;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.1) 100%);
    }

    .content {
        position: relative;
        z-index: 2;
        width: 100%;
        height: 100%;
        padding: 2rem;
        box-sizing: border-box;
    }

    .battle-scene {
        height: 60%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .enemy-section,
    .player-section {
        display: flex;
        align-items: center;
        padding: 1rem;
    }

    .enemy-section {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .player-section {
        align-self: flex-start;
    }

    .pokemon-info {
        background: white;
        border: 3px solid #333;
        border-radius: 10px;
        padding: 1rem;
        margin: 0 1rem;
        min-width: 250px;
    }

    .info-box h3 {
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 1rem;
        text-transform: capitalize;
    }

    .health-container {
        width: 100%;
    }

    .health-bar {
        width: 100%;
        height: 12px;
        background: #ccc;
        border: 2px solid #333;
        border-radius: 6px;
        overflow: hidden;
    }

    .health-fill {
        height: 100%;
        background: linear-gradient(to right, #ff0000 0%, #00ff00 100%);
        transition: width 0.5s ease;
    }

    .health-text {
        font-size: 0.8rem;
        color: #333;
        text-align: right;
        margin-top: 0.2rem;
    }

    .pokemon-sprite {
        width: 200px;
        height: 200px;
        image-rendering: pixelated;
        filter: drop-shadow(2px 4px 6px rgba(0, 0, 0, 0.3));
    }

    .pokemon-sprite.enemy {
        animation: bounce 2s infinite;
    }

    .pokemon-sprite.player {
        animation: bounce 2s infinite reverse;
    }

    .battle-interface {
        background: rgba(255, 255, 255, 0.9);
        border-top: 3px solid #333;
        padding: 1rem;
        height: 40%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .battle-log-container {
        flex: 1;
        overflow-y: auto;
        margin-bottom: 1rem;
    }

    .battle-log {
        padding: 1rem;
        background: white;
        border: 2px solid #333;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 1rem;
    }

    .menu-button {
        background: #ffcb05;
        border: 3px solid #2a75bb;
        color: #2a75bb;
        padding: 1rem;
        font-size: 0.9rem;
        font-family: 'Press Start 2P', cursive;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        border-radius: 5px;
    }

    .menu-button:hover {
        background: #ffd83d;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .battle-result {
        text-align: center;
        padding: 2rem;
        animation: fadeIn 0.5s ease;
    }
</style>