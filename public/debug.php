<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

echo '<pre>';

$path = __DIR__ . '/../src/Entity/Product.php';
echo "Path: $path\n";
echo "file_exists: " . (file_exists($path) ? 'YES' : 'NO') . "\n";

echo "Trying require_once...\n";
require_once $path;
echo "Required OK\n";

echo "class_exists(App\\Entity\\Product): ";
var_dump(class_exists(\App\Entity\Product::class));