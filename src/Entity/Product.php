<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Any offered product or service. For example: a pair of shoes; a concert ticket; the rental of a car; a haircut; or an episode of a TV show streamed online.
 *
 * @see http://schema.org/Product
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Product")
 * @Vich\Uploadable()
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * The name of the item.
     *
     * @see http://schema.org/name
     *
     * @ORM\Column(type="text")
     * @ApiProperty(iri="http://schema.org/name")
     * @Assert\NotNull
     */
    private string $name;

    /**
     * A description of the item.
     *
     * @see http://schema.org/description
     *
     * @ORM\Column(type="text")
     * @ApiProperty(iri="http://schema.org/description")
     * @Assert\NotNull
     */
    private string $description;

    /**
     * An image of the item. This can be a \[\[URL\]\] or a fully described \[\[ImageObject\]\].
     *
     * @see http://schema.org/image
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    private ?string $image = null;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="product", cascade={"remove", "persist"})
     */
    private $offers;


    public function __construct()
    {
        $this->updatedAt = new DateTime('now');
        $this->offers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     */
    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setProduct($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getProduct() === $this) {
                $offer->setProduct(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
