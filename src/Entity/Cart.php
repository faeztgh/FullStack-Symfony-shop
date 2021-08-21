<?php

namespace App\Entity;

use App\Controller\CartController;
use App\Model\ModifiedUserInterface;
use App\Model\ModifiedUserTrait;
use App\Model\OwnedInterface;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart implements ModifiedUserInterface, OwnedInterface
{
    use ModifiedUserTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="cart", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="cart")
     */
    private $cartItem;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->cartItem = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|CartItem[]
     */
    public function getUncheckedCartItem()
    {
        $uncheckedCartItems = [];
        foreach ($this->getCartItem() as $item) {
            if ($item->getStatus() != CartController::CART_ITEM_STATUS_CHECKED_OUT) {
                array_push($uncheckedCartItems, $item);
            }
        }
        return $uncheckedCartItems;
    }

    /**
     * @return Collection|CartItem[]
     */
    public function getCartItem(): Collection
    {
        return $this->cartItem;
    }

    /**
     * @return Collection|CartItem[]
     */
    public function getCheckedCartItem()
    {
        $uncheckedCartItems = [];
        foreach ($this->getCartItem() as $item) {
            if ($item->getStatus() == CartController::CART_ITEM_STATUS_CHECKED_OUT) {
                array_push($uncheckedCartItems, $item);
            }
        }
        return $uncheckedCartItems;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItem->contains($cartItem)) {
            $this->cartItem[] = $cartItem;
            $cartItem->setCart($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItem->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getCart() === $this) {
                $cartItem->setCart(null);
            }
        }

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getOwner(): ?User
    {
        return $this->user;
    }
}
