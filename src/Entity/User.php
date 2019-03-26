<?php

namespace App\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 *
 * @Doctrine\ORM\Mapping\Entity()
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\UserRepository")
 * @Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity(fields={"email"},
 * message="There is already an account with this email")
 * @Doctrine\ORM\Mapping\HasLifecycleCallbacks()
 * @package                                                     App\Entity
 */
class User implements UserInterface
{

    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue(strategy = "AUTO")
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;
    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=180, unique=true)
     * @Symfony\Component\Validator\Constraints\NotBlank()
     * @Symfony\Component\Validator\Constraints\Email()
     */
    private $email;
    /**
     * @Doctrine\ORM\Mapping\Column(type="json")
     */
    private $roles = [];
    /**
     * @var                       string The hashed password
     * @Doctrine\ORM\Mapping\Column(type="string")
     */
    private $password;
    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     * @Symfony\Component\Validator\Constraints\NotBlank()
     */
    private $firstName;
    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     * @Symfony\Component\Validator\Constraints\NotBlank()
     */
    private $lastName;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\Wishlist",
     * mappedBy="user", cascade={"persist", "remove"})
     */
    private $wishList;
    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\Cart",
     * mappedBy="user", cascade={"persist", "remove"})
     */
    private $userCart;
    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\Order",
     * mappedBy="user", cascade={"persist", "remove"})
     */
    private $userOrder;

    /**
     * @return mixed
     */
    public function getWish()
    {
        return $this->wishList;
    }
    /**
     * @param mixed $wishList
     */
    public function setWish($wishList)
    {
        $this->wishList = $wishList;
    }
    /**
     * @param mixed $userCart
     */
    public function setUserCart($userCart)
    {
        $this->userCart = $userCart;
    }
    /**
     * @return mixed
     */
    public function getUserCart()
    {
        return $this->userCart;
    }
    /**
     * @param mixed $userOrder
     */
    public function setUserOrder($userOrder)
    {
        $this->userOrder = $userOrder;
    }
    /**
     * @return mixed
     */
    public function getUserOrder()
    {
        return $this->userOrder;
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {

        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        if ($this->email === 'krunom92@gmail.com') {
            $roles[] = 'ROLE_ADMIN';
        } else {
            $roles[] = 'ROLE_USER';
        } return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
