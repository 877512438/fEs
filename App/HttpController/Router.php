<?php


namespace App\HttpController;




use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {
        // TODO: Implement initialize() method.
        $routeCollector->get('/','/index');
        $routeCollector->get('/ct','/Database/Datatable/createTable');
        $routeCollector->get('/db/hastb/{dbName}/{tableName}','/Database/Datatable/hasTable');
    }

}