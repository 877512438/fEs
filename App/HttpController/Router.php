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


        /** TABLE DATAS */
        $routeCollector->get('/model/lists/{table_name}','/Database/DataLists/lists');
        $routeCollector->get('/model/fields/{table_name}','/Database/DataLists/fields');
        $routeCollector->get('/model/add/{table_name}','/Database/DataLists/add');
        $routeCollector->get('/model/edit/{table_name}','/Database/DataLists/edit');
        $routeCollector->get('/model/del/{table_name}','/Database/DataLists/del');

        /** custom */

    }

}