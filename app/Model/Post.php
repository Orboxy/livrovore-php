<?php

namespace Model;
class Post
{
    // Ici, le $id à un typage de type "int"
    private int $id;
    private string $title;
    private string $content;
    private string $image;

    // Ici, le $published_at à un typage de type "string" OU BIEN (|) de type \DateTime (Objet de PHP)
    /**
     * la barre | signifie "ou" en français. C'est donc ou un string ou un DateTime
     */
    private string|\DateTime $published_at;
    private string $author;
    private string $slug;

    public function __construct()
    {
        $get_arguments = func_get_args();
        $number_of_arguments = func_num_args();

        if (method_exists($this, $method_name = '__construct' . $number_of_arguments)) {
            call_user_func_array(array($this, $method_name), $get_arguments);
        }
    }

    public function __construct7(int $id, string $title, string $content, string $image, string|\DateTime $published_at, string $author, string $slug): void
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
        $this->published_at = $published_at;
        $this->author = $author;
        $this->slug = $slug;
    }

    public function __construct6(string $title, string $content, string $image, string|\DateTime $published_at, string $author, string $slug): void
    {
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
        $this->published_at = $published_at;
        $this->author = $author;
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