<?php

namespace App\Repository;

use App\Entity\Book;
use App\Exception\BookNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\AbstractUnicodeString;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[]
     */
    public function findByCategory(int $id): array
    {
        $query = $this->getEntityManager()->createQuery('SELECT b FROM App\Entity\Book b WHERE :categoryId MEMBER OF b.categories');
        $query->setParameter('categoryId', $id);

        return $query->getResult();
    }

    /**
     * @param int[] $ids
     *
     * @return Book[]
     */
    public function findBooksByIds(array $ids): array
    {
        return $this->findBy(['id' => $ids]);
    }

    /**
     * @return Book[]
     */
	public function findUsersBook(?UserInterface $user): array
	{
        return $this->findBy(['user' => $user]);
	}

    public function getUserBookById(int $id, ?UserInterface $user): Book
    {
        $book = $this->findOneBy(['id' => $id, 'user' => $user]);

        if (!$book) {
            throw new BookNotFoundException();
        }

        return $book;
    }

    public function existBySlug(string $slug): bool
    {
        return null !== $this->findOneBy(['slug' => $slug]);
    }
}
