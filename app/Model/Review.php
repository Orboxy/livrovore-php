<?php

namespace Model;

class Review
{
    private int $id;
    private string $title;
    private string $content;
    private string $image;
    private string|\DateTime $published_at;
    private string $author;

    private int $note;
    private string $slug;

    public function __construct()
    {
        $get_arguments = func_get_args();
        $number_of_arguments = func_num_args();

        if (method_exists($this, $method_name = '__construct' . $number_of_arguments)) {
            call_user_func_array(array($this, $method_name), $get_arguments);
        }
    }
    // Ici, le __construct permet de compter le nombre d'argument qu'envoie le retour de la requête SQL quand tu ajoute/modifie
    // et du coup, étant donné que MySQL te renvoie le même nombre de champs que dans la BDD, il faut qu'il y ai les mêmes en paramettre
    // + aussi le nombre de chamsp total juste après. Donc '__construct' deviens '__construct8' (car y'a 8 champs)
    public function __construct8(int $id, string $title, string $content, string $image, string|\DateTime $published_at, string $author, string $slug, int $note): void
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
        $this->published_at = $published_at;
        $this->author = $author;
        $this->note = $note;
        $this->slug = $slug;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
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
    public function getPublishedAt(): \DateTime|string
    {
        return $this->published_at;
    }

    /**
     * @param \DateTime|string $published_at
     */
    public function setPublishedAt(\DateTime|string $published_at): void
    {
        $this->published_at = $published_at;
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
     * @return int
     */
    public function getNote(): int
    {
        return $this->note;
    }

    /**
     * @param int $note
     */
    public function setNote(int $note): void
    {
        $this->note = $note;
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