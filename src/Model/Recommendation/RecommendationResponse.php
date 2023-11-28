<?php

namespace App\Model\Recommendation;

readonly class RecommendationResponse
{
    public function __construct(
        private int $id,
        private int $ts,
        /** @var RecommendationItem[] */
        private array $recommendations
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTs(): int
    {
        return $this->ts;
    }

    /**
     * @return RecommendationItem[]
     */
    public function getRecommendations(): array
    {
        return $this->recommendations;
    }
}
