<?php

namespace Vyuldashev\LaravelOpenApi\Builders\Paths\Operation;

use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement;
use Vyuldashev\LaravelOpenApi\Annotations\Operation as OperationAnnotation;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class SecurityBuilder
{
    public function build(RouteInformation $route): array
    {

        return collect($route->actionAnnotations)
            ->filter(static function ($annotation) {
                return $annotation instanceof OperationAnnotation;
            })
            ->filter(static function (OperationAnnotation $annotation) {
                return isset($annotation->security);
            })
            ->map(static function (OperationAnnotation $annotation) {
               $security = app($annotation->security);
               $scheme = $security->build();
               return SecurityRequirement::create()->securityScheme($scheme);
            })
            ->values()
            ->toArray();
    }
}
