<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 */
class Section
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct()
    {
        $this->topicss = new ArrayCollection();
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

    /**
     * @return Collection|Topic[]
     */
    public function getTopicss(): Collection
    {
        return $this->topicss;
    }

    public function addTopicss(Topic $topicss): self
    {
        if (!$this->topicss->contains($topicss)) {
            $this->topicss[] = $topicss;
            $topicss->setSection($this);
        }

        return $this;
    }

    public function removeTopicss(Topic $topicss): self
    {
        if ($this->topicss->removeElement($topicss)) {
            // set the owning side to null (unless already changed)
            if ($topicss->getSection() === $this) {
                $topicss->setSection(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
