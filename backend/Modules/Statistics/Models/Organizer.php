<?php
/**
 * Created by PhpStorm.
 * User: nikolaev
 * Date: 22.01.2016
 * Time: 10:16
 */
namespace backend\Modules\Statistics\Models;

use Core\QB\DB;
use backend\Modules\Afisha\Models\Prices;
use backend\Modules\Afisha\Models\Orders;
use backend\Modules\Afisha\Models\Seats;

class Organizer
{
    public static function getOrganizers($status = null, $order_by = null)
    {
        $query = DB::select()->from('users')->where('role_id', '=', 9);
        if (null !== $status) {
            $query->where('status', '=', (int)$status);
        }
        if (null !== $order_by) {
            if (is_array($order_by) && count($order_by) == 2) {
                $query->order_by($order_by[0], $order_by[1]);
            } else {
                $query->order_by((string)$order_by);
            }
        }

        return $query->find_all();
    }

    public static function getOrganizerById($id, $status = null)
    {
        $query = DB::select()->from('users')->where('role_id', '=', 9)->where('id', '=', $id);
        if (null !== $status) {
            $query->where('status', '=', (int)$status);
        }

        return $query->find();
    }

    public static function getPosters(Array $params = array())
    {
        $query = DB::select()->from('afisha');
        if ($params['date_s']) {
            $query->where('created_at', '>=', (int)$params['date_s']);
        }
        if ($params['date_po']) {
            $query->where('created_at', '<=', (int)$params['date_po']);
        }
        if ($params['status']) {
            $query->where('status', '=', (int)$params['status']);
        }
        if ($params['event_id']) {
            $query->where('id', '=', (int)$params['event_id']);
        }
        if ($params['organizer_id']) {
            $query->where('organizer_id', '=', (int)$params['organizer_id']);
        }
        if ($params['order']) {
            if (is_array($params['order']) && count($params['order']) == 2) {
                $query->order_by((string)$params['order'][0], (string)$params['order'][1]);
            } else {
                $query->order_by((string)$params['order']);
            }
        }
        if ($params['group_by']) {
            $query->group_by((string)$params['group_by']);
        }

        return $query->find_all();
    }

    public static function getPoster($id)
    {
        $query = DB::select()->from('afisha')->where('id', '=', (int)$id);
        return $query->find();
    }

    /*
     * Calculate detailed statistics for poster
     */
    public static function getDetailed($poster)
    {
        $prices = Prices::getList(array('afisha_id' => $poster->id, 'order_by' => 'price'));

        if (count($prices) == 0) {
            return null;
        }

        $adminOrders = Orders::getList(array('afisha_id' => $poster->id, 'admin_brone' => 1));
        $siteOrders = Orders::getList(array('afisha_id' => $poster->id, 'admin_brone' => 0));

        $seatsByPrices = Seats::getList(array('grouping' => 'price_id'));
        $result = array();
        foreach ($prices as $key => $price) {
            $result[$key]['price'] = $price;

            $arrPriceSeats = array();
            foreach($seatsByPrices[$price->id] as $k => $v) {
                $arrPriceSeats[] = $v->view_key;
            }
            $soldQuantity = 0;

//            Coming
            $result[$key]['coming_quantity'] = count($arrPriceSeats);
            $result[$key]['coming_sum'] = count($arrPriceSeats) * $price->price;

//            Admin brone
            $result[$key]['admin_brone_quantity'] = 0;
            foreach ($adminOrders as $k => $v) {
                $seats = array_filter(explode(',', $v->seats_keys));
                foreach ((array)$seats as $seat) {
                    if (in_array($seat, $arrPriceSeats)) {
                        $result[$key]['admin_brone_quantity']++;
                    }
                }
            }
            $result[$key]['admin_brone_sum'] = $result[$key]['admin_brone_quantity'] * $price->price;

//            Site brone
            $result[$key]['site_brone_quantity'] = 0;
            foreach ($siteOrders as $k => $v) {
                $seats = array_filter(explode(',', $v->seats_keys));
                foreach ((array)$seats as $seat) {
                    if (in_array($seat, $arrPriceSeats)) {
                        if ($v->status == 'success') {
                            $soldQuantity++;
                        } else {
                            $result[$key]['site_brone_quantity']++;
                        }
                    }
                }
            }
            $result[$key]['site_brone_sum'] = $result[$key]['site_brone_sum'] * $price->price;

//            Residue
            $result[$key]['residue_quantity'] = $result[$key]['coming_quantity'] - $result[$key]['admin_brone_quantity'] - $result[$key]['site_brone_quantity'] - $soldQuantity;
            $result[$key]['residue_sum'] = $result[$key]['residue_quantity'] * $price->price;

//            Sold
            $result[$key]['sold_quantity'] = $soldQuantity;
            $result[$key]['sold_sum'] = $soldQuantity * $price->price;
        }

        return $result;
    }

