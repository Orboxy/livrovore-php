<?php

namespace Model;

use DateTime;

class Book
{
    private int $id; // Ici, tu as une propriété que tu as besoin pour la BDD

    private string $title; // Par exemple, ici, ça correspond à ta colonne titre dans ta BDD,
    // pourquoi "string" car c'est une chaine de cara, tu peux avoir plusieurs types :
    private int|string|DateTime|bool $propertyMultiple;
    // Ceci comprend donc ou un INT ou une STRING ou un object DateTime ou un BOOLEAN (true, false)
    private string $author;
    private string $image;
    private string $slug;

    public function __construct()
    {
        $get_arguments = func_get_args();
        $number_of_arguments = func_num_args();

        if (method_exists($this, $method_name = '__construct' . $number_of_arguments)) {
            call_user_func_array(array($this, $method_name), $get_arguments);
        }
    }


    /**
     * La fonction magique __construct :
     * Une fonction magique est une fonction que tu retrouves dans les classes PHP.
     * Ces fonctions te permettre d'avoir un comportement différent en fonction de comment tu utilises ta classe Book (dans cet exemple) et aussi de qu'est-ce que tu veux que
     * la fonction te fasse.
     * Ici, cette fonction __construct s'exécute automatiquement à chaque fois que tu ferras un new Book();
     *
     * Exemple : la fonction __construct.
     *
     * @param int $id
     * @param string $title
     * @param bool|DateTime|int|string $propertyMultiple
     */

    public function __construct6(int $id, string $title, DateTime|bool|int|string $propertyMultiple, string $author, string $image, string $slug): void
    {
        $this->id = $id;
        $this->title = $title;
        $this->propertyMultiple = $propertyMultiple;
        $this->author = $author;
        $this->image = $image;
        $this->slug = $slug;
    }

    /**
     * Cette fonction permet de récupérer la propriété nommée "id" de ta class Book
     * à la fin, le ": int" oblige ta fonction "getInt" à te renvoyer (return) un int, tu ne peux pas lui retourner autre chose.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Cette fonction te permet de lui définir quel est l'id de ton objet Book.
     * Ici, tu vois qu'à la fin de la fonction, c'est écrit ": void" car c'est juste une fonction qui fait quelque chose, elle ne renvoie aucune donnée.
     *
     * Cependant, cette fonction te demande un paramètre de type "int", car ta propriété (ligne 9) est définie en tant que telle, et ne prendra donc rien d'autre qu'un int
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return \DateTime|string
     */
    public function getPropertyMultiple(): \DateTime|string
    {
        return $this->propertyMultiple;
    }

    /**
     * @param \DateTime|string| $propertyMultiple
     */
    public function setPropertyMultiple(\DateTime|string $propertyMultiple): void
    {
        $this->propertyMultiple = $propertyMultiple;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }
    /**
     * @return string
     */

    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

}