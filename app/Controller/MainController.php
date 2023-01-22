<?php

namespace Controller;

use AttributesRouter\Attribute\Route;
use Model\Manager\PostManager;

class MainController extends CoreController
{

    #[Route('/', name: 'main-home', methods: ['GET'])]
    public function home(array $arguments = []): void
    {
        $postManager = new PostManager();

        $arguments['dernier_article'] = $postManager->getAll(1);
        $arguments['la_list_des_livres'] = $postManager->getAll(3, 1); // Ici, on en récupère toi, mais on oublie le 1er, car on le récup au dessus

        $this->show('pages/home.twig', $arguments);
    }

    #[Route('/article/{slug}', name: 'show-post', methods: ['GET'])]
    public function showPost(array $arguments = []): void
    {
        $postManager = new PostManager();
        $post = $postManager->getBySlug($arguments['params']['slug']);

        if ($post) {
            $arguments['post'] = $post;
            $this->show('pages/post.twig', $arguments);
        } else {
            $this->page404($arguments);
        }
    }

    // Du coup, tout le #[Route] et le public function, c'est une route !

    // "/articles" = le lien pour y accéder
    // "name" c'est le nom pour recréé la route quand tu veux y cliquer dessus
    // "methods" : Si tu veux juste de l'affichage (GET) ou si tu as un formulaire (POST)
    //
    // Le nom de la fonction doit être différent, car tu peux pas avoir 2 fois la même fonction dans un fichier PHP
    #[Route('/articles', name: 'main-articles', methods: ['GET'])]
    public function articles(array $arguments = []): void
    {
        $postManager = new PostManager();
        $arguments['tout_les_articles'] = $postManager->getAll(-1);

        $this->show('pages/posts.twig', $arguments);
    }

    #[Route('/a-propos', name: 'main-about', methods: ['GET'])]
    public function about(array $arguments = []): void
    {
        $this->show('pages/about.twig', $arguments);
    }

    #[Route('/contactez-moi', name: 'main-contact', methods: ['GET'])]
    public function contact(array $arguments = []): void
    {
        $this->show('pages/contact.twig', $arguments);
    }

    #[Route('/confidentialite', name: 'main-confidentialite', methods: ['GET'])]
    public function confidentialite(array $arguments = []): void
    {
        $this->show('pages/confidentialite.twig', $arguments);
    }

    #[Route('/legals', name: 'main-legals', methods: ['GET'])]
    public function legals(array $arguments = []): void
    {
        $this->show('pages/legals.twig', $arguments);
    }

}
