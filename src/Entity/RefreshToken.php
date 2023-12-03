<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
class RefreshToken extends AbstractRefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'string')]
    protected $refreshToken;

    #[ORM\Column(type: 'string')]
    protected $username;

    #[ORM\Column(type: 'datetime')]
    protected $valid;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class)]
    private UserInterface $user;

    #[ORM\Column(type: 'date_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken($refreshToken = null): static
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername($username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getValid(): ?DateTimeInterface
    {
        return $this->valid;
    }

    public function setValid($valid): static
    {
        $this->valid = $valid;

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public static function createForUserWithTtl(string $refreshToken, UserInterface $user, int $ttl): RefreshTokenInterface
    {
        /** @var RefreshToken $model */
        $model = parent::createForUserWithTtl($refreshToken, $user, $ttl);
        $model->setUser($user);

        return $model;
    }
}
