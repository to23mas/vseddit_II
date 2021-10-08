<?php
declare(strict_types=1);

namespace Tom\Application\Pals\Entity\Data;

use Doctrine\ORM\Mapping as ORM;
use Nettrine\ORM\Entity;


/**
 * @ORM\Entity(repositoryClass="Tom\Application\Pals\Repository\Data\PalsRepository")
 * @ORM\Table(name="pals")
 */
class PalsEntity {

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    protected int $palId;


    /**
     * @var string
     * @ORM\Column(name="first_name", type="string")
     */
    private string $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string")
     */
    private string $lastName;

    /**
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return int
     */
    public function getPalId(): int
    {
        return $this->palId;
    }


    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getArray(): array
    {
        return ['id' => $this->palId, 'first_name' => $this->firstName, 'last_name' => $this->lastName];
    }


}