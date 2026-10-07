<?php

namespace App\Services\Auth\Breach;

enum RiskLevel: string
{
    case Safe = 'safe';
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';

    /**
     * Map a HIBP exposure count onto a risk level.
     */
    public static function fromCount(int $count): self
    {
        return match (true) {
            $count >= 10000 => self::Critical,
            $count >= 1000 => self::High,
            $count >= 100 => self::Medium,
            $count >= 1 => self::Low,
            default => self::Safe,
        };
    }

    /**
     * Human-readable name of the risk level.
     */
    public function label(): string
    {
        return match (true) {
            $this === self::Safe => 'Safe',
            $this === self::Low => 'Low',
            $this === self::Medium => 'Medium',
            $this === self::High => 'High',
            $this === self::Critical => 'Critical',
        };
    }
}
