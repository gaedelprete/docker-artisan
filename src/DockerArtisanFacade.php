<?php

namespace GaeDelPrete\DockerArtisan;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GaeDelPrete\DockerArtisan\Skeleton\SkeletonClass
 */
class DockerArtisanFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'docker-artisan';
    }
}
