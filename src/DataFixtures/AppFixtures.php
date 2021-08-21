<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Contact;
use App\Entity\Discount;
use App\Entity\OrderDetails;
use App\Entity\OrderItems;
use App\Entity\PaymentDetails;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    protected Generator $faker;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->faker = Factory::create();
        $this->passwordEncoder = $passwordEncoder;

    }

    public function load(ObjectManager $manager)
    {
        $this->loadAdmin($manager);
        $this->loadUsers($manager);
        $this->loadContacts($manager);
        $this->loadProducts($manager);
        $this->loadCartItems($manager);
    }

    public function loadAdmin(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName("Faez");
        $user->setLastName("Tgh");
        $user->setUsername("Admin");
        $user->setEmail("admin@gmail.com");
        $user->setAvatar("https://picsum.photos/500/300?random=5");
        $user->setAddress($this->faker->streetAddress);
        $user->setPhoneNo($this->faker->phoneNumber);

        $user->setCreatedAt($this->faker->dateTimeThisDecade);
        $user->setUpdatedAt($this->faker->dateTimeThisDecade);
        $user->setRoles(["ROLE_ADMIN, ROLE_SUPER_ADMIN"]);
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                123456
            )
        );

        $manager->persist($user);
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setUsername($this->faker->userName);
            $user->setEmail($this->faker->email);
            $user->setAvatar("https://picsum.photos/500/300?random=$i");
            $user->setAddress($this->faker->streetAddress);
            $user->setPhoneNo($this->faker->phoneNumber);
            $user->setCreatedAt($this->faker->dateTimeThisDecade);
            $user->setUpdatedAt($this->faker->dateTimeThisDecade);

            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $this->faker->password
                )
            );
            $this->setReference("user$i", $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function loadContacts(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $contact = new Contact();
            $contact->setFullName($this->faker->name);
            $contact->setEmail($this->faker->email);
            $contact->setSubject($this->faker->realText(20));
            $contact->setMessage($this->faker->realText(500));
            $contact->setCreatedAt($this->faker->dateTimeThisDecade);
            $manager->persist($contact);
        }

        $manager->flush();
    }

    private function loadProducts(ObjectManager $manager)
    {
        $this->loadDiscounts($manager);

        for ($i = 1; $i <= 10; $i++) {
            /**
             * @var Discount $discount
             */
            $discount = $this->getReference("discount$i");

            $product = new Product();
            $product->setName("product$i");
            $product->setBrand($this->faker->firstNameMale);
            $product->setModel($this->faker->randomLetter);
            $product->setColor($this->faker->colorName);
            $product->setBriefDescription($this->faker->realText(100));
            $product->setDescription($this->faker->realText(1000));
            $product->setPrice(round($this->faker->numberBetween(50, 15000)));
            $product->setCreatedAt($this->faker->dateTimeThisDecade);
            $product->setUpdatedAt($this->faker->dateTimeThisDecade);
            $product->setRate($this->faker->numberBetween(1, 5));
            $product->setSize($this->faker->randomDigitNotNull);
            $product->setCreatedUser("Data Fixtures");
            $product->setUpdatedUser("Data Fixtures");
            $product->setDiscount($discount);

            $product->setImage("$i.jpg");

            $this->setReference("product$i", $product);
            $manager->persist($product);
        }
        $manager->flush();
    }

    private function loadDiscounts(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {

            $discount = new Discount();
            $discount->setName("discount$i");
            $discount->setDescription($this->faker->realText(80));
            $discount->setDiscountPercent($this->faker->numberBetween(5, 15));
            $discount->setCreatedAt($this->faker->dateTimeThisDecade);
            $discount->setUpdatedAt($this->faker->dateTimeThisDecade);
            $discount->setCreatedUser("Data Fixtures");
            $discount->setUpdatedUser("Data Fixtures");

            $this->setReference("discount$i", $discount);

            $manager->persist($discount);
        }

        $manager->flush();
    }

    private function loadCartItems(ObjectManager $manager)
    {
        $this->loadCarts($manager);
        for ($i = 1; $i <= 10; $i++) {
            /**
             * @var Product $product
             */
            $product = $this->getReference("product$i");
            /**
             * @var Cart $cart
             */
            $cart = $this->getReference("cart$i");
            $quantity = 1;


            $cartItem = new CartItem();
            $cartItem->setProduct($product);

            $cartItem->setCart($cart);
            $cartItem->setQuantity($quantity);
            $cartItem->setTotalPrice($product->getPrice() * $quantity);

            $cartItem->setCreatedAt($this->faker->dateTimeThisDecade);
            $cartItem->setUpdatedAt($this->faker->dateTimeThisDecade);
            $cartItem->setCheckedOutDate($this->faker->dateTimeThisYear);
            $cartItem->setCreatedUser("Data Fixtures");
            $cartItem->setUpdatedUser("Data Fixtures");

            $this->setReference("cartItem$i", $cartItem);

            $manager->persist($cartItem);
        }

        $manager->flush();
    }

    private function loadCarts(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            /**
             * @var User $user
             */
            $user = $this->getReference("user$i");

            $cart = new Cart();
            $cart->setUser($user);
            $cart->setTotalPrice($this->faker->numberBetween(20, 2000));
            $cart->setQuantity(1);

            $cart->setCreatedAt($this->faker->dateTimeThisDecade);
            $cart->setUpdatedAt($this->faker->dateTimeThisDecade);
            $cart->setCreatedUser("Data Fixtures");
            $cart->setUpdatedUser("Data Fixtures");

            $this->setReference("cart$i", $cart);

            $manager->persist($cart);
        }

        $manager->flush();
    }
}
