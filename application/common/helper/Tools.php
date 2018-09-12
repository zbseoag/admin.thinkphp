<?php
/**
 * 助手类
 * @package app\common\helper
 * @author zbseoag
 * @date 2018-04-24
 */

namespace app\common\helper;
use think\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class Tools {

    
    /**
     * 文件上传
     * @param $file 上传文件名
     * @param string $classify 分类
     * @param string $ext 扩展名
     * @param string $size 文件大小(MB)
     * @return bool|object
     */
    public static function upload($file, $rule = 'date', $validate = ['ext'=>'jpg,png,gif,xls,xlsx,csv,pdf']){
        
        $files = request()->file($file);
        if(empty($files)) return false;
        
        if(!is_array($files)) $files = array($files);
    
        $name = $rule == 'same'? false : true;
        foreach($files as $file){
            
            if($rule) $file->rule($rule);
            $info = $file->validate($validate)->move(UPLOAD_PATH, $name);
            if(!$info) throw new Exception($file->getError());
        }
        
        return $info;
        
    }
    
    /**
     * 读取excel文件
     * @param $file
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function read($file){
        
        return IOFactory::load($file)->getActiveSheet()->toArray(null, true, true, true);
    }
    
    
    /**
     * 导出excel文件
     * @param $head
     * @param $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function export($file, $head, $data){
        
        array_unshift($data, $head);
        $head = array_keys($head);
        
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->setActiveSheetIndex(0);
        $worksheet->setTitle('sheet1');
        
        //生成列ABC...
        $column = self::getExcelColumn(1, count($head));
        foreach($data as $key => $row){
            foreach($head as $idx => $field){
                $worksheet->setCellValue($column[$idx + 1] . ($key + 1), $row[$field]);
            }
        }
        
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if($ext == 'xls') $ext = 'xlsx';
        $content = array(
            'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ods'   => 'application/vnd.oasis.opendocument.spreadsheet',
            'csv'   => 'text/csv',
            'html'  => 'text/html',
            'pdf'   => 'application/pdf',
        );
        
        if($ext == 'pdf') IOFactory::registerWriter('Pdf', Mpdf::class);
        
        header('Content-Type: '. $content[$ext]);
        header('Content-Disposition: attachment;filename="'.$file.'"');
        header('Cache-Control: max-age=0');
        
        $writer = IOFactory::createWriter($spreadsheet, ucfirst($ext));
        $writer->save('php://output');
        
        exit;
    }
    
   
    
    /**
     * 根据当前列号,返回 excel 列名
     * 例如: 第1列=>A, 第2列=>B 再与行号组成 A1 A2
     * @param int $i 列号
     * @param int $count 列数
     * @return string
     */
    public static function getExcelColumn($i, $count = 1){
        
        $max = $i + max($count, 1);
        for($i; $i < $max; $i++){
            
            $char1 = floor($i / 26);
            $char2 = $i % 26;
            if($i % 26 == 0) $char1--;
            if($char2 == 0) $char2 = 26;
    
            if($i <= 26){
                $result[$i] = chr(64 + $char2);
            }else{
                $result[$i] = chr(64 + $char1) . chr(64 + $char2);
            }
        }
        
        return ($count < 2)? current($result) : $result;
    }
    
    

}



