<?php

namespace Kikwik\DebounceBundle\Model;


use Doctrine\ORM\Mapping as ORM;

trait DebounceTrait
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $debounceRequestedAt;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected $debounceResponse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $debounceResponseCode;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isDebounceSafe;

    public function setDebounceResponse(array $debounceResponse)
    {
        $this->debounceRequestedAt = new \DateTime();
        $this->debounceResponse = $debounceResponse;
        if($debounceResponse['success']==1)
        {
            $this->debounceResponseCode = $debounceResponse['debounce']['code'];
            $this->isDebounceSafe = $debounceResponse['isSafe'];
        }
        else
        {
            $this->debounceResponseCode = null;
            $this->isDebounceSafe = null;
        }
    }

    public function getDebounceRequestedAt(): ?\DateTime
    {
        return $this->debounceRequestedAt;
    }

    public function getDebounceResponse(): ?array
    {
        return $this->debounceResponse;
    }

    public function getDebounceResponseCode(): ?int
    {
        return $this->debounceResponseCode;
    }

    public function getIsDebounceSafe(): ?bool
    {
        return $this->isDebounceSafe;
    }
}
