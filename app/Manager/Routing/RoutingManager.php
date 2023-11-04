<?php

namespace App\Manager\Routing;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;

class RoutingManager extends OriginalRegistrar
{
    // add data to the array
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'bulkUpdate', 'import'];


    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceBulkUpdate($name, $base, $controller, $options)
    {
        $uri = 'bulk-update/'.$this->getResourceUri($name);

        $action = $this->getResourceAction($name, $controller, 'bulkUpdate', $options);

        return $this->router->patch($uri, $action);
    }

    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceImport($name, $base, $controller, $options)
    {
        $uri = 'import/'.$this->getResourceUri($name);

        $action = $this->getResourceAction($name, $controller, 'import', $options);

        return $this->router->post($uri, $action);
    }
}
