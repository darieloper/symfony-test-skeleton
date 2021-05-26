<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends AbstractFixture
{
    public function load(ObjectManager $em)
    {
        foreach (range(1, 10) as $i) {
            $tag = new Tag();
            $tag->setName($this->faker->department);

            $em->persist($tag);
        }

        $em->flush();
    }
}