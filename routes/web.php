<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'v1'], function ($app) {

    /* User public profile */
    $app->get('/user/{id}/public/info','PublicController@userProfile');
    /*-----------------------------------------------------------------------------------------------------------------------------------------
    |                                           Service MODULE
     -----------------------------------------------------------------------------------------------------------------------------------------*/
    /* service */
    $app->get('/services', 'ServiceController@index');
    $app->get('/services/categories', 'ServiceController@serviceWithCategory');
    $app->get('/service/{title}/types-categories', 'ServiceTypeCategoryController@serviceWithTypeHerCategory');

    /* type */
    $app->get('/categories', 'CategoryController@index');

    /* category */
    $app->get('/types', 'TypeController@index');

    /* package */
    $app->get('/packages', 'PackageController@index');

    /* accessor */
    $app->get('/accessors', 'AccessorsController@index');

    /* field and field-options */
    $app->get('/fields-options/service/{service_id}/group/{group_id}', 'FieldController@fieldWithOption');
    $app->get('/fields-options/groups/service/{service_id}', 'FieldController@groupsWithfieldFieldOptions');

    /* display position */
    $app->get('/displays', 'DisplayController@displayDependOnServiceTypeCategory');

    /* city */
    $app->get('/cities', 'CityController@cities');

    /* location */
    $app->get('/city/{city_title}/area', 'CityController@area');

    /* accessors */
    $app->get('/popular/agent', 'PopularAgentController@popular');

    /* directory */
    $app->get('/popular/{service_title}/agent', 'ServicePopularAgentController@popular');
    $app->get('/service/{service_title}/business-type/directory/users', 'ServiceDirectoryUserController@directoryWithUser');


    /*-----------------------------------------------------------------------------------------------------------------------------------------
    |                                           Service MODULE
     -----------------------------------------------------------------------------------------------------------------------------------------*/

    /*-----------------------------------------------------------------------------------------------------------------------------------------
    |                                           ProductRepo MODULE
     -----------------------------------------------------------------------------------------------------------------------------------------*/
    $app->get('/product/search', 'ProductSearchController@result');
    $app->get('/product/max/min', 'ProductSearchController@maxMinProducts');
    $app->get('/home/products', 'HomeProductController@index');
    $app->get('/{service_title}/product/{id}[/{title}]', 'ProductDetailController@details');
    $app->get('/service/{service_title}/products', 'ServiceProductController@index');
    $app->get('/featured/products', 'FeatureProductController@products');
    $app->get('/popular/products', 'PopularProductController@popularProducts');
    $app->get('/counties','CountyController@counties');
    $app->get('/states','StateController@states');
    $app->get('/state/{state_title}/service/category/total-product','ServiceProductController@totalProductDependOnState');
    /*-----------------------------------------------------------------------------------------------------------------------------------------
    |                                           ProductRepo MODULE
     -----------------------------------------------------------------------------------------------------------------------------------------*/

    $app->group(['middleware' => 'auth:api'], function ($app) {

        /* User Product */
        $app->get('/user/products/list', 'UserDashboardController@index');
        $app->get('/user/total/products', 'UserDashboardController@totalProduct');
        $app->get('/user/total/sell', 'UserDashboardController@totalSell');
        $app->get('/user/total/days/sell', 'UserDashboardController@totalSellByDays');
        $app->get('/user/total/months/sell', 'UserDashboardController@totalSellByMonth');
        $app->get('/user/products/total/view', 'UserDashboardController@totalView');
        $app->get('/user/products/days/total/view', 'UserDashboardController@totalViewByDays');
        $app->get('/user/products/months/total/view', 'UserDashboardController@totalViewByMont');
        $app->get('/user/popular/products/{days}', 'UserProductController@mostPopular');
        $app->get('/user/sell/products/{days}', 'UserProductController@mostRecentSell');

        /* User Product Delete */
        $app->delete('user/product/{id}/delete','UserProductController@delete');

        /* User Message */
        $app->get('/user/messages', 'UserMessageController@index');
        $app->get('/message/user/list','UserMessageController@userList');
        $app->get('/user/{sender_id}/messages/list','UserMessageController@messageList');

        /* User Package */
        $app->get('/user/packages','UserPackageController@index');
    });
});
