<?php

namespace OC\PlatformBundle\Model;

class ClientDTO
{
    /**
     * @var string|null
     */
    private $firstname;

    /**
     * @var string|null
     */
    private $surname;

    /**
     * @var string|null
     */
    private $adresse;

    /**
     * @var string|null
     */
    private $mail;

    /**
     * @var int|null
     */
    private $tel;

    /**
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param null|string $firstname
     */
    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return null|string
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param null|string $surname
     */
    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return null|string
     */
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    /**
     * @param null|string $adresse
     */
    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return null|string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param null|string $mail
     */
    public function setMail(?string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return int|null
     */
    public function getTel(): ?int
    {
        return $this->tel;
    }

    /**
     * @param int|null $tel
     */
    public function setTel(?int $tel): void
    {
        $this->tel = $tel;
    }
}
