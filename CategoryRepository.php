<?php

class categoryRepository
{
    private $category = [];

    public function addCategory(Category $category): void
    {
        $category->setId(count($this->category) + 1);
        $this->category[] = $category;
    }

    public function getCategory(int $id)
    {
        foreach ($this->category as $category) {
            if ($category->getId() === $id) {
                return $category;
            }
        }
    }
}