<?php

namespace WPBR\App;

Core()->settings->defineDefaults( [
    'slotLength'   => '15 mins',
    'cancellationLimit' => '15 mins',
    'defaultStatus' => 'pending',
] );
