<?php

namespace Controller;

use AttributesRouter\Attribute\Route;
use Cassandra\Date;
use Enum\PasswordResetStatus;
use Model\Manager\ReviewManager;
use Model\Manager\UserManager;
use Model\Review;
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

class ReviewController extends CoreController
{
    // Ici : affichage de tous les posts que contient la BDD afin de pouvoir les administrer
    #[Route('/admin/review', name: 'admin-review-list', methods: ['GET'])]
    public function register($arguments = [])
    {

        $reviewManager = new ReviewManager();
        $arguments['liste_des_revues'] = $reviewManager->getAll();

        $this->show('pages/admin/review/list.twig', $arguments);
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
            $newReview->setPublishedAt((new \DateTime())->format('d/m/y H:i:s'));
            $newReview->setSlug($_POST['slug']);// Ici, on ne demande pas la date dans le formulaire, puisque la date de publi c'est quandtu click, du coup, un simple new DateTime
            // suffit pour générer la date et l'heure actuelle :)

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
                $review->setPublishedAt((new \DateTime())->format('d/m/y H:i:s'));
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

            $this->show('pages/admin/review/edit.twig', $arguments);
        } else {
            $this->page404($arguments);
        }
    }

}