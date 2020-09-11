<?php
use App\Model\Table\FormTable;

try {
    if ($success) {
        $objPHPExcel->getProperties()->setCreator("SICANTIK")
            ->setLastModifiedBy("SICANTIK")
            ->setTitle($form['nama_form'])
            ->setSubject("Report")
            ->setDescription("Report for SICANTIK")
            ->setKeywords("Report SICANTIK")
            ->setCategory("Result file");

        if ($form['canvas']) {
            $rowIndex = 2;

            foreach ($form['canvas'] as $canvasIndex => $canvas) {
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcelSheet = $objPHPExcel->getActiveSheet();

                switch ($canvas['ftype']) {
                    case FormTable::TIPE_FORM:
                    case FormTable::TIPE_TABEL:
                    case FormTable::TIPE_TABEL_GRID:
                    case FormTable::TIPE_TABEL_STATIK;
                        $header = [];
                        $headerRow = $rowIndex - 1;
                        if ($canvas['fields']) {
                            foreach ($canvas['fields'] as $recordIndex => $row) {
                                foreach ($row['field'] as $colIndex => $field) {
                                    if ($recordIndex == 0) {
                                        // Create the Header
                                        $objPHPExcelSheet->setCellValueByColumnAndRow($colIndex, $headerRow, $field['label']);
                                        $header[] = $field['label'];
                                    }
                                    $objPHPExcelSheet->setCellValueByColumnAndRow($colIndex, $rowIndex, $field['data']);
                                }
                                $rowIndex++;
                            }

                            // Make the first row bold
                            $firstLetter = PHPExcel_Cell::stringFromColumnIndex(0);
                            $lastLetter = PHPExcel_Cell::stringFromColumnIndex(count($header)-1);
                            $headerRange = "{$firstLetter}$headerRow:{$lastLetter}$headerRow";
                            $objPHPExcelSheet->getStyle($headerRange)->getFont()->setBold(true);
                        }
                        $rowIndex += 2;
                        break;
                }
            }
        }

        switch ($outputType) {
            case 'xls':
                // Set the excel header
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="report.xls"');

                // Write file to the browser
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
                break;

            case 'pdf':
                // Set the PDF Renderer
                $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                $rendererLibraryPath = ROOT . '/vendor/mpdf/mpdf';
                PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);

                // Set the PDF Header
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment;filename="report.pdf"');
                header('Cache-Control: max-age=0');

                // Write file to the browser
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
                $objWriter->save('php://output');
                break;
        }
    } else {
        echo $message;
    }
} catch (\Exception $ex) {
    echo $ex->getMessage();
}