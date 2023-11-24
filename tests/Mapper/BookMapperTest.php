<?php

namespace App\Tests\Mapper;

use App\Entity\Book;
use App\Mapper\BookMapper;
use App\Model\BookDetails;
use App\Tests\AbstractTestCase;
use DateTimeImmutable;

class BookMapperTest extends AbstractTestCase
{
    public function testMap(): void
    {
        $book = (new Book())
            ->setTitle('test')
            ->setSlug('test')
            ->setAuthors(['test'])
            ->setImage('test')
            ->setIsbn('111111')
            ->setDescription('Test description')
            ->setPublicationDate(new DateTimeImmutable('2023-10-10')
            );

        $this->setEntityId($book, 1);

        $mapper = BookMapper::map($book, BookDetails::class);

        $expected = new BookDetails(
            id: 1,
            title: 'test',
            slug: 'test',
            image: 'test',
            authors: ['test'],
            publicationDate: (new DateTimeImmutable('2023-10-10'))->format(DATE_ATOM)
        );

        $this->assertEquals($expected, $mapper);
    }
}
