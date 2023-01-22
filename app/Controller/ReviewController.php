<?php

namespace Controller;

use AttributesRouter\Attribute\Route;
use Model\Review;
use Model\Manager\ReviewManager;
use Util\AccountUtils;

class ReviewController extends CoreController
{
    // Ici : affichage de tous les posts que contient la BDD afin de pouvoir les administrer
    #[Route('/admin/review', name: 'admin-review-list', methods: ['GET'])]
    public function register($arguments = [])
    {

        /** @var AccountUtils $am */ // ici, c'est juste pour que php comprenne que le $am est un AccountUtils
        //Ce qui me permet de voir des fonctions dans le AccountUitls, sinon, phpstorm le trouvera pas, php le comprendra quand même
        $am = $arguments['account'];
        if(!$am->isConnected()) $am->checkConnected('/');
        // Il faudra que tu mettes ces deux lignes là de partout au debut de tes fonctionne comme celles-ci.
        // Ca permet de voir si t'es connecter, et si tu l'est pas, te rediriger sur la home

        $reviewManager = new ReviewManager();
        $arguments['liste_des_revues'] = $reviewManager->getAll();

        $this->show('pages/admin/reviews/list.twig', $arguments);
    }

    // Ici : On va faire le bouton Supprimer
    #[Route('/admin/review/delete/{id}', name: 'admin-review-delete', methods: ['GET'])]
    public function delete($arguments = [])
    {
        $reviewManager = new ReviewManager();
        $id = $arguments['params']['id'];
        $reviewManager->del($reviewManager->get($id));
        header('Location: /admin/review');
    }

    // Ici : On va faire l'ajout d'un article
    #[Route('/admin/review/add', name: 'admin-review-add', methods: ['GET', 'POST'])]
    public function add($arguments = [])
    {
        $reviewManager = new ReviewManager();

        if (!empty($_POST)) {
            $newReview = new Review();
            $newReview->setTitle($_POST['title']);
            $newReview->setContent($_POST['content']);
            $newReview->setImage($_POST['image']);
            $newReview->setAuthor($_POST['author']);
            $newReview->setPublishedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $newReview->setSlug($_POST['slug']);
            $newReview->setNote($_POST['note']);

            $result = $reviewManager->add($newReview);
            if ($result) {
                $arguments['success'][] = "Votre article à bien été créé.";
            }
        }


        $this->show('pages/admin/reviews/add.twig', $arguments);
    }

    // Ici : On va faire l'ajout d'un article
    #[Route('/admin/review/edit/{id}', name: 'admin-review-edit', methods: ['GET', 'POST'])]
    public function edit($arguments = [])
    {
        $reviewManager = new ReviewManager();

        if ($review = $reviewManager->get($arguments['params']['id'])) {
            $arguments['review'] = $review;
            if (!empty($_POST)) {
                $review->setTitle($_POST['title']);
                $review->setContent($_POST['content']);
                $review->setImage($_POST['image']);
                $review->setAuthor($_POST['author']);
                $review->setPublishedAt((new \DateTime())->format('Y-m-d H:i:s'));
                $review->setSlug($_POST['slug']);
                $review->setNote($_POST['note']);// Ici, on ne demande pas la date dans le formulaire, puisque la date de publi c'est quand tu click, du coup, un simple new DateTime
                // suffit pour générer la date et l'heure actuelle :)

                $result = $reviewManager->update($review);
                if ($result) {
                    $arguments['success'][] = "Votre review à bien été modifiée !";
                } else {
                    $arguments['error'][] = "Y'a un pépin les gars !";
                }
            }

            $this->show('pages/admin/reviews/edit.twig', $arguments);
        } else {
            $this->page404($arguments);
        }
    }

}