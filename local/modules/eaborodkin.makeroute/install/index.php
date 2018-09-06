<?php

use \Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\SystemException;

class Eaborodkin_Makeroute extends CModule
{
    public $MODULE_ID = 'eaborodkin.makeroute';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = "Y";

    public function __construct()
    {
        $arModuleVersion = array();

        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path . '/version.php');

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('EABORODKIN_MAKEROUTE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('EABORODKIN_MAKEROUTE_MODULE_DESCRIPTION');
    }

    /**
     * @throws SystemException
     */
    function DoInstall()
    {
        if (IsModuleInstalled($this->MODULE_ID)) throw new SystemException("Модуль \"{$this->MODULE_NAME}\" уже установлен в системе!");
        if(!CheckVersion(ModuleManager::getVersion("main"), "14.00.00")) throw new SystemException('Необходима версия ядра 1С-Битрикс 14+!');

        $this->installFiles();
        $this->installEvents();
        $this->installDB();

        if (!ModuleManager::isModuleInstalled($this->MODULE_ID)) ModuleManager::registerModule($this->MODULE_ID);

        $GLOBALS['APPLICATION']->includeAdminFile(
            Loc::getMessage('EABORODKIN_MAKEROUTE_INSTALL_TITLE'),
            Application::getDocumentRoot() . "/local/modules/{$this->MODULE_ID}/install/step.php"
        );

        return false;
    }

    function installDB()
    {
       return true;
    }

    function installEvents()
    {
        return true;
    }

    function installFiles()
    {
        return true;
    }

    function DoUninstall()
    {
        global $APPLICATION;
        $this->uninstallDB();
        $this->uninstallEvents();
        $this->uninstallFiles();

        if (ModuleManager::isModuleInstalled($this->MODULE_ID)) ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->includeAdminFile(
            Loc::getMessage('EABORODKIN_MAKEROUTE_UNINSTALL_TITLE'),
            Application::getDocumentRoot() . "/local/modules/{$this->MODULE_ID}/install/unstep.php"
        );

        return false;
    }

    function uninstallDB()
    {
        return true;
    }

    function uninstallEvents()
    {
        return true;
    }

    function uninstallFiles()
    {
        return true;
    }

}
