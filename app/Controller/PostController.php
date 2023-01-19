<?php

namespace Controller;

use AttributesRouter\Attribute\Route;
use Cassandra\Date;
use Enum\PasswordResetStatus;
use Model\Manager\PostManager;
use Model\Manager\UserManager;
use Model\Post;
use Model\User;
use PHPMailer\PHPMailer\Exception;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use Service\DoubleAuthenticationService;
use Service\Mailer;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Util\AccountUtils;
use Util\JsonResponse;

class PostController extends CoreController
{
    // Ici : affichage de tous les posts que contient la BDD afin de pouvoir les administrer
    #[Route('/admin/post', name: 'admin-post-list', methods: ['GET'])]
    public function register($arguments = [])
    {

        $postManager = new PostManager();
        $arguments['liste_des_articles'] = $postManager->getAll();

        $this->show('pages/admin/posts/list.twig', $arguments);
    }

    // Ici : On va faire le bouton Supprimer
    #[Route('/admin/post/delete/{id}', name: 'admin-post-delete', methods: ['GET'])]
    public function delete($arguments = [])
    {
        $postManager = new PostManager();
        $id = $arguments['params']['id'];
        $postManager->del($postManager->get($id));
        header('Location: /admin/post');
    }

    // Ici : On va faire l'ajout d'un article
    #[Route('/admin/post/add', name: 'admin-post-add', methods: ['GET', 'POST'])]
    public function add($arguments = [])
    {
        $postManager = new PostManager();

        if (!empty($_POST)) {
            $newPost = new Post();
            $newPost->setTitle($_POST['title']);
            $newPost->setContent($_POST['content']);
            $newPost->setImage($_POST['image']);
            $newPost->setAuthor($_POST['author']);
            $newPost->setPublishedAt((new \DateTime())->format('d/m/y H:i:s'));
            $newPost->setSlug($_POST['slug']);// Ici, on ne demande pas la date dans le formulaire, puisque la date de publi c'est quandtu click, du coup, un simple new DateTime
            // suffit pour générer la date et l'heure actuelle :)

            $result = $postManager->add($newPost);
            if ($result) {
                $arguments['success'][] = "Votre article à bien été créé.";
            }
        }


        $this->show('pages/admin/posts/add.twig', $arguments);
    }

    // Ici : On va faire l'ajout d'un article
    #[Route('/admin/post/edit/{id}', name: 'admin-post-edit', methods: ['GET', 'POST'])]
    public function edit($arguments = [])
    {
        $postManager = new PostManager();

        if ($post = $postManager->get($arguments['params']['id'])) {
            $arguments['post'] = $post;
            if (!empty($_POST)) {
                $post->setTitle($_POST['title']);
                $post->setContent($_POST['content']);
                $post->setImage($_POST['image']);
                $post->setAuthor($_POST['author']);
                $post->setPublishedAt((new \DateTime())->format('d/m/y H:i:s'));
                $post->setSlug($_POST['slug']);// Ici, on ne demande pas la date dans le formulaire, puisque la date de publi c'est quand tu click, du coup, un simple new DateTime
                // suffit pour générer la date et l'heure actuelle :)

                $result = $postManager->update($post);
                if ($result) {
                    $arguments['success'][] = "Votre article à bien été modifié !";
                } else {
                    $arguments['error'][] = "Y'a un pépin les gars !";
                }
            }

            $this->show('pages/admin/posts/edit.twig', $arguments);
        } else {
            $this->page404($arguments);
        }
    }

}