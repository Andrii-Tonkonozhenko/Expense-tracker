<?php

interface CategoryRepositoryInterface
{
    public function addCategory(Category $category): void;

    public function getCategory(int $id_category): Category;

    public function getAllCategory() : array;

}


class InMemoryCategoryRepository implements CategoryRepositoryInterface
{
    private $category = [];

    public function addCategory(Category $category): void
    {
        $category->setId(count($this->category) + 1);
        $this->category[] = $category;
    }

    /**
     * @param int $id_category
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function getCategory(int $id_category): Category
    {
        $category = null;
        foreach ($this->category as $category) {
            if ($category->getId() === $id_category) {
                return $category;
            }
        }
        if ($category === null) {
            throw new CategoryNotFoundException();
        }
        return $category;
    }

    public function getAllCategory() : array
    {
        return $this->category;
    }

}