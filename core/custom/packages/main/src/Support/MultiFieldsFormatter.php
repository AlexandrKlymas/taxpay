<?php

namespace EvolutionCMS\Main\Support;
class MultiFieldsFormatter
{

    public static $rowAble = ['row','box'];


    public static function multiFieldsChildren($data,$config,$index = 0,$group =false,$parentSingleItem=false){
        $prepare = [];


        if (is_array($data)) {

            foreach ($data as $key => $item) {
                $blockName = $item['name'];
                $blockValue = $item['value'];

                $blockConfig = $config[$blockName];

                if ($blockConfig['items']) {
                    $nextIndex = (int) $index + 1;
                    $singleItem = count($blockConfig['items']) === 1;
                    $canDuplicated = in_array($item['type'],self::$rowAble) && $blockConfig['actions'] !== false && (empty($blockConfig['actions']) || in_array('add',$blockConfig['actions']));


                    $response = self::multiFieldsChildren($item['items'], $blockConfig['items'], $nextIndex,false,$singleItem);

                    if(
                        $group ||
                        $index === 0 ||
                        ($parentSingleItem === true && $canDuplicated)
                    ){
                        $type = 'onlyArray';
                    }
                    else if($parentSingleItem === false && $canDuplicated){
                        $type = 'nameAndKey';

                    }
                    else{
                        $type = 'key';
                    }

                    if(in_array($type,['onlyArray','nameAndKey'])){
                        $response = array_merge(array_filter([
                            '_name' => $blockName,
                            '_value' => $blockValue
                        ]), $response);
                    }


                    if($type == 'onlyArray'){
                        $prepare[] = $response;
                    }
                    else if($type == 'nameAndKey'){
                        $prepare[$item['name']][] = $response;
                    }
                    elseif($type == 'key'){
                        $prepare[$item['name']] = $response;
                    }


                } else if ($blockConfig['group_items']) {
                    $response = self::multiFieldsChildren($item['items'], $blockConfig['group_items'], $index + 1, true,false);
                    $prepare[$item['name']] = $response;
                } else {

                    $prepare[$item['name']] = $item['value'];
                }
            }

        }


        return $prepare;
    }
    public static function multiFields($data,$tvName,$docId = null)
    {
        if(isset($docId)){
            $_REQUEST['mf_doc_id'] = $docId;
        }

        $file = MODX_BASE_PATH. 'assets/plugins/multifields/config/'.$tvName.'.php';
        if(!file_exists($file)){
            throw new \Exception('File '.$file.' not found');
        }
        $config = require $file;

        $templates = $config['templates'] ?? [];

        $templates = self::multiFieldsChangeTemplatesToItems($templates,$templates);

        $result = self::multiFieldsChildren($data,$templates);

        unset($_REQUEST['mf_doc_id']);

        return $result;
    }

    private static function multiFieldsChangeTemplatesToItems(array $templates, array $originalConfig)
    {
        foreach ($templates as $key => $template) {
            if(isset($template['templates'])){
                foreach ($template['templates'] as $childKey => $childTemplateName) {
                    $childTemplate = $originalConfig[$childTemplateName];
                    if(empty($childTemplate)){
                        continue;
                    }
                    $templates[$key]['group_items'][$childTemplateName] = $childTemplate;
                }
                unset($templates[$key]['templates']);
            }
            if(isset($template['items'])){
                $templates[$key]['items'] = self::multiFieldsChangeTemplatesToItems($template['items'],$originalConfig);
            }
        }
        return $templates;
    }
}