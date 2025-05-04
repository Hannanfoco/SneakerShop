<?php
require __DIR__ . '/vendor/autoload.php';

if (class_exists(\OpenApi\Generator::class)) {
    echo "✅ OpenApi\\Generator is available!";
} else {
    echo "❌ Still not found.";
}
