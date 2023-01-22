<?php

namespace Model\Manager;

use Model\Post;
use PDO;
use Util\Database;

class PostManager extends Database
{

    public function exist(string $obj)
    {
        return (bool)$this->sql("SELECT * FROM article AS a WHERE a.title=:title", ['title' => $obj])->fetch();
    }

    public function get(string $obj): ?Post
    {
        return $this->sql("SELECT id, title, content, image, published_at, author, slug FROM article WHERE id=:id", ['id' => $obj], [PDO::FETCH_CLASS, Post::class])->fetch();
    }

    public function getBySlug(string $slug): Post|bool|null
    {
        return $this->sql("SELECT id, title, content, image, published_at, author, slug FROM article WHERE slug=:slug", ['slug' => $slug], [PDO::FETCH_CLASS, Post::class])->fetch();
    }

    public function getAll(int $limit = 10, int $offset = -1, $data = []): mixed
    {
        $result = array();

        /*
         * Le LIMIT : réduit le nombre de résultats. Si on "LIMIT 2" : on aura seulement 2 résultats sur notre requête, même si dans la table il y a 100 elements.
         *
         * Le Offset va de pair avec le LIMIT : pour une pagination par exemple. (Décalage)
         * Le limite va réduire le nombre de résultats à 10 (par exemple).
         * Mais pour avoir une page n°2 (par exemple) on va devoir ignorer (décaler donc) de 10 element le résultat de notre requête pour ne pas avoir les 10 premiers articles
         * puisqu'ils ont déjà été affichés sur la page n°1.
         * Alors : on met un OFFSET de 10 pour décaler notre résultat et afficher les éléments de la page n°2.
         *
         */
        $check_offset = ($offset == -1) ? "" : " OFFSET " . $offset;
        $checkLimit = ($limit == -1) ? "" : " LIMIT " . $limit;

        // $terner = (condition) ? si vrai : si faux;
//        if(condition) {
//            si vrai
//        } else {
//            si faux
//        }

        /*
         * LE PDO::FETCH_CLASS :
         * Cela permet à PDO de ne pas renvoyer seulement un tableau qui contient les informations stockées en BDD, mais de les convertir en Objet PHP.
         * Dans ce cas ci-dessous, le Fetch Class va faire un new Post() avec les informations récupérées en BDD qui nous seront utilisables en tant qu'objet plutôt que de tableau.
         */
        return $this->sql("SELECT * FROM article WHERE 1 ORDER BY published_at DESC" . $checkLimit . $check_offset, [], [PDO::FETCH_CLASS, Post::class])->fetchAll();
    }

    /*
     * Le typage : force une variable ou une fonction à être ou retourner un certains types.
     * Exemple de type : int, string, array, Post, Book, float, ...
     *
     * Dans ce cas ici, la fonction "add()" va retourner NULL (?) ou un Article (Post)
     * La fonction retourne NULL quand la requête SQL a échoué et que rien ne s'est passé comme prévu.
     */
    public function add(Post $obj): ?Post
    {
        // On ajoute pas l'ID, car ici, on ne le connait pas encore étant donné que c'est le serveur MySQL qui va nous le créer
        $this->sql(
            "INSERT INTO article (title, content, image, published_at, author, slug) VALUES(:title, :content, :image, :published_at, :author, :slug)",
            [
                'title' => $obj->getTitle(),
                'content' => $obj->getContent(),
                'image' => $obj->getImage(),
                'published_at' => $obj->getPublishedAt(),
                'author' => $obj->getAuthor(),
                'slug' => $obj->getSlug(),
            ]
        );
        $obj->setId($this->getDatabase()->lastInsertId()); // c'est ici qu'on met l'ID quand on ajoute
        return $obj;
    }

    public function del(Post $obj): bool
    {
        if ($this->sql("DELETE FROM article WHERE id=:id", ['id' => $obj->getId()])->fetch()) return true;
        else return false;
    }

    public function update(Post $obj): mixed
    {
        if ($this->sql(
            "UPDATE article SET title = :title, content = :content, image = :image, published_at = :published_at, author = :author, slug = :slug WHERE id=:id",
            [
                'id' => $obj->getId(),
                'title' => $obj->getTitle(),
                'content' => $obj->getContent(),
                'image' => $obj->getImage(),
                'published_at' => $obj->getPublishedAt(),
                'author' => $obj->getAuthor(),
                'slug' => $obj->getSlug(),
            ]
        )->execute()) return $obj;
        else return null;
    }

    public function getLatest(int $size)
    {
        return $this->sql("SELECT * FROM article ORDER BY published_at DESC LIMIT " . $size, [], [PDO::FETCH_CLASS, Post::class])->fetchAll();

    }

}