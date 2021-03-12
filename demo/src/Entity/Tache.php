<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @ORM\Entity(repositoryClass=TacheRepository::class)
 */
class Tache
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $DueDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Done;

    /**
     * @ORM\ManyToOne(targetEntity=ArticleSemaine::class, inversedBy="taches")
     */
    private $Semaine;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->DueDate;
    }

    public function setDueDate(\DateTimeInterface $DueDate): self
    {
        $this->DueDate = $DueDate;

        return $this;
    }

    public function getDone(): ?bool
    {
        return $this->Done;
    }

    public function setDone(bool $Done): self
    {
        $this->Done = $Done;

        return $this;
    }

    public function getSemaine(): ?ArticleSemaine
    {
        return $this->Semaine;
    }

    public function setSemaine(?ArticleSemaine $Semaine): self
    {
        $this->Semaine = $Semaine;

        return $this;
    }
}
