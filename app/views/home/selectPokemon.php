<section class="change-pokemon container">
    <div class="background-image"></div>
    <div class="menu-overlay"></div>

    <div class="content">
        <h1 class="title">Sélectionner un Pokémon</h1>

        <div class="search-container">
            <input type="text" id="searchPokemon" placeholder="Rechercher un Pokémon...">
        </div>

        <div class="pokemon-list" id="pokemonList"></div>

        <div class="pagination">
            <button id="prevBtn" class="nav-button">◄ Précédent</button>
            <span id="currentPage">Page 1</span>
            <button id="nextBtn" class="nav-button">Suivant ►</button>
        </div>

        <div id="loading" class="loading">
            <div class="pokeball-loading"></div>
        </div>
    </div>
</section>

<style>
    .change-pokemon.container {
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
        transform: translateZ(0);
        will-change: transform;
        animation: softZoom 20s ease-in-out infinite alternate;
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
        max-width: 1200px;
        padding: 2rem;
    }

    .title {
        font-size: clamp(2rem, 4vw, 4rem);
        color: #ffcb05;
        text-shadow:
            0 0 10px rgba(255, 203, 5, 0.5),
            0 0 20px rgba(255, 203, 5, 0.3);
        margin-bottom: 2rem;
        font-family: 'Pokemon Solid', sans-serif;
        letter-spacing: 0.2em;
        text-align: center;
    }

    .search-container {
        position: relative;
        margin: 2rem auto;
        max-width: 500px;
    }

    #searchPokemon {
        width: 100%;
        padding: 1rem 3rem 1rem 1.5rem;
        font-size: 1.2rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 30px;
        color: white;
        backdrop-filter: blur(5px);
        transition: all 0.3s ease;
    }

    #searchPokemon:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 20px rgba(255, 203, 5, 0.3);
    }

    .pokemon-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
        padding: 1rem;
        max-height: 60vh;
        overflow-y: auto;
    }

    .pokemon-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
    }

    .pokemon-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .pokemon-image {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        object-fit: contain;
    }

    .pokemon-name {
        color: white;
        font-size: 1.2rem;
        margin: 1rem 0;
        text-transform: capitalize;
    }

    .pokemon-types {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .pokemon-type {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.9rem;
        color: white;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        margin-top: 2rem;
    }

    .nav-button {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .nav-button:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    #currentPage {
        color: white;
        font-size: 1.2rem;
    }

    .loading {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .pokeball-loading {
        width: 60px;
        height: 60px;
        background: linear-gradient(to bottom, #ff0000 50%, #ffffff 50%);
        border-radius: 50%;
        position: relative;
        animation: rotate 1s infinite linear;
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @keyframes softZoom {
        0% {
            transform: scale(1);
        }

        100% {
            transform: scale(1.1);
        }
    }
</style>

<script>
    class PokemonManager {
        constructor() {
            this.currentPage = 1;
            this.limit = 8;
            this.offset = 0;

            this.elements = {
                pokemonList: document.getElementById('pokemonList'),
                loading: document.getElementById('loading'),
                prevBtn: document.getElementById('prevBtn'),
                nextBtn: document.getElementById('nextBtn'),
                pageDisplay: document.getElementById('currentPage'),
                searchInput: document.getElementById('searchPokemon')
            };

            this.initializeEventListeners();
            this.fetchPokemons();
        }

        initializeEventListeners() {
            this.elements.prevBtn.addEventListener('click', () => this.previousPage());
            this.elements.nextBtn.addEventListener('click', () => this.nextPage());
            this.elements.searchInput.addEventListener('input', debounce((e) => this.searchPokemon(e.target.value), 300));
        }

        async fetchPokemons() {
            try {
                this.showLoading();
                const response = await fetch(`https://pokeapi.co/api/v2/pokemon?limit=${this.limit}&offset=${this.offset}`);
                const data = await response.json();

                const pokemonDetails = await Promise.all(data.results.map(pokemon => this.fetchPokemonDetails(pokemon.url)));

                this.renderPokemonCards(pokemonDetails);
            } catch (error) {
                console.error('Erreur:', error);
                this.elements.pokemonList.innerHTML = '<p style="color: white;">Erreur lors du chargement des Pokémon</p>';
            } finally {
                this.hideLoading();
            }
        }

        async fetchPokemonDetails(url) {
            const response = await fetch(url);
            return await response.json();
        }

        renderPokemonCards(pokemons) {
            this.elements.pokemonList.innerHTML = '';

            pokemons.forEach(pokemon => {
                const card = document.createElement('div');
                card.className = 'pokemon-card';

                const types = pokemon.types.map(type =>
                    `<span class="pokemon-type" style="background-color: ${this.getTypeColor(type.type.name)}">
                        ${type.type.name}
                    </span>`
                ).join('');

                card.innerHTML = `
                    <img class="pokemon-image" 
                         src="${pokemon.sprites.other['official-artwork'].front_default}" 
                         alt="${pokemon.name}">
                    <h3 class="pokemon-name">${pokemon.name}</h3>
                    <div class="pokemon-types">${types}</div>
                `;

                card.addEventListener('click', () => this.selectPokemon(pokemon.name));
                this.elements.pokemonList.appendChild(card);
            });
        }

        selectPokemon(pokemonName) {
            this.showLoading(); // Afficher l'indicateur de chargement
            fetch('/home/selectPokemon', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        pokemonName
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '/home/selectAttacks';
                    }
                })
                .catch(error => console.error('Erreur:', error))
                .finally(() => {
                    this.hideLoading(); // Masquer l'indicateur de chargement
                });
        }

        getTypeColor(type) {
            const colors = {
                normal: '#A8A878',
                fire: '#F08030',
                water: '#6890F0',
                electric: '#F8D030',
                grass: '#78C850',
                ice: '#98D8D8',
                fighting: '#C03028',
                poison: '#A040A0',
                ground: '#E0C068',
                flying: '#A890F0',
                psychic: '#F85888',
                bug: '#A8B820',
                rock: '#B8A038',
                ghost: '#705898',
                dragon: '#7038F8',
                dark: '#705848',
                steel: '#B8B8D0',
                fairy: '#EE99AC'
            };
            return colors[type] || '#888888';
        }

        showLoading() {
            this.elements.loading.style.display = 'flex';
        }

        hideLoading() {
            this.elements.loading.style.display = 'none';
        }

        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.offset -= this.limit;
                this.elements.pageDisplay.textContent = `Page ${this.currentPage}`;
                this.fetchPokemons();
            }
        }

        nextPage() {
            this.currentPage++;
            this.offset += this.limit;
            this.elements.pageDisplay.textContent = `Page ${this.currentPage}`;
            this.fetchPokemons();
        }

        async searchPokemon(query) {
            if (!query) {
                this.offset = 0;
                this.currentPage = 1;
                return this.fetchPokemons();
            }

            try {
                this.showLoading();
                const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${query.toLowerCase()}`);
                const pokemon = await response.json();
                this.renderPokemonCards([pokemon]);
            } catch {
                this.elements.pokemonList.innerHTML = '<p style="color: white;">Aucun Pokémon trouvé</p>';
            } finally {
                this.hideLoading();
            }
        }
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    document.addEventListener('DOMContentLoaded', () => {
        new PokemonManager();
    });
</script>