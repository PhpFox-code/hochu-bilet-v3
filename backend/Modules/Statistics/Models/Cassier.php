<?php
/**
 * Created by PhpStorm.
 * User: nikolaev
 * Date: 25.01.2016
 * Time: 16:24
 */

namespace backend\Modules\Statistics\Models;

use backend\Modules\Afisha\Models\Afisha;
use Core\Config;

class Cassier
{
    public static function getExcel($orders, $afisha)
    {
        $countOrders = $countPlaces = $totalCost = $countBrone = $countExpired = $countPayed = 0;
        $pay_statuses = Config::get('order.pay_statuses');
        $xls = new \PHPExcel();
        $xls->setActiveSheetIndex();
        $sheet = $xls->getActiveSheet();

        $sheet->setTitle($afisha->name);

//        Header
        $sheet->mergeCells('A1:F1')
            ->setCellValue('A1', $afisha->name.'('.date('d-m-Y H:i:s').')');

        $sheet->setCellValue('A2', 'Номер заказа');
        $sheet->setCellValue('B2', 'Название события');
        $sheet->setCellValue('C2', 'Количество мест');
        $sheet->setCellValue('D2', 'Сумма заказа');
        $sheet->setCellValue('E2', 'Дата');
        $sheet->setCellValue('F2', 'Статус');

        $offset = 2;
        $row = 1;
        foreach ( $orders as $key => $obj ) {
            $row = $offset + ($key + 1);
            $places = count(array_filter(explode(',', $obj->seats_keys)));
            $cost = Afisha::getTotalCost($obj);

            if ($obj->status == 'success') {
                $status = $pay_statuses['success'];
                $countPayed++;
            }
            else if ($obj->created_at > time() - Config::get('reserved_days') * 24 * 60 * 60
                AND $obj->status != 'success') {
                $status = $pay_statuses['brone'];
                $countBrone++;
            }
            else if ($obj->created_at < time() - Config::get('reserved_days') * 24 * 60 * 60
                AND $obj->status != 'success') {
                $status = $pay_statuses['expired'];
                $countExpired++;
            }
            else {
                $status = 'Не оплачено';
            }

            $sheet->setCellValueByColumnAndRow(0, $row, $obj->id);
            $sheet->setCellValueByColumnAndRow(1, $row, $afisha->name);
            $sheet->setCellValueByColumnAndRow(2, $row, $places);
            $sheet->setCellValueByColumnAndRow(3, $row, number_format($cost, 0, '.', ' ').' грн');
            $sheet->setCellValueByColumnAndRow(4, $row, date( 'd.m.Y H:i', $obj->created_at ));

            $sheet->setCellValueByColumnAndRow(5, $row, $status);

//            Calc
            $countOrders++;
            $countPlaces += $places;
            $totalCost += $cost;
        }
//        Footer
        $row++;
        $sheet->setCellValueByColumnAndRow(0, $row, 'Кол-во заказов: '.$countOrders);
        $sheet->setCellValueByColumnAndRow(1, $row, '');
        $sheet->setCellValueByColumnAndRow(2, $row, 'Кол-во мест: '.$countPlaces);
        $sheet->setCellValueByColumnAndRow(3, $row, 'Сумма: '.$totalCost);
        $sheet->setCellValueByColumnAndRow(4, $row, "Кол-во");
        $sheet->setCellValueByColumnAndRow(4, $row+1, "Забронированных: ".$countBrone);
        $sheet->setCellValueByColumnAndRow(4, $row+2, "Просроченных: ".$countExpired);
        $sheet->setCellValueByColumnAndRow(4, $row+3, "Оплаченных: ".$countPayed);

//        Set auto width
        foreach (range('A', $xls->getActiveSheet()->getHighestDataColumn()) as $col) {
            $xls->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }

//        Save file
        // Выводим HTTP-заголовки
        header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header ( "Cache-Control: no-cache, must-revalidate" );
        header ( "Pragma: no-cache" );
        header ( "Content-type: application/vnd.ms-excel" );
        header ( "Content-Disposition: attachment; filename=".$afisha->alias.'_'.date('d-m-Y_H:i:s').".xls" );

// Выводим содержимое файла
        $objWriter = new \PHPExcel_Writer_Excel5($xls);
        $objWriter->save('php://output');
    }
}