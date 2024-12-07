<section class="heal-container">
    <div class="background-image"></div>
    <div class="menu-overlay"></div>

    <div class="content">
        <h1 class="title">Centre Pokémon</h1>
        <p class="subtitle">Soignez votre Pokémon</p>

        <?php if (isset($pokemon)): ?>
            <div class="pokemon-card">
                <img src="<?= $pokemon['sprites']['front_default'] ?>" alt="<?= $pokemon['name'] ?>" class="pokemon-image">
                <h2 class="pokemon-name"><?= ucfirst($pokemon['name']) ?></h2>
                <div class="health-bar">
                    <div class="health-fill" style="width: <?= ($pokemon['stats']['hp'] / $pokemon['stats']['max_hp']) * 100 ?>%"></div>
                    <span class="health-text"><?= $pokemon['stats']['hp'] ?>/<?= $pokemon['stats']['max_hp'] ?> PV</span>
                </div>
                <form action="/heal/heal" method="POST">
                    <button type="submit" class="heal-button">Soigner</button>
                </form>
            </div>
        <?php else: ?>
            <div class="message-card">
                <p><?= $message ?? 'Aucun Pokémon n\'a besoin de soins.' ?></p>
            </div>
        <?php endif; ?>

        <a href="/" class="back-button">Retour à l'accueil</a>
    </div>
</section>

<style>
    .heal-container {
        position: fixed;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .background-image {
        position: fixed;
        inset: 0;
        background: url('https://moewalls.com/wp-content/uploads/2023/06/rayquaza-flying-in-the-clouds-pokemon-thumb.jpg') center/cover no-repeat;
        filter: brightness(0.7);
    }

    .menu-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .content {
        position: relative;
        z-index: 2;
        width: 90%;
        max-width: 600px;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        text-align: center;
    }

    .title {
        font-size: 2rem;
        color: #ffcb05;
        margin-bottom: 1rem;
    }

    .subtitle {
        color: #333;
        margin-bottom: 2rem;
    }

    .pokemon-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
    }

    .pokemon-image {
        width: 150px;
        height: 150px;
        margin-bottom: 1rem;
    }

    .pokemon-name {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .health-bar {
        background: #ccc;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .health-fill {
        height: 20px;
        background: linear-gradient(to right, #ff0000, #00ff00);
    }

    .health-text {
        font-size: 1rem;
        color: #333;
    }

    .heal-button {
        background: #ffcb05;
        border: none;
        padding: 1rem 2rem;
        border-radius: 10px;
        cursor: pointer;
        font-size: 1rem;
        color: #333;
        transition: background 0.3s;
    }

    .heal-button:hover {
        background: #ffd83d;
    }

    .message-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
    }

    .back-button {
        display: inline-block;
        margin-top: 1rem;
        padding: 1rem 2rem;
        background: #ffcb05;
        color: #333;
        border-radius: 10px;
        text-decoration: none;
        transition: background 0.3s;
    }

    .back-button:hover {
        background: #ffd83d;
    }
</style>