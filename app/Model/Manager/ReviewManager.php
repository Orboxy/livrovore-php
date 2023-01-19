<?php

namespace Model\Manager;

use Model\Review;
use PDO;
use Util\Database;

class ReviewManager extends Database
{

    public function exist(string $obj)
    {
        return (bool)$this->sql("SELECT * FROM review AS a WHERE a.title=:title", ['title' => $obj])->fetch();
    }

    public function get(string $obj): ?Review
    {
        return $this->sql("SELECT id, title, content, image, published_at, author, slug, note FROM review WHERE id=:id", ['id' => $obj], [PDO::FETCH_CLASS, Review::class])->fetch();
    }

    public function getBySlug(string $slug): Review|bool|null
    {
        return $this->sql("SELECT id, title, content, image, published_at, author, slug, note FROM review WHERE slug=:slug", ['slug' => $slug], [PDO::FETCH_CLASS, Review::class])->fetch();
    }

    public function getAll(): mixed
    {
        return $this->sql("SELECT * FROM review WHERE 1", [], [PDO::FETCH_CLASS, Review::class])->fetchAll();
    }

    public function add(Review $obj): ?Review
    {
        // On ajoute pas l'ID, car ici, on le connais pas encore étant donné que c'est le serveur MySQL qui va nous le créer :)
        $this->sql(
            "INSERT INTO review (title, content, image, published_at, author, slug, note) VALUES(:title, :content, :image, :published_at, :author, :slug, :note)",
            [
                'title' => $obj->getTitle(),
                'content' => $obj->getContent(),
                'image' => $obj->getImage(),
                'published_at' => $obj->getPublishedAt(),
                'author' => $obj->getAuthor(),
                'slug' => $obj->getSlug(),
                'note' => $obj->getNote(),
            ]
        );
        $obj->setId($this->getDatabase()->lastInsertId()); // c'est ici qu'on met l'ID quand on ajoute
        return $obj;
    }

    public function del(Review $obj): bool
    {
        if ($this->sql("DELETE FROM review WHERE id=:id", ['id' => $obj->getId()])->fetch()) return true;
        else return false;
    }

    public function update(Review $obj): mixed
    {
        if ($this->sql(
            "UPDATE review SET title = :title, content = :content, image = :image, published_at = :published_at, author = :author, slug = :slug, note = :note WHERE id=:id",
            [
                'id' => $obj->getId(),
                'title' => $obj->getTitle(),
                'content' => $obj->getContent(),
                'image' => $obj->getImage(),
                'published_at' => $obj->getPublishedAt(),
                'author' => $obj->getAuthor(),
                'slug' => $obj->getSlug(),
                'note' => $obj->getNote(),
            ]
        )->execute()) return $obj;
        else return null;
    }
}