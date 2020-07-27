<?
/**
 * User: mxaleev
 * Date: 08.07.2020
 * Time: 20:27
 * @author Mikhail Khaleev <info@xaleev.ru>
 */
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Highloadblock as HL;

class hlEmptyListComponents extends CBitrixComponent
{

    private $_request;

    private $hlbBLockName = [
        'ID' => 1,
        'NAME' => 'HLBlockName',
        'TABLE_NAME' => 'HLTableName'
    ];


    /**
     * Получение основных данных из БД
     *
     * @return mixed
     */
    function selectData()
    {
        $entity = HL\HighloadBlockTable::compileEntity($this->hlbBLockName);
        $entityDataClass = $entity->getDataClass();

        $resData = $entityDataClass::getList([
            'select' => ['*'],
            'filter' => [],
            'order' => [
                'ID' => 'DESC'
            ]
        ]);

        return $this->arResultModifier($resData->fetchAll());
    }


    /**
     * Модификация $arResult
     *
     * @param $arResult
     * @return mixed
     */
    private function arResultModifier($arResult)
    {
        return $arResult;
    }


    /**
     * Проверка наличия модулей требуемых для работы компонента
     *
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {

        if (!Loader::includeModule('iblock') || !Loader::includeModule('highloadblock'))
        {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }

        return true;
    }


    /**
     * Обертка над глобальной переменной
     *
     * @return CAllMain|CMain
     */
//    private function _app()
//    {
//        global $APPLICATION;
//        return $APPLICATION;
//    }


    /**
     * Обертка над глобальной переменной
     * @return CAllUser|CUser
     */
    private function _user()
    {
        global $USER;
        return $USER;
    }


    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }


    public function executeComponent()
    {
        // Массив основных данных
        $arrData = [];

        // Проверка наличия необходимых модулей
        $this->_checkModules();

        $this->_request = Application::getInstance()->getContext()->getRequest();

        $obCache = new CPHPCache();
        $cacheLifetime = $this->arParams['CACHE_TIME'];
        // $cacheID = 'module_hl' . $this->arParams['HLB_ID'] . 'user' . $this->arParams['CLIENT_ID'] . $this->_user()->GetUserGroupString();
        $cacheID = 'module_hl' . $this->arParams['HLB_ID'];
        $cachePath = '/module';

        // $arrData = $this->selectData();

        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
        {
            $vars = $obCache->GetVars();
            $arrData = $vars['arrData'];
        }
        elseif($obCache->StartDataCache())
        {
            $arrData = $this->selectData();
            $obCache->EndDataCache(['arrData' => $arrData]);
        }

        $this->arResult = array_merge($this->arResult, $arrData);

        $this->includeComponentTemplate();

    }

}
