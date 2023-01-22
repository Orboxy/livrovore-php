<?php

namespace Controller;

use AttributesRouter\Attribute\Route;
use Model\Manager\PostManager;
use Model\Manager\UserManager;
use Model\User;

class SessionController extends CoreController
{
    #[Route('/register', name: 'session-register', methods: ['GET', 'POST'])]
    public function register($arguments = [])
    {
        $as = $arguments['account'];
        $um = new UserManager();

        if ($as->isConnected()) {
            header('Location: ' . $arguments['router']->generateUrl('main-home'));
        }

        if ($post = $_POST ?? null) {
            $email = $post['email'];
            $fn = $post['firstname'];
            $ln = $post['lastname'];
            $psw = $post['password'];

            if ((isset($email) && !empty($email)) && (isset($fn) && !empty($fn)) && (isset($ln) && !empty($ln)) && (isset($psw) && !empty($psw))) {
                if (!$um->exist($email)) {
//                    $newUser = new User($email, $fn, $ln, $psw);
                    $newUser = new User();
                    $newUser->setEmail($email);
                    $newUser->setFirstname($fn);
                    $newUser->setLastname($ln);
                    $newUser->setPassword($psw);

                    if ($um->register($newUser)) {
                        $arguments['success'] = [
                            'Votre compte a bien été créé. Vous pouvez maintenant <a href="' . $arguments['router']->generateUrl('session-login') . '">vous connecter</a>',
                        ];
                    }
                } else {
                    $arguments['error'] = [
                        'Un compte existe déjà avec cette adresse email. Avez-vous essayé de <a href="' . $arguments['router']->generateUrl('session-login') . '">vous connecter</a>',
                    ];
                }
            } else {
                $arguments['error'] = [
                    'Veuillez remplir tout les champs.',
                ];
            }
        }

        $this->show('pages/admin/register.twig', $arguments);
    }

    #[Route('/login', name: 'session-login', methods: ['GET', 'POST'])]
    public function login($arguments = [])
    {
        $s = $arguments['session'];
        $as = $arguments['account'];
        $um = new UserManager();

        if ($as->isConnected()) {
            header('Location: ' . $arguments['router']->generateUrl('session-account'));
        }

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $psw = $_POST['password'];

            if ((isset($email) && !empty($email)) && (isset($psw) && !empty($psw))) {

                if ($um->exist($email)) {
                    if ($um->checkCredentials($email, $psw)) {
                        $user = $um->getFromEmail($email);
                            $as->login($user);
                            header('Location: /account');
                    } else {
                        $arguments['error'] = [
                            'Identifiants invalides, veuillez les vérifier.',
                        ];
                    }
                } else {
                    $arguments['error'] = [
                        'Identifiants invalides, veuillez les vérifier.',
                    ];
                }
            } else {
                $arguments['error'] = [
                    'Veuillez remplir tout les champs.',
                ];
            }
        }

        $this->show('pages/Login.twig', $arguments);
    }

    #[Route('/account', name: 'session-account', methods: ['GET'])]
    public function account($arguments = [])
    {
        $s = $arguments['session'];
        $as = $arguments['account'];

        if (!$as->isConnected()) {
            header('Location: ' . $arguments['router']->generateUrl('session-login'));
        }
        $postManager = new PostManager();

        $allPosts = $postManager->getAll(0, 0, []);

        $arguments['photo_de_profile'] = "https://picsum.photos/720/1000";

        // array() = [] = un tableau associatif
        $arguments['la_list_des_livres'] = $allPosts;
        $this->show('pages/admin/account/account.twig', $arguments);
    }

    #[Route('/logout', name: 'session-logout', methods: ['GET'])]
    public function logout($arguments = [])
    {
        $as = $arguments['account'];
        $as->logout($arguments['router']->generateUrl('main-home'));
    }

}