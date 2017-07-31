<?php
namespace DevTics\LaravelHelpers\Utils;

use Maatwebsite\Excel\Facades\Excel; 

class Exportable {
    public static function export($config, $results) {
        $keyExport = $config['keyExport'] ? $config['keyExport'] : 'export';
        if(isset($_GET[$keyExport])) {
            $filename = $config['filename'] ? $config['filename'] : 'filename';
            $keyForceDownload = $config['keyForceDownload'] ? $config['keyForceDownload'] : 'keyForceDownload';
            $keyType = $config['keyType'] ? $config['keyType'] : 'keyType';
            $type =  isset($_GET[$keyType]) && in_array($_GET[$keyType], [
                'xlsx', 'xlsm', 'xltx', 'xltm', 'xls', 'xlt', 'ods', 'ots',
                'slk', 'xml', 'gnumeric', 'htm', 'html', 'csv', 'txt', 'pdf'
            ]) ? $_GET[$keyType] : 'xls';
            $excel = Excel::create($filename, function($excel)  use ($config, $results) {
                $title = $config['title'] ? $config['title'] : 'title';
                $creator = $config['creator'] ? $config['creator'] : 'creator';
                $company = $config['company'] ? $config['company'] : 'company';
                $description = $config['description'] ? $config['description'] : 'description';
                
               
                $parsers = $config['parsers'] ? $config['parsers'] : 'parsers';
                $sheetname = $config['sheetname'] ? $config['sheetname'] : 'sheetname';                
                $cols = $config['cols'] ? $config['cols'] : 'cols';                
                $excel->setTitle($title);
                $excel->setCreator($creator)
                      ->setCompany($company);
                $excel->setDescription($description);
              
                $excel->sheet($sheetname, function($sheet) use ($results, $cols, $parsers) {
                    $titles = [];
                    foreach ($cols as $c) {
                        $titles[] = $c[1];
                    }
                    $iRow = 1;
                    $sheet->appendRow($iRow++, $titles);
                    foreach ($results as $i => $r) {
                        $row = [];
                        foreach ($cols as $c) {
                            $aR = (array) $r;
                            if(isset($parsers[$c[0]])) {
                                $row[] = $parsers[$c[0]]($aR[ $c[0] ]);
                            } else {
                                $row[] = $aR[$c[0]];
                            }
                        }
                        $sheet->appendRow($iRow, $row);
                        $iRow++;
                    }
                });
            });
            if(isset($_GET[$keyForceDownload])){
                $excel->download($type);
            }
            $excel->export($type);
            die();
        }
        return $results;
    }
}