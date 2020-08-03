<?php

// Home
Breadcrumbs::for('front_home', function ($trail) {
    $trail->push('Home', url('/'));
});