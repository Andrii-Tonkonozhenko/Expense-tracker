<?php

interface CategoryRepositoryInterface
{
    public function createCategory(Category $category): void;

    public function getCategory(int $categoryId): Category;

    public function getAllCategory(): array;

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

    public function getAllCategory(): array
    {
        return $this->category;
    }

}

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function hydrate(array $data): Category
    {
        $category = new Category();
        $category->setId($data['id']);
        $category->setTitle($data['title']);

        return $category;
    }

    public function createCategory(Category $category): void
    {
        $sql = "INSERT categories (title)
            VALUES('{$category->getTitle()}')";
        $this->pdo->exec($sql);
    }

    public function getCategory(int $categoryId): Category
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id=?");
        $stmt->execute([$categoryId]);
        $data = $stmt->fetch();

        if (!$data['id'] === $categoryId) {
            throw new CategoryNotFoundException();
        }

        return $this->hydrate($data);
    }

    public function getAllCategory(): array
    {
        $data = $this->pdo->query("SELECT * FROM categories")->fetchAll();

        $categories = [];

        foreach ($data as $row) {
            $categories[] = $this->hydrate($row);
        }

        return $categories;
    }
}
