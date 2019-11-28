<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegistrationRepository")
 */
class Registration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lesson", inversedBy="registrations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lesson_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="registrations")
     *  @ORM\JoinColumn(nullable=false)
     */
    private $member_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayment(): ?bool
    {
        return $this->payment;
    }

    public function setPayment(?bool $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getLessonId(): ?Lesson
    {
        return $this->lesson_id;
    }

    public function setLessonId(?Lesson $lesson_id): self
    {
        $this->lesson_id = $lesson_id;

        return $this;
    }

    public function getMemberId(): ?Person
    {
        return $this->member_id;
    }

    public function setMemberId(?Person $member_id): self
    {
        $this->member_id = $member_id;

        return $this;
    }
}
