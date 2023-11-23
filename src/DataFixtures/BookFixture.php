<?php

namespace App\DataFixtures;

use App\Entity\Book;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $androidCategory = $this->getReference(name: BookCategoryFixture::ANDROID_CATEGORY);
        $devicesCategory = $this->getReference(name: BookCategoryFixture::DEVICES_CATEGORY);

        $manager->persist(
            (new Book())
            ->setTitle('Lorem Ipsum')
            ->setPublicationDate(new DateTimeImmutable(datetime: '2019-06-08'))
            ->setSlug('lorem')
            ->setAuthors(['Bot', 'ChatBot'])
            ->setImage('/public/default.png')
            ->setIsbn('1234445555')
            ->setDescription('Some Description')
            ->setCategories(new ArrayCollection([$devicesCategory]))
        );

        $manager->persist(
            (new Book())
            ->setTitle('Lorem Ipsum')
            ->setPublicationDate(new DateTimeImmutable(datetime: '2019-06-08'))
            ->setSlug('lorem')
            ->setAuthors(['Bots', 'ChatBot'])
            ->setImage('/public/default1.png')
            ->setCategories(new ArrayCollection([$androidCategory, $devicesCategory]))
        );

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BookCategoryFixture::class,
        ];
    }
}
