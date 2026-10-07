<?php

namespace App\Console\Commands;

use App\Models\OtpCode;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('otp:cleanup')]
#[Description('Delete expired and consumed OTP codes')]
class CleanupOtpCodes extends Command
{
    public function handle(): int
    {
        $deleted = OtpCode::query()
            ->where('expires_at', '<', now())
            ->delete();

        $this->info("Deleted {$deleted} expired OTP codes.");

        return self::SUCCESS;
    }
}
