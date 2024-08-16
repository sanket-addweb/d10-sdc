<?php declare(strict_types = 1);

class Foo
{

    public function __construct(int $i) { }

    public function doFoo(): void
    {
        new self(1); // PHPStan reports: Unsafe usage of new static()
    }

}

class Bar extends Foo
{

    public function __construct(string $s) { }

}

(new Foo(1))->doFoo(); // works, returns Foo
(new Bar('s'))->doFoo(); // crashes with: Argument #1 ($s) must be of type string, int given