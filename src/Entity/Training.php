<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingRepository")
 */
class Training
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cost;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lesson", mappedBy="training_id")
     */
    private $lessons;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(?int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return Collection|Lesson[]
     */
    public function getInstructorId(): Collection
    {
        return $this->lessons;
    }

    public function addLessons(Lesson $instructorId): self
    {
        if (!$this->lessons->contains($instructorId)) {
            $this->lessons[] = $instructorId;
            $instructorId->setTrainingId($this);
        }

        return $this;
    }

    public function removeLessons(Lesson $instructorId): self
    {
        if ($this->lessons->contains($instructorId)) {
            $this->lessons->removeElement($instructorId);
            // set the owning side to null (unless already changed)
            if ($instructorId->getTrainingId() === $this) {
                $instructorId->setTrainingId(null);
            }
        }

        return $this;
    }
}

