<?php
/**
 * Created by PhpStorm.
 * User: eaborodkin
 * Date: 01.09.18
 * Time: 10:03
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


use Eaborodkin\MakeRoute\{
    Dijkstra, GraphCollection, Edge, Vertex, WeightValue
};


/**
 * Функция поиска самого дешевого маршрута
 *
 * @param string $from населённый пункт отправления
 * @param string $to населённый пункт назначения
 * @param array $arRoutes массив маршрутов с элементами вида [откуда, куда, стоимость]
 * @return string
 * @throws Exception
 */
function getCheapestRoute(string $from, string $to, array $arRoutes): string
{
    // Будущий массив населённых пунктов вида: населённый пункт => объект населённого пункта
    $arVertexObjects = [];

    $oGraph = new GraphCollection();

    $arVertexObjects[$from] = new Vertex($from);
    $arVertexObjects[$to] = new Vertex($to);

    if (!is_string($from))
        throw new Exception("Населённый пункт отправления(1-й параметр функции) должен быть строкового типа.");
    if (!is_string($to))
        throw new Exception("Населённый пункт назначения(2-й параметр функции) должен быть строкового типа.");
    foreach ($arRoutes as $k => $arRoute) {
        if (!is_string($arRoute[0]))
            throw new Exception("В $k-й строке массива маршрутов(3-й параметр функции) 
            населённый пункт отправления должен быть строкового типа.");
        if (!is_string($arRoute[1]))
            throw new Exception("В $k-й строке массива маршрутов(3-й параметр функции) 
            населённый пункт назначения должен быть строкового типа.");
        if (!is_numeric($arRoute[2]))
            throw new Exception("В $k-й строке массива маршрутов(3-й параметр функции) 
            стоимость проезда от одного населенного пункта до другого должна быть целым или дробным числом.");


        if (!in_array($arRoute[0], $arVertexObjects)) $arVertexObjects[$arRoute[0]] = new Vertex($arRoute[0]);
        if (!in_array($arRoute[1], $arVertexObjects)) $arVertexObjects[$arRoute[1]] = new Vertex($arRoute[1]);

        $oGraph->attach(new Edge($arVertexObjects[$arRoute[0]], $arVertexObjects[$arRoute[1]], new WeightValue($arRoute[2])));
        $oGraph->attach(new Edge($arVertexObjects[$arRoute[1]], $arVertexObjects[$arRoute[0]], new WeightValue($arRoute[2])));
    }

    $oDijkstra = new Dijkstra($oGraph);


    $optimalStack = $oDijkstra->shortestPath($arVertexObjects[$from], $arVertexObjects[$to]);

    if (is_null($optimalStack)) return 'Путь не найден!';

    $arChain = [];
    while (!$optimalStack->isEmpty()) {
        $arChain[] = $optimalStack->pop();
    }

    return implode(' → ', $arChain);
}

try {
    $arRequest = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    $from = 'C';
    $to = 'A';

    $from = $arRequest['from'] ?? $from;
    $to = $arRequest['to'] ?? $to;

    echo "Маршрут из $from в $to:\n";

    echo getCheapestRoute(
        $from,
        $to,
        [
            ['A', 'B', 3],
            ['A', 'D', 3],
            ['A', 'F', 6],
            ['B', 'D', 1],
            ['B', 'E', 3],
            ['C', 'E', 2],
            ['C', 'F', 3],
            ['D', 'E', 1],
            ['D', 'F', 2],
            ['E', 'F', 5],
        ]
    );

} catch (Exception $exception) {
    echo $exception->getMessage();
}