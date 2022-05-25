<?php

function jabc_display_prefix() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    echo $prefix;
} ?>