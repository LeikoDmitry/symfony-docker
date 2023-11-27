<?php

namespace App\Model\Recommendation;

class RecommendationItem
{
    public function __construct(private int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
