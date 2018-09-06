<?php
/**
 * Created by PhpStorm.
 * User: eaborodkin
 * Date: 05.09.18
 * Time: 18:03
 */

if (empty($_SERVER["DOCUMENT_ROOT"])) {
    $arPath = explode(DIRECTORY_SEPARATOR, __DIR__);
    end($arPath);
    $arPath[key($arPath)] = 'public_html';

    $_SERVER["DOCUMENT_ROOT"] = implode(DIRECTORY_SEPARATOR, $arPath);
}

require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Application;

// @todo организовать автозагрузку
include Application::getDocumentRoot() . '/local/modules/eaborodkin.makeroute/lib/Dijkstra.php';
include Application::getDocumentRoot() . '/local/modules/eaborodkin.makeroute/lib/GraphCollection.php';
include Application::getDocumentRoot() . '/local/modules/eaborodkin.makeroute/lib/Edge.php';
include Application::getDocumentRoot() . '/local/modules/eaborodkin.makeroute/lib/Vertex.php';
include Application::getDocumentRoot() . '/local/modules/eaborodkin.makeroute/lib/WeightValue.php';
include Application::getDocumentRoot() . '/local/modules/eaborodkin.makeroute/lib/City.php';
include Application::getDocumentRoot() . '/local/modules/eaborodkin.makeroute/lib/CityWeightValue.php';
include Application::getDocumentRoot() . '/local/modules/eaborodkin.makeroute/lib/CityEdge.php';




use Eaborodkin\MakeRoute\{
    Dijkstra, GraphCollection, City, CityEdge
};

try {
    $oGraph = new GraphCollection();

    $A = new City('Москва', 55.5807419, 36.8237715);
    $B = new City('Белгород', 50.5894814, 36.5029236);
    $C = new City('Тула', 54.1847323, 37.4870225);
    $D = new City('Караганда', 49.8238976, 73.028544);
    $E = new City('Нью-Йорк', 40.6971478, -74.2605473);
    $F = new City('Дели', 28.6921151, 76.8104785);

    $oGraph->attach(new CityEdge($A, $B));
    $oGraph->attach(new CityEdge($A, $D));
    $oGraph->attach(new CityEdge($A, $F));

    $oGraph->attach(new CityEdge($B, $A));
    $oGraph->attach(new CityEdge($B, $D));
    $oGraph->attach(new CityEdge($B, $E));

    $oGraph->attach(new CityEdge($C, $E));
    $oGraph->attach(new CityEdge($C, $F));

    $oGraph->attach(new CityEdge($D, $A));
    $oGraph->attach(new CityEdge($D, $B));
    $oGraph->attach(new CityEdge($D, $E));
    $oGraph->attach(new CityEdge($D, $F));

    $oGraph->attach(new CityEdge($E, $B));
    $oGraph->attach(new CityEdge($E, $C));
    $oGraph->attach(new CityEdge($E, $D));
    $oGraph->attach(new CityEdge($E, $F));

    $oGraph->attach(new CityEdge($F, $A));
    $oGraph->attach(new CityEdge($F, $C));
    $oGraph->attach(new CityEdge($F, $D));
    $oGraph->attach(new CityEdge($F, $E));

    $oDijkstra = new Dijkstra($oGraph);


    $optimalStack = $oDijkstra->shortestPath($D, $C);

    if ($optimalStack->isEmpty()) throw new Exception('Путь не найден!');

    $arChain = [];
    while (!$optimalStack->isEmpty()) {
        $arChain[] = $optimalStack->pop();
    }

    echo '<xmp>';
    echo implode(' → ', $arChain);
    echo '</xmp>';

} catch (Exception $exception) {
    echo $exception->getMessage();
}