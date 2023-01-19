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

    public function getAll(): mixed
    {
        return $this->sql("SELECT * FROM article WHERE 1", [], [PDO::FETCH_CLASS, Post::class])->fetchAll();
    }

    public function add(Post $obj): ?Post
    {
        // On ajoute pas l'ID, car ici, on le connais pas encore étant donné que c'est le serveur MySQL qui va nous le créer :)
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
}