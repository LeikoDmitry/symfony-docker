<?php

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist((new BookCategory())->setTitle('Data')->setSlug('data'));
        $manager->persist((new BookCategory())->setTitle('Android')->setSlug('android'));
        $manager->persist((new BookCategory())->setTitle('IOS')->setSlug('ios'));

        $manager->flush();
    }
}
