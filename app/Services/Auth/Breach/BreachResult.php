<?php

namespace App\Services\Auth\Breach;

final readonly class BreachResult
{
    public function __construct(
        public bool $pwned,
        public int $count,
        public RiskLevel $riskLevel,
    ) {}

    /**
     * A human-readable summary of the breach status.
     */
    public function message(): string
    {
        return $this->pwned
            ? __('auth.social_password_breach', ['count' => number_format($this->count)])
            : __('auth.social_password_safe');
    }

    public function label(): string
    {
        return $this->pwned ? 'Compromised' : 'Clear';
    }

    /**
     * The canonical serialized shape used by the API.
     */
    public function toArray(): array
    {
        return [
            'pwned' => $this->pwned,
            'count' => $this->count,
            'risk_level' => $this->riskLevel->value,
            'label' => $this->label(),
            'message' => $this->message(),
        ];
    }
}
