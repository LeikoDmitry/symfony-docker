<?php

namespace App\DataFixtures;

use App\Entity\Book;
use DateTime;
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
            ->setPublicationDate(new DateTime(datetime: '2019-06-08'))
            ->setSlug('lorem')
            ->setAuthors(['Bot', 'ChatBot'])
            ->setImage('/public/default.png')
            ->setMeap(true)
            ->setCategories(new ArrayCollection([$devicesCategory]))
        );

        $manager->persist(
            (new Book())
            ->setTitle('Lorems Ipsums')
            ->setPublicationDate(new DateTime(datetime: '2019-06-08'))
            ->setSlug('lorems')
            ->setAuthors(['Bots', 'ChatBot'])
            ->setImage('/public/default1.png')
            ->setMeap(false)
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
