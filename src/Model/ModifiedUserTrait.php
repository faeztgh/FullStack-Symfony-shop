<?php


namespace App\Model;


use Doctrine\ORM\Mapping as ORM;

trait ModifiedUserTrait
{
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $createdUser;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $updatedUser;

    /**
     * @return string|null
     */
    public function getCreatedUser(): ?string
    {
        return $this->createdUser;
    }

    /**
     * @param string|null $createdUser
     */
    public function setCreatedUser(?string $createdUser): void
    {
        $this->createdUser = $createdUser;
    }

    /**
     * @return string|null
     */
    public function getUpdatedUser(): ?string
    {
        return $this->updatedUser;
    }

    /**
     * @param string|null $updatedUser
     */
    public function setUpdatedUser(?string $updatedUser): void
    {
        $this->updatedUser = $updatedUser;
    }


}