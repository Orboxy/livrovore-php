<?php

namespace Model\Manager;

use Model\Book;
use PDO;
use Util\Database;

class BookManager extends Database
{

    public function exist(string $obj)
    {
        return (bool)$this->sql("SELECT * FROM livre AS a WHERE a.title=:title", ['title' => $obj])->fetch();
    }

    public function get(string $obj): ?Book
    {
        return $this->sql("SELECT id, title, image, author FROM livre WHERE id=:id", ['id' => $obj], [PDO::FETCH_CLASS, Book::class])->fetch();
    }

    public function getAll(): mixed
    {
        return $this->sql("SELECT * FROM livre WHERE 1", [], [PDO::FETCH_CLASS, Book::class])->fetchAll();
    }

    public function add(Book $obj): Book
    {
        // On ajoute pas l'ID, car ici, on le connais pas encore étant donné que c'est le serveur MySQL qui va nous le créer :)
        $this->sql(
            "INSERT INTO livre (title, image, author) VALUES(:title, :image, :author)",
            [
                'title' => $obj->getTitle(),
                'image' => $obj->getImage(),
                'author' => $obj->getAuthor(),
            ]
        );
        $obj->setId($this->getDatabase()->lastInsertId()); // c'est ici qu'on met l'ID quand on ajoute
        return $obj;
    }

    public function del(Book $obj): bool
    {
        if ($this->sql("DELETE FROM livre WHERE id=:id", ['id' => $obj->getId()])->fetch()) return true;
        else return false;
    }

    public function update(Book $obj): mixed
    {
        if ($this->sql(
            "UPDATE livre SET title = :title, image = :image, author = :author WHERE id=:id",
            [
                'id' => $obj->getId(),
                'title' => $obj->getTitle(),
                'image' => $obj->getImage(),
                'author' => $obj->getAuthor(),
            ]
        )->execute()) return $obj;
        else return null;
    }
}