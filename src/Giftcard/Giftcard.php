<?php

namespace App\Giftcard;

use ClientX\Entity\Timestamp;

class Giftcard
{

    public int $id;
    public ?int $userId = 0;
    public ?string $code = null;
    public string $type;
    public ?float $amount = null;
    public ?float $minRange = null;
    public ?float $maxRange = null;
    public array $usages = [];
    public int $maxusages = 100;
    public ?\DateTime $expireAt = null;
    use Timestamp;

    public function canUse(int $userId)
    {
        if ($this->userId != null) {
            return $this->userId == $userId && !in_array($userId, $this->usages);
        }
        if (in_array($userId, $this->usages) || $this->maxusages == count($this->usages)) {
            return false;
        }
        if ($this->getExpireAt() != null && $this->getExpireAt()->format('U') < time()){
            return false;
        }
        return true;
    }

    public function getAmount()
    {
        if ($this->type == 'fixed') {
            return $this->amount;
        }
        return rand($this->minRange, $this->maxRange);
    }

    public function use(int $userId)
    {
        $this->usages[] = $userId;
    }

    /**
     * @param string $json
     */
    public function setUsages(string $json): void
    {
        $this->usages = json_decode($json);
    }

    /**
     * @param float|null $minRange
     */
    public function setMinRange(?string $minRange): void
    {
        if ($minRange){
            $this->minRange = (float)$minRange;
        }
    }

    /**
     * @param float|null $maxRange
     */
    public function setMaxRange($maxRange): void
    {
        if ($maxRange){
            $this->maxRange = (float)$maxRange;
        }
    }

    public function setAmount($amount): void
    {
        if ($amount){
            $this->amount = (float)$amount;
        }
    }

    /**
     * @return \DateTime|null
     */
    public function getExpireAt(): ?\DateTime
    {
        return $this->expireAt;
    }

    /**
     * @param string|null $expireAt
     */
    public function setExpireAt(?string $expireAt): void
    {
        $this->expireAt = new \DateTime($expireAt);
    }
}
