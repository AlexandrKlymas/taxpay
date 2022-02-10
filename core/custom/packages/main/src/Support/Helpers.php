<?php

namespace EvolutionCMS\Main\Support;


class Helpers
{

    public static function e($value){
        if(is_array($value)){

            foreach ($value as $key => $item) {
                $value[$key] = self::e($item);
            }
            return $value;
        }

        return htmlspecialchars($value);
    }

    public static function parsedRule($rule){
        if(($position = mb_strpos($rule,':')) !==false){
            $parts = [
                mb_substr($rule, 0, $position, 'utf-8'),
                mb_substr($rule,  $position+1, null, 'utf-8'),
            ];
        }
        else{
            $parts = [$rule];
        }

        $ruleName = array_shift($parts);
        $params = array_shift($parts);


        $rulesWithSingleParam = [
            'regex',
            'not_regex',
        ];

        if (in_array($ruleName, $rulesWithSingleParam)) {
            $params = [$params];
        }
        else{
            $params = explode(',', $params);
        }

        return [
            'name' => $ruleName,
            'params'=>$params
        ];
    }
    public static function mbUcfirst($string, $encoding = 'utf-8')
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }

    public static function multiFields($data, $index = 0, $parent = [])
    {
        $prepare = [];

        if (is_array($data)) {
            foreach ($data as $key => $item) {

                switch ($item['type']) {
                    case 'row':
                        $response = self::multiFields($item['items'], $index + 1, $item);

                        $response = array_merge([
                            'row_name' => $item['name'],
                            'row_value' => $item['value']??[],
                        ], $response);

                        if ($index == 0 || strpos($item['name'], '_group_item') !== false) {
                            $prepare[] = $response;
                        }else if ($index == 0 || strpos($item['name'], '_group_onwer') !== false) {
                            $prepare[$item['name']] = $response;
                        }
                        else {
                            $prepare[$item['name']][] = $response;
                        }

                        break;
                    default:
                        $prepare[$item['name']] = $item['value'];
                        break;
                }
            }
        }
        return $prepare;
    }
    public static function UltimateParent($top = 0,$topLevel = 0,$id = null){
        $evo = \EvolutionCMS();
        $id= isset ($id) && intval($id) ? intval($id) : $evo->documentIdentifier;


        if ($id && $id != $top) {
            $pid= $id;
            if (!$topLevel || count($evo->getParentIds($id)) >= $topLevel) {
                while ($parentIds= $evo->getParentIds($id, 1)) {
                    $pid= array_pop($parentIds);
                    if ($pid == $top) {
                        break;
                    }
                    $id= $pid;
                    if ($topLevel && count($evo->getParentIds($id)) < $topLevel) {
                        break;
                    }
                }
            }
        }
        return $id;
    }


    /**
     *  TODO Refactor ol code
     */
    public static function getUserGeo(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if(isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        if ($ipaddress != 'UNKNOWN') {
            $ipinfo = file_get_contents('https://ipinfo.io/'.$ipaddress.'/geo');
            return (!empty($ipinfo)) ? $ipinfo : '';
        }
        return '';
    }

    public static function formattedCheckId($checkId){
        $parts = str_split($checkId,4);
        return implode('-',$parts);
    }

    public static function getContAndMethodFromAction($action,$namespace = ''){
        $actionArray = explode(':',$action);

        $classArray = explode('_', $actionArray[0]);
        $classArray = array_map(
            function ($item) {
                $explodedTplName = explode('-', $item);
                $explodedTplName = array_map(
                    function ($item) {
                        return ucfirst(trim($item));
                    },
                    $explodedTplName
                );

                return join($explodedTplName);
            },
            $classArray
        );
        $class = $namespace.implode('\\', $classArray).'Controller';

        return [
            'class' => $class,
            'method'=>$actionArray[1]
        ];
    }

    public static function getCallableFromAction($action,$namespace){
        $classAndMethod = self::getContAndMethodFromAction($action,$namespace);
        $obj = \EvolutionCMS()->make($classAndMethod['class']);
        return [ $obj,$classAndMethod['method']];
    }

    public static function arrayTransformoWebixOptions(array $data, $idKey='',$valueKey='' ){
        $options = [];
        foreach ($data as $key => $value) {
            $options[] = [
                'id' => $idKey === ''? $key:$value[$idKey],
                'value' => $valueKey ===''?$value:$value[$valueKey],
            ];
        }
        return $options;
    }


    public static function getModuleId($alias){
        $modules = evolutionCMS()->modulesFromFile;

        foreach ($modules as $id => $module) {
            if($module['properties']['module_alias'] === $alias){
                return $id;
            }
        }

        throw new \Exception('Module not found');

    }

    public static function fileWithVersion($filePath){
        $v = '';
        if(file_exists(MODX_BASE_PATH.$filePath)){
            $v =  substr(md5(filemtime(MODX_BASE_PATH.$filePath)),0,5);
        }

        return '/'.$filePath.'?v='.$v;
    }
}