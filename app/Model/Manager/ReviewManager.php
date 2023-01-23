<?php

namespace Model\Manager;

use Model\Review;
use PDO;
use Util\Database;

class ReviewManager extends Database
{

    public function exist(string $obj)
    {
        return (bool)$this->sql("SELECT * FROM revues AS a WHERE a.title=:title", ['title' => $obj])->fetch();
    }

    public function get(string $obj): ?Review
    {
        return $this->sql("SELECT id, title, content, image, published_at, author, slug, note FROM revues WHERE id=:id", ['id' => $obj], [PDO::FETCH_CLASS, Review::class])->fetch();
    }

    public function getBySlug(string $slug): Review|bool|null
    {
        return $this->sql("SELECT id, title, content, image, published_at, author, slug, note FROM revues WHERE slug=:slug", ['slug' => $slug], [PDO::FETCH_CLASS, Review::class])->fetch();
    }

    public function getAll(int $limit = 10, int $offset = -1, $data = []): mixed
    {
        $check_offset = ($offset == -1) ? "" : " OFFSET " . $offset;
        $checkLimit = ($limit == -1) ? "" : " LIMIT " . $limit;
        return $this->sql("SELECT * FROM revues WHERE 1 ORDER BY published_at DESC" . $checkLimit . $check_offset, [], [PDO::FETCH_CLASS, Review::class])->fetchAll();
    }

    public function add(Review $obj): ?Review
    {
        // On ajoute pas l'ID, car ici, on le connais pas encore étant donné que c'est le serveur MySQL qui va nous le créer :)
        $this->sql(
            "INSERT INTO revues (title, content, image, published_at, author, slug, note) VALUES(:title, :content, :image, :published_at, :author, :slug, :note)",
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
        if ($this->sql("DELETE FROM revues WHERE id=:id", ['id' => $obj->getId()])->fetch()) return true;
        else return false;
    }

    public function update(Review $obj)
    {
        if ($this->sql(
            "UPDATE revues SET title = :title, content = :content, image = :image, published_at = :published_at, author = :author, slug = :slug, note = :note WHERE id=:id",
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