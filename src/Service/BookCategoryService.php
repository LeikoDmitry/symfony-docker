<?php

namespace App\Service;

use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use Doctrine\Common\Collections\Criteria;

class BookCategoryService
{
    public function __construct(private BookCategoryRepository $bookCategoryRepository)
    {
    }

    public function findAll(): BookCategoryListResponse
    {
        $categories = $this->bookCategoryRepository->findBy(criteria: [], orderBy: ['title' => Criteria::ASC]);

        $items = array_map(callback: function (BookCategory $bookCategory) {
            return new BookCategoryListItem(
                $bookCategory->getId(),
                $bookCategory->getTitle(),
                $bookCategory->getSlug()
            );
        }, array: $categories);

        return new BookCategoryListResponse($items);
    }
}
