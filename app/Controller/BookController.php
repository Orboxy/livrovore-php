<?php

namespace Controller;

use AttributesRouter\Attribute\Route;
use Model\Manager\PostManager;

class BookController extends CoreController
{
    // Ici : affichage de tous les posts que contient la BDD afin de pouvoir les administrer
    #[Route('/admin/book', name: 'admin-book-list', methods: ['GET'])]
    public function register($arguments = [])
    {
        $postManager = new PostManager();
        $arguments['liste_des_articles'] = $postManager->getAll();

        $this->show('pages/admin/books/list.twig', $arguments);
    }

    // Ici : On va faire le bouton Supprimer
    #[Route('/admin/book/delete/{id}', name: 'admin-book-delete', methods: ['GET'])]
    public function delete($arguments = [])
    {
        $postManager = new PostManager();
        $id = $arguments['params']['id'];
        $postManager->del($postManager->get($id));
        header('Location: /admin/book');
    }

    // Ici : On va faire l'ajout d'un article
    #[Route('/admin/book/add', name: 'admin-book-add', methods: ['GET', 'POST'])]
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


        $this->show('pages/admin/books/add.twig', $arguments);
    }

    // Ici : On va faire l'ajout d'un article
    #[Route('/admin/book/edit/{id}', name: 'admin-book-edit', methods: ['GET', 'POST'])]
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

            $this->show('pages/admin/books/edit.twig', $arguments);
        } else {
            $this->page404($arguments);
        }
    }

}