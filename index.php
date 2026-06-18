<?php
/**
 * force login to kirby
 * and redirect to the origin path location
 */

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Url;
use Kirby\Panel\Panel;
use Kirby\Toolkit\Str;

$resolveIsActive = static function (): bool {
    $isActive = kirby()->option('andrekelling.force-login.is-active');

    if (is_bool($isActive)) {
        return $isActive;
    }

    if (is_callable($isActive)) {
        return (bool)$isActive();
    }

    // Defensive fallback for invalid option types: keep plugin inactive.
    return false;
};

Kirby::plugin('andrekelling/force-login', [
    'options' => array(
        'is-active'         => false,
    ),
    'hooks' => [
        'route:before' => function () use ($resolveIsActive) {
            if ($resolveIsActive() !== true) {
                return;
            }
            if (kirby()->user()) {
                return;
            }

            $panelUrl = Panel::url();
            $currentUrl = Url::current();
            $isPanelUrl = Str::startsWith($currentUrl, $panelUrl);

            $isApiUrl = Str::startsWith(Url::path(), 'api', true );

            if ($isPanelUrl || $isApiUrl) {
                return;
            }

            go($panelUrl.'/login?redirectAfterLogin=' . urlencode(kirby()->request()->path()));
        },
        'route:after' => function () use ($resolveIsActive) {
            if ($resolveIsActive() !== true) {
                return;
            }
            if (!kirby()->user()) {
                return;
            }
            $query = kirby()->request()->query()->data();
            if (array_key_exists('redirectAfterLogin', $query)) {
                go($query['redirectAfterLogin']);
            }
        }
    ]
]);
