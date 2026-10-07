<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use SocialiteProviders\Apple\Provider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        // Answer every Gate ability that matches a permission slug from the
        // database. Permissions created through the admin CRUD become
        // gateable immediately; non-permission abilities fall through to
        // their regular gates/policies. The admin role bypasses via
        // HasRoles::hasPermission().
        Gate::before(function (User $user, string $ability) {
            return Permission::query()->where('slug', $ability)->exists()
                ? $user->hasPermission($ability)
                : null;
        });

        // @stylex('button', 'buttonPrimary', 'mt-4') => "x1 x2 x3 mt-4"
        Blade::directive('stylex', function (string $expression) {
            return "<?php echo cls({$expression}); ?>";
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->getKey() ?: $request->ip());
        });

        RateLimiter::for('api-login', function (Request $request) {
            return Limit::perMinute(5)->by(
                Str::transliterate(Str::lower((string) $request->input('email', ''))).'|'.$request->ip()
            );
        });

        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('apple', Provider::class);
        });
    }
}
