<?php

namespace App\Services\Auth\Breach;

final readonly class BreachService
{
    public function __construct(private PwnedClient $client) {}

    /**
     * Check whether a password has been exposed in known data breaches.
     */
    public function check(string $password): BreachResult
    {
        $count = $this->client->rangeCount($password);

        return new BreachResult(
            pwned: $count > 0,
            count: $count,
            riskLevel: RiskLevel::fromCount($count),
        );
    }

    /**
     * Convenience check — true if the password appears in any known breach.
     */
    public function isPwned(string $password): bool
    {
        return $this->check($password)->pwned;
    }
}