    public static function getExcel($detailed, $poster)
    {
        $totComingQuantity = $totComingSum = $totAdminQuantity = $totAdminSum = $totSiteQuantity = $totSiteSum =
        $totResidueQuantity = $totResidueSum = $totSoldQuantity = $totSoldSum = 0;

        $xls = new \PHPExcel();
        $xls->setActiveSheetIndex();
        $sheet = $xls->getActiveSheet();

        $sheet->setTitle($poster->name);

//        Header
        $sheet->mergeCells('A1:A2')
//            ->getAlignment()
//            ->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setCellValue('A1', 'Цена билета');

        $sheet->mergeCells('B1:C1')
//            ->getAlignment()
//            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setCellValue('B1', 'Приход');

        $sheet->mergeCells('D1:E1')
//            ->getAlignment()
//            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setCellValue('D1', 'Бронь админа');

        $sheet->mergeCells('F1:G1')
//            ->getAlignment()
//            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setCellValue('F1', 'Бронь на сайте');

        $sheet->mergeCells('H1:I1')
//            ->getAlignment()
//            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setCellValue('H1', 'Остаток');

        $sheet->mergeCells('J1:K1')
//            ->getAlignment()
//            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setCellValue('J1', 'Продано');

        $sheet->setCellValue('B2', 'кол-во')
            ->setCellValue('C2', 'сума');

        $sheet->setCellValue('D2', 'кол-во')
            ->setCellValue('E2', 'сума');

        $sheet->setCellValue('F2', 'кол-во')
            ->setCellValue('G2', 'сума');

        $sheet->setCellValue('H2', 'кол-во')
            ->setCellValue('I2', 'сума');

        $sheet->setCellValue('J2', 'кол-во')
            ->setCellValue('K2', 'сума');

        $offset = 2;
        $row = 1;
        foreach ( $detailed as $key => $el ) {
            $row = $offset + ($key + 1);
//            Price
            $sheet->setCellValueByColumnAndRow(0, $row, $el['price']->price);

            $sheet->setCellValueByColumnAndRow(1, $row, $el['coming_quantity']);
            $sheet->setCellValueByColumnAndRow(2, $row, $el['coming_sum']);

            $sheet->setCellValueByColumnAndRow(3, $row, $el['admin_brone_quantity']);
            $sheet->setCellValueByColumnAndRow(4, $row, $el['admin_brone_sum']);

            $sheet->setCellValueByColumnAndRow(5, $row, $el['site_brone_quantity']);
            $sheet->setCellValueByColumnAndRow(6, $row, $el['site_brone_sum']);

            $sheet->setCellValueByColumnAndRow(7, $row, $el['residue_quantity']);
            $sheet->setCellValueByColumnAndRow(8, $row, $el['residue_sum']);

            $sheet->setCellValueByColumnAndRow(9, $row, $el['sold_quantity']);
            $sheet->setCellValueByColumnAndRow(10, $row, $el['sold_sum']);

//            Calc
            $totComingQuantity += $el['coming_quantity'];
            $totComingSum += $el['coming_sum'];
            $totAdminQuantity += $el['admin_brone_quantity'];
            $totAdminSum += $el['admin_brone_sum'];
            $totSiteQuantity += $el['site_brone_quantity'];
            $totSiteSum += $el['site_brone_sum'];
            $totResidueQuantity += $el['residue_quantity'];
            $totResidueSum += $el['residue_sum'];
            $totSoldQuantity += $el['sold_quantity'];
            $totSoldSum += $el['sold_sum'];
        }
//        Footer
        $row++;
        $sheet->setCellValueByColumnAndRow(1, $row, $totComingQuantity);
        $sheet->setCellValueByColumnAndRow(2, $row, $totComingSum);
        $sheet->setCellValueByColumnAndRow(3, $row, $totAdminQuantity);
        $sheet->setCellValueByColumnAndRow(4, $row, $totAdminSum);
        $sheet->setCellValueByColumnAndRow(5, $row, $totSiteQuantity);
        $sheet->setCellValueByColumnAndRow(6, $row, $totSiteSum);
        $sheet->setCellValueByColumnAndRow(7, $row, $totResidueQuantity);
        $sheet->setCellValueByColumnAndRow(8, $row, $totResidueSum);
        $sheet->setCellValueByColumnAndRow(9, $row, $totSoldQuantity);
        $sheet->setCellValueByColumnAndRow(10, $row, $totSoldSum);

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
        header ( "Content-Disposition: attachment; filename=".$poster->alias.'_'.date('d-m-Y_H:i:s').".xls" );

// Выводим содержимое файла
        $objWriter = new \PHPExcel_Writer_Excel5($xls);
        $objWriter->save('php://output');
    }
}
