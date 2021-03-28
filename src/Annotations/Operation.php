<?php

namespace Vyuldashev\LaravelOpenApi\Annotations;

use Illuminate\Support\Arr;
use Vyuldashev\LaravelOpenApi\Factories\SecuritySchemeFactory;
use InvalidArgumentException;

/**
 * @Annotation
 *
 * @Target({"METHOD"})
 */
class Operation
{
    /** @var string */
    public $id;

    /** @var array<string> */
    public $tags;

    /** @var string */
    public $method;

    /** @var string|SecuritySchemeFactory|null */
    public $security;

    public function __construct($values)
    {
        $this->id = Arr::get($values, 'id');

        $tag = Arr::get($values, 'tags');
        $this->tags = is_string($tag) ? [$tag] : [];
        $this->method = Arr::get($values, 'method');

        if (!is_null(Arr::get($values, 'security'))) {
            $security = Arr::get($values, 'security');
            $this->security = class_exists($security)
                ? $security
                : app()->getNamespace() . 'OpenApi\\SecuritySchemes\\' . $security . 'SecurityScheme';
            if (!is_a($this->security, SecuritySchemeFactory::class, true)) {
                throw new InvalidArgumentException(
                    sprintf('Security class is either not declared or is not an instance of [%s]', SecuritySchemeFactory::class)
                );
            }
        }
    }


}
