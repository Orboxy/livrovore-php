<?php

namespace Controller;

use AttributesRouter\Attribute\Route;
use Model\Book;
use Model\Manager\BookManager;

class BookController extends CoreController
{
    // Ici : affichage de tous les posts que contient la BDD afin de pouvoir les administrer
    #[Route('/admin/book', name: 'admin-book-list', methods: ['GET'])]
    public function register($arguments = [])
    {
        $bookManager = new BookManager();
        $arguments['liste_des_livres'] = $bookManager->getAll();

        $this->show('pages/admin/books/list.twig', $arguments);
    }

    // Ici : On va faire le bouton Supprimer
    #[Route('/admin/book/delete/{id}', name: 'admin-book-delete', methods: ['GET'])]
    public function delete($arguments = [])
    {
        $bookManager = new BookManager();
        $id = $arguments['params']['id'];
        $bookManager->del($bookManager->get($id));
        header('Location: /admin/book');
    }

    // Ici : On va faire l'ajout d'un article
    #[Route('/admin/book/add', name: 'admin-book-add', methods: ['GET', 'POST'])]
    public function add($arguments = [])
    {
        $bookManager = new BookManager();

        if (!empty($_POST)) {
            $newPost = new Book();
            $newPost->setTitle($_POST['title']);
            $newPost->setImage($_POST['image']);
            $newPost->setAuthor($_POST['author']);

            $result = $bookManager->add($newPost);
            if ($result) {
                $arguments['success'][] = "Votre livre à bien été créé.";
            }
        }


        $this->show('pages/admin/books/add.twig', $arguments);
    }

    // Ici : On va faire l'ajout d'un article
    #[Route('/admin/book/edit/{id}', name: 'admin-book-edit', methods: ['GET', 'POST'])]
    public function edit($arguments = [])
    {
        $bookManager = new BookManager();

        if ($book = $bookManager->get($arguments['params']['id'])) {
            $arguments['book'] = $book;
            if (!empty($_POST)) {
                $book->setTitle($_POST['title']);
                $book->setImage($_POST['image']);
                $book->setAuthor($_POST['author']);

                $result = $bookManager->update($book);
                if ($result) {
                    $arguments['success'][] = "Votre livre à bien été modifié !";
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