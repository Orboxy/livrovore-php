<?php

namespace Controller;

use AttributesRouter\Attribute\Route;
use Model\Manager\PostManager;
use Model\Post;

class MainController extends CoreController {

    #[Route('/', name: 'main-home', methods: ['GET'])]
    public function home(array $arguments = []): void
    {
        $postManager = new PostManager();

        $allPosts = $postManager->getAll(0, 0, []);
        $arguments['la_list_des_livres'] = $allPosts;

        $arguments['photo_de_profile'] = "https://picsum.photos/720/1000";

        // array() = [] = un tableau associatif

       $this->show('pages/home.twig', $arguments);
    }

    #[Route('/article/{slug}', name: 'show-post', methods: ['GET'])]
    public function showPost(array $arguments = []): void
    {
        $postManager = new PostManager();
        $post = $postManager->getBySlug($arguments['params']['slug']);

        if($post) {
            $arguments['post'] = $post;
            $this->show('pages/post.twig', $arguments);
        } else {
            $this->page404($arguments);
        }

    }

}
