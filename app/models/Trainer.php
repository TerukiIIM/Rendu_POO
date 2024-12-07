<?php

class Trainer
{
    private int $id;
    private string $name;
    private string $password;
    private array $pokemons;
    private Pokemon $current_pokemon;

    public function __construct($name, $pokemons)
    {
        $this->name = $name;
        $this->pokemons = $pokemons;
        $this->current_pokemon = $pokemons[0];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPokemons(): array
    {
        return $this->pokemons;
    }

    public function getCurrentPokemon(): Pokemon
    {
        return $this->current_pokemon;
    }

//     public static function create(string $username, string $password): User
//     {
//         if (empty($username) || empty($password)) {
//             throw new \Exception("Le nom d'utilisateur et le mot de passe sont requis");
//         }

//         // Vérifier si l'utilisateur existe déjà
//         if (self::findByUsername($username)) {
//             throw new \Exception("Ce nom d'utilisateur est déjà pris");
//         }

//         $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

//         $db = Database::getInstance()->getConnection();
//         $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
//         $stmt = $db->prepare($query);
//         $stmt->execute([
//             'username' => $username,
//             'password' => $hashedPassword
//         ]);

//         return new self($db->lastInsertId(), $username);
//     }

//     public static function authenticate(string $username, string $password): ?User
//     {
//         $db = Database::getInstance()->getConnection();
//         $query = "SELECT * FROM users WHERE username = :username";
//         $stmt = $db->prepare($query);
//         $stmt->execute(['username' => $username]);

//         $user = $stmt->fetch(PDO::FETCH_ASSOC);

//         if ($user && password_verify($password, $user['password'])) {
//             return new self($user['id'], $user['username']);
//         }

//         return null;
//     }

//     private static function findByUsername(string $username): ?User
//     {
//         $db = Database::getInstance()->getConnection();
//         $query = "SELECT * FROM users WHERE username = :username";
//         $stmt = $db->prepare($query);
//         $stmt->execute(['username' => $username]);

//         $user = $stmt->fetch(PDO::FETCH_ASSOC);

//         if ($user) {
//             return new self($user['id'], $user['username']);
//         }

//         return null;
//     }
}
