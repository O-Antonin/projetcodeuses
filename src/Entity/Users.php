<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity (
 * fields = {"email"},
 *  message = "Un compte est déja existant à cette adresse mail!" )
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage = "Votre mot de passe doit contenir 8 caractères minimum", groups={"registration"})
     * @Assert\EqualTo(propertyPath="confirm_password", message="Les mots de passe ne correspondent pas", groups={"registration"})
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passe ne correspondent pas", groups={"registration"})
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

  /**
     *  Pour pouvoir encoder le mot de passe, il faut que notre entité User implemente l'interface UserInterface
     * Cette interface contient des méthodes que nous sommes obligés de déclarer:
     * getPassword(), getUsername(), getRoles(), getSalt(), et eraseCredentials()
     */

    public function eraseCredentials()
    {

    }

      // Renvoit la chaine de caractères non encodée que le user a saisi qui a été utilisé à l'origine pour encoder le mdp
   public function getSalt()
    {    
       
    } 
    
      // cette méthode renvoit un tableau de chaine de caractères où sont stockés les rôles accordés à l'utilisateur
    public function getRoles()
    {
          //return ['ROLE_USER'];
          return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }





}
