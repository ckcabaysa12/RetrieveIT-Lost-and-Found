<?php

namespace App\Services;

class ReportExcelExportService
{
    public function __construct(private ReportXmlService $reportXml) {}

    public function toCsvString(): string
    {
        $data = $this->reportXml->reportData();
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, ['RetrieveIT System Report']);
        fputcsv($handle, ['Generated', $data['generated_at']]);
        fputcsv($handle, ['System', $data['system']]);
        fputcsv($handle, []);

        fputcsv($handle, ['Users']);
        fputcsv($handle, ['Metric', 'Count']);
        foreach ($data['userStats'] as $label => $count) {
            fputcsv($handle, [ucfirst(str_replace('_', ' ', $label)), $count]);
        }
        fputcsv($handle, []);

        fputcsv($handle, ['Items']);
        fputcsv($handle, ['Metric', 'Count']);
        foreach ($data['itemStats'] as $label => $count) {
            fputcsv($handle, [ucfirst(str_replace('_', ' ', $label)), $count]);
        }
        fputcsv($handle, []);

        fputcsv($handle, ['Claims']);
        fputcsv($handle, ['Metric', 'Count']);
        foreach ($data['claimStats'] as $label => $count) {
            fputcsv($handle, [ucfirst(str_replace('_', ' ', $label)), $count]);
        }
        fputcsv($handle, []);

        fputcsv($handle, ['Items by category']);
        fputcsv($handle, ['Category', 'Count']);
        foreach ($data['byCategory'] as $category) {
            fputcsv($handle, [$category->name, $category->items_count]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return "\xEF\xBB\xBF".$csv;
    }
}
