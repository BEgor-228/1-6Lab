<?php
namespace App\Controllers;

use App\Models\AppointmentModel;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;

class ReportController {
    private $model;
    
    public function __construct(AppointmentModel $model) {
        $this->model = $model;
    }
    public function generatePdf() {
        $sales = $this->model->getAllSales();
        $html = '<h1>Список продаж</h1>';
        $html .= '<table border="1" style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr><th>Марка</th><th>Модель</th><th>Цена</th><th>Цвет</th><th>Имя клиента</th><th>Телефон клиента</th></tr>';
        foreach ($sales as $sale) {
            $html .= '<tr>';
            $html .= '<td>' . $sale['car_brand'] . '</td>';
            $html .= '<td>' . $sale['car_model'] . '</td>';
            $html .= '<td>' . $sale['car_price'] . '</td>';
            $html .= '<td>' . $sale['car_color'] . '</td>';
            $html .= '<td>' . $sale['client_name'] . '</td>';
            $html .= '<td>' . $sale['client_phone'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $mpdf = new Mpdf(['tempDir' => __DIR__ . '/../../tmp']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('sales_report.pdf', 'D');
    }

    public function generateExcel() {
        $sales = $this->model->getAllSales();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Марка');
        $sheet->setCellValue('B1', 'Модель');
        $sheet->setCellValue('C1', 'Цена');
        $sheet->setCellValue('D1', 'Цвет');
        $sheet->setCellValue('E1', 'Имя клиента');
        $sheet->setCellValue('F1', 'Телефон клиента');
        $row = 2;
        foreach ($sales as $sale) {
            $sheet->setCellValue('A' . $row, $sale['car_brand']);
            $sheet->setCellValue('B' . $row, $sale['car_model']);
            $sheet->setCellValue('C' . $row, $sale['car_price']);
            $sheet->setCellValue('D' . $row, $sale['car_color']);
            $sheet->setCellValue('E' . $row, $sale['client_name']);
            $sheet->setCellValue('F' . $row, $sale['client_phone']);
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'sales_report.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
    }

    public function generateCsv(): Response {
        $sales = $this->model->getAllSales();
        $fileName = 'sales_report.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Марка', 'Модель', 'Цена', 'Цвет', 'Имя клиента', 'Телефон клиента']);
        foreach ($sales as $sale) {
            fputcsv($output, [
                $sale['car_brand'],
                $sale['car_model'],
                $sale['car_price'],
                $sale['car_color'],
                $sale['client_name'],
                $sale['client_phone']
            ]);
        }
        fclose($output);
        return new Response('', Response::HTTP_OK, ['Content-Type' => 'text/csv']);
    }
}