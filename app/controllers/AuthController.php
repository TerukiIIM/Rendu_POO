<?php

class AuthController
{
    // use Render;

    // public function login()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $username = $_POST['username'] ?? '';
    //         $password = $_POST['password'] ?? '';

    //         $user = Trainer::authenticate($username, $password);
    //         if ($user) {
    //             $_SESSION['user_id'] = $user->getId();
    //             header('Location: /');
    //             exit;
    //         }

    //         $error = "Identifiants invalides";
    //         $this->renderView('auth/login', ['error' => $error]);
    //     }

    //     $this->renderView('auth/login');
    // }

    // public function register()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $username = $_POST['username'] ?? '';
    //         $password = $_POST['password'] ?? '';

    //         try {
    //             $user = Trainer::create($username, $password);
    //             $_SESSION['user_id'] = $user->getId();
    //             header('Location: /pokemon/select');
    //             exit;
    //         } catch (\Exception $e) {
    //             $error = $e->getMessage();
    //             $this->renderView('auth/register', ['error' => $error]);
    //         }
    //     }

    //     $this->renderView('auth/register');
    // }

    // public function logout()
    // {
    //     session_destroy();
    //     header('Location: /auth/login');
    //     exit;
    // }
}
