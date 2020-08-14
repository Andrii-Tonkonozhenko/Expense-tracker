<?php

interface CategoryRepositoryInterface
{
    public function createCategory(Category $category): void;

    public function getCategory(int $categoryId): Category;

    public function getAllCategory() : array;

}


class InMemoryCategoryRepository implements CategoryRepositoryInterface
{
    private $category = [];

    public function createCategory(Category $category): void
    {
        $category->setId(count($this->category) + 1);
        $this->category[] = $category;
    }

    /**
     * @param int $categoryId
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function getCategory(int $categoryId): Category
    {
        $category = null;
        foreach ($this->category as $category) {
            if ($category->getId() === $categoryId) {
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