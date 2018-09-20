<?php

namespace WPBR\App;

use WPBR\App\Hooks\Controllers;

Loader::load( [
    Controllers\Admin\Menu::class,
    Controllers\Admin\MenuSections::class,
    Controllers\Settings::class,
] );
