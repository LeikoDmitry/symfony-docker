<?php

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoryFixture extends Fixture
{
    public const ANDROID_CATEGORY = 'android';
    public const DEVICES_CATEGORY = 'devices';

    public function load(ObjectManager $manager): void
    {
        $categorise = [
            static::DEVICES_CATEGORY => (new BookCategory())->setTitle('Devices')->setSlug('devices'),
            static::ANDROID_CATEGORY => (new BookCategory())->setTitle('Android')->setSlug('android'),
        ];

        foreach ($categorise as $category) {
            $manager->persist($category);
        }

        $manager->persist((new BookCategory())->setTitle('Data')->setSlug('data'));
        $manager->persist((new BookCategory())->setTitle('IOS')->setSlug('ios'));

        $manager->flush();

        foreach ($categorise as $key => $category) {
            $this->addReference($key, $category);
        }
    }
}
