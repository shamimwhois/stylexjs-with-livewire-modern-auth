<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Afghanistan', 'iso2' => 'AF', 'dial_code' => '+93'],
            ['name' => 'Albania', 'iso2' => 'AL', 'dial_code' => '+355'],
            ['name' => 'Algeria', 'iso2' => 'DZ', 'dial_code' => '+213'],
            ['name' => 'Argentina', 'iso2' => 'AR', 'dial_code' => '+54'],
            ['name' => 'Australia', 'iso2' => 'AU', 'dial_code' => '+61'],
            ['name' => 'Austria', 'iso2' => 'AT', 'dial_code' => '+43'],
            ['name' => 'Bangladesh', 'iso2' => 'BD', 'dial_code' => '+880'],
            ['name' => 'Belgium', 'iso2' => 'BE', 'dial_code' => '+32'],
            ['name' => 'Brazil', 'iso2' => 'BR', 'dial_code' => '+55'],
            ['name' => 'Bulgaria', 'iso2' => 'BG', 'dial_code' => '+359'],
            ['name' => 'Canada', 'iso2' => 'CA', 'dial_code' => '+1'],
            ['name' => 'Chile', 'iso2' => 'CL', 'dial_code' => '+56'],
            ['name' => 'China', 'iso2' => 'CN', 'dial_code' => '+86'],
            ['name' => 'Colombia', 'iso2' => 'CO', 'dial_code' => '+57'],
            ['name' => 'Croatia', 'iso2' => 'HR', 'dial_code' => '+385'],
            ['name' => 'Czech Republic', 'iso2' => 'CZ', 'dial_code' => '+420'],
            ['name' => 'Denmark', 'iso2' => 'DK', 'dial_code' => '+45'],
            ['name' => 'Egypt', 'iso2' => 'EG', 'dial_code' => '+20'],
            ['name' => 'Estonia', 'iso2' => 'EE', 'dial_code' => '+372'],
            ['name' => 'Finland', 'iso2' => 'FI', 'dial_code' => '+358'],
            ['name' => 'France', 'iso2' => 'FR', 'dial_code' => '+33'],
            ['name' => 'Germany', 'iso2' => 'DE', 'dial_code' => '+49'],
            ['name' => 'Greece', 'iso2' => 'GR', 'dial_code' => '+30'],
            ['name' => 'Hungary', 'iso2' => 'HU', 'dial_code' => '+36'],
            ['name' => 'India', 'iso2' => 'IN', 'dial_code' => '+91'],
            ['name' => 'Indonesia', 'iso2' => 'ID', 'dial_code' => '+62'],
            ['name' => 'Ireland', 'iso2' => 'IE', 'dial_code' => '+353'],
            ['name' => 'Israel', 'iso2' => 'IL', 'dial_code' => '+972'],
            ['name' => 'Italy', 'iso2' => 'IT', 'dial_code' => '+39'],
            ['name' => 'Japan', 'iso2' => 'JP', 'dial_code' => '+81'],
            ['name' => 'Kenya', 'iso2' => 'KE', 'dial_code' => '+254'],
            ['name' => 'Latvia', 'iso2' => 'LV', 'dial_code' => '+371'],
            ['name' => 'Lithuania', 'iso2' => 'LT', 'dial_code' => '+370'],
            ['name' => 'Luxembourg', 'iso2' => 'LU', 'dial_code' => '+352'],
            ['name' => 'Malaysia', 'iso2' => 'MY', 'dial_code' => '+60'],
            ['name' => 'Mexico', 'iso2' => 'MX', 'dial_code' => '+52'],
            ['name' => 'Morocco', 'iso2' => 'MA', 'dial_code' => '+212'],
            ['name' => 'Netherlands', 'iso2' => 'NL', 'dial_code' => '+31'],
            ['name' => 'New Zealand', 'iso2' => 'NZ', 'dial_code' => '+64'],
            ['name' => 'Nigeria', 'iso2' => 'NG', 'dial_code' => '+234'],
            ['name' => 'North Macedonia', 'iso2' => 'MK', 'dial_code' => '+389'],
            ['name' => 'Norway', 'iso2' => 'NO', 'dial_code' => '+47'],
            ['name' => 'Pakistan', 'iso2' => 'PK', 'dial_code' => '+92'],
            ['name' => 'Peru', 'iso2' => 'PE', 'dial_code' => '+51'],
            ['name' => 'Philippines', 'iso2' => 'PH', 'dial_code' => '+63'],
            ['name' => 'Poland', 'iso2' => 'PL', 'dial_code' => '+48'],
            ['name' => 'Portugal', 'iso2' => 'PT', 'dial_code' => '+351'],
            ['name' => 'Romania', 'iso2' => 'RO', 'dial_code' => '+40'],
            ['name' => 'Russia', 'iso2' => 'RU', 'dial_code' => '+7'],
            ['name' => 'Saudi Arabia', 'iso2' => 'SA', 'dial_code' => '+966'],
            ['name' => 'Serbia', 'iso2' => 'RS', 'dial_code' => '+381'],
            ['name' => 'Singapore', 'iso2' => 'SG', 'dial_code' => '+65'],
            ['name' => 'Slovakia', 'iso2' => 'SK', 'dial_code' => '+421'],
            ['name' => 'Slovenia', 'iso2' => 'SI', 'dial_code' => '+386'],
            ['name' => 'South Africa', 'iso2' => 'ZA', 'dial_code' => '+27'],
            ['name' => 'South Korea', 'iso2' => 'KR', 'dial_code' => '+82'],
            ['name' => 'Spain', 'iso2' => 'ES', 'dial_code' => '+34'],
            ['name' => 'Sri Lanka', 'iso2' => 'LK', 'dial_code' => '+94'],
            ['name' => 'Sweden', 'iso2' => 'SE', 'dial_code' => '+46'],
            ['name' => 'Switzerland', 'iso2' => 'CH', 'dial_code' => '+41'],
            ['name' => 'Thailand', 'iso2' => 'TH', 'dial_code' => '+66'],
            ['name' => 'Turkey', 'iso2' => 'TR', 'dial_code' => '+90'],
            ['name' => 'Ukraine', 'iso2' => 'UA', 'dial_code' => '+380'],
            ['name' => 'United Arab Emirates', 'iso2' => 'AE', 'dial_code' => '+971'],
            ['name' => 'United Kingdom', 'iso2' => 'GB', 'dial_code' => '+44'],
            ['name' => 'United States', 'iso2' => 'US', 'dial_code' => '+1'],
            ['name' => 'Vietnam', 'iso2' => 'VN', 'dial_code' => '+84'],
        ];

        Country::query()->truncate();

        foreach ($countries as $country) {
            Country::query()->create($country);
        }
    }
}
