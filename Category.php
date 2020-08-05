<?php

class Category
{
    private $id;
    private $title;

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getId() : int
    {
        return $this->id;
    }

}