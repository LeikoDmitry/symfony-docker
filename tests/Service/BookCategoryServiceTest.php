<?php

namespace App\Tests\Service;

use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BookCategoryService;
use App\Tests\AbstractTestCase;
use Doctrine\Common\Collections\Criteria;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;

class BookCategoryServiceTest extends AbstractTestCase
{
    /**
     * @throws Exception
     * @throws ReflectionException
     */
    public function testFindAll(): void
    {
        $category = (new BookCategory())->setTitle('test')->setSlug('test');
        $this->setEntityId(entity: $category, value: 1);
        $repository = $this->createMock(BookCategoryRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with([], ['title' => Criteria::ASC])
            ->willReturn([$category]);

        $service = new BookCategoryService($repository);
        $expected = new BookCategoryListResponse([new BookCategoryListItem(id: 1, title: 'test', slug: 'test')]);

        $this->assertEquals(expected: $expected, actual: $service->findAll());
    }
}
