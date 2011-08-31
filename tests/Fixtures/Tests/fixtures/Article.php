<?php

/**
 * @Doctrine\ORM\Mapping\Entity
 */
class Article
{
    /**
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\GeneratedValue
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     */
    private $title;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="User")
     */
    private $author;

    /**
     * @Doctrine\ORM\Mapping\Column(type="text")
     */
    private $content;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
}
