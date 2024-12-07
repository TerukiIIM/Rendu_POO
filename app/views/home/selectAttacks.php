<section class="change-attacks container">
    <div class="content">
        <h1 class="title">Sélectionner les Attaques</h1>
        <p class="subtitle">Choisissez 4 attaques pour votre Pokémon</p>

        <div class="pokemonAttacks">
            <div class="moves-column">
                <?php foreach ($moves as $move): ?>
                    <div class="move-card" onclick="selectMove('<?= $move['name'] ?>')">
                        <h3><?= ucfirst($move['name']) ?></h3>
                        <div class="move-type" style="background-color: <?= $typeColors[$move['type']] ?? '#A8A878' ?>">
                            <?= ucfirst($move['type']) ?>
                        </div>
                        <div class="move-stats">
                            <span>Puissance: <?= $move['power'] ?: 'N/A' ?></span>
                            <span>Précision: <?= $move['accuracy'] ?: 'N/A' ?>%</span>
                        </div>
                        <p class="move-description"><?= $move['description'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pokemon-column">
                <div class="pokemon-info">
                    <img src="<?= $pokemon['sprite'] ?>" alt="<?= $pokemon['name'] ?>" class="pokemon-image">
                    <h2 class="pokemon-name"><?= ucfirst($pokemon['name']) ?></h2>
                </div>
                <div id="selectedMovesCount"></div>
                <div id="confirmButtonContainer"></div>
            </div>
        </div>
    </div>
</section>

<style>
    .change-attacks.container {
        background: url('https://moewalls.com/wp-content/uploads/2023/06/rayquaza-flying-in-the-clouds-pokemon-thumb.jpg') center/cover fixed;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .content {
        background: rgba(0, 0, 0, 0.8);
        padding: 2rem;
        border-radius: 10px;
        max-width: 1200px;
        width: 90%;
    }

    .title {
        color: #ffcb05;
        font-size: 2rem;
        text-align: center;
        margin-bottom: 1rem;
    }

    .subtitle {
        color: #fff;
        text-align: center;
        margin-bottom: 2rem;
    }

    .pokemonAttacks {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    .moves-column {
        height: 70vh;
        overflow-y: auto;
    }

    .move-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 10px;
        cursor: pointer;
        transition: transform 0.3s, background 0.3s;
    }

    .move-card.selected {
        background: linear-gradient(45deg, #ffcb05 0%, #ffd700 100%);
    }

    .move-card:hover {
        transform: translateY(-5px);
    }

    .move-type {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        color: white;
    }

    .pokemon-column {
        position: sticky;
        top: 2rem;
    }

    .pokemon-info {
        text-align: center;
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        border-radius: 10px;
    }

    .pokemon-image {
        width: 150px;
        height: 150px;
        animation: float 3s infinite ease-in-out;
    }

    .confirm-moves-btn {
        width: 100%;
        padding: 1rem;
        background: #ffcb05;
        border: none;
        border-radius: 25px;
        margin-top: 1rem;
        cursor: pointer;
        transition: background 0.3s;
    }

    .confirm-moves-btn:hover {
        background: #ffd700;
    }

    .confirm-moves-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-controls {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .pagination-controls button {
        background: #ffcb05;
        color: #333;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .pagination-controls button:hover {
        background: #ffd700;
    }

    .pagination-controls button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-controls span {
        color: white;
        display: flex;
        align-items: center;
    }

    * {
        color: white;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }
</style>

<script>
    const MOVES_PER_PAGE = 10;
    let currentPage = 1;
    let allMoves = [];
    const selectedMoves = new Set();
    const maxMoves = 4;

    function initializePagination() {
        allMoves = Array.from(document.querySelectorAll('.move-card'));
        displayPage(1);
        renderPaginationControls();
    }

    function displayPage(page) {
        const start = (page - 1) * MOVES_PER_PAGE;
        const end = start + MOVES_PER_PAGE;

        allMoves.forEach((move, index) => {
            move.style.display = (index >= start && index < end) ? '' : 'none';
        });

        currentPage = page;
        renderPaginationControls(); // Re-render pagination controls on page change
    }

    function renderPaginationControls() {
        const totalPages = Math.ceil(allMoves.length / MOVES_PER_PAGE);
        const controls = document.createElement('div');
        controls.className = 'pagination-controls';

        controls.innerHTML = `
        <button ${currentPage === 1 ? 'disabled' : ''} onclick="displayPage(${currentPage - 1})">Précédent</button>
        <span>Page ${currentPage}/${totalPages}</span>
        <button ${currentPage === totalPages ? 'disabled' : ''} onclick="displayPage(${currentPage + 1})">Suivant</button>
    `;

        const paginationContainer = document.querySelector('.pagination-controls');
        if (paginationContainer) {
            paginationContainer.remove();
        }
        document.querySelector('.moves-column').appendChild(controls);
    }

    function selectMove(moveName) {
        const card = event.currentTarget;
        if (selectedMoves.has(moveName)) {
            selectedMoves.delete(moveName);
            card.classList.remove('selected');
        } else if (selectedMoves.size < maxMoves) {
            selectedMoves.add(moveName);
            card.classList.add('selected');
        }
        updateMoveCount();
    }

    function updateMoveCount() {
        document.getElementById('selectedMovesCount').innerHTML =
            `<div class="selected-count">${selectedMoves.size}/4 attaques sélectionnées</div>`;
        document.getElementById('confirmButtonContainer').innerHTML =
            `<button class="confirm-moves-btn" ${selectedMoves.size === 0 ? 'disabled' : ''} onclick="saveAttacks()">Confirmer</button>`;
    }

    function saveAttacks() {
        if (!selectedMoves.size) return;
        fetch('/home/saveAttacks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    moves: Array.from(selectedMoves)
                })
            })
            .then(r => r.json())
            .then(data => data.success ? window.location.href = '/' : Promise.reject(data.message))
            .catch(err => alert('Erreur: ' + err));
    }

    document.addEventListener('DOMContentLoaded', initializePagination);
</script>