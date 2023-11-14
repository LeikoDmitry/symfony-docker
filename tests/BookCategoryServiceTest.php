<?php

namespace App\Tests;

use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BookCategoryService;
use Doctrine\Common\Collections\Criteria;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class BookCategoryServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testFindAll(): void
    {
        $repository = $this->createMock(BookCategoryRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with([], ['title' => Criteria::ASC])
            ->willReturn([(new BookCategory())->setTitle('test')->setSlug('test')->setId(1)]);

        $service = new BookCategoryService($repository);
        $expected = new BookCategoryListResponse([new BookCategoryListItem(id: 1, title: 'test', slug: 'test')]);

        $this->assertEquals(expected: $expected, actual: $service->findAll());
    }
}
