<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportXmlService;
use DOMDocument;
use Illuminate\Http\Response;
use Illuminate\View\View;
use RuntimeException;
use XSLTProcessor;

class ReportController extends Controller
{
    public function __construct(private ReportXmlService $reportXml) {}

    public function index(): View
    {
        return view('admin.reports.index', [
            'userStats' => [
                'total' => \App\Models\User::where('role', 'user')->count(),
                'verified' => \App\Models\User::where('role', 'user')->where('is_verified', true)->count(),
                'pending' => \App\Models\User::where('role', 'user')->where('verification_status', 'pending')->count(),
            ],
            'itemStats' => [
                'lost' => \App\Models\Item::where('type', 'lost')->count(),
                'found' => \App\Models\Item::where('type', 'found')->count(),
                'returned' => \App\Models\Item::where('status', 'returned')->count(),
            ],
            'claimStats' => [
                'pending' => \App\Models\Claim::where('status', 'pending')->count(),
                'approved' => \App\Models\Claim::where('status', 'approved')->count(),
                'rejected' => \App\Models\Claim::where('status', 'rejected')->count(),
            ],
            'byCategory' => \App\Models\Category::withCount('items')->orderByDesc('items_count')->get(),
            'xsltEnabled' => extension_loaded('xsl'),
        ]);
    }

    public function xml(): Response
    {
        return response($this->reportXml->toXmlString(), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function transform(): Response
    {
        if (! extension_loaded('xsl')) {
            throw new RuntimeException('PHP XSL extension is required. Enable extension=xsl in php.ini.');
        }

        $xml = $this->reportXml->buildDom();
        $xsl = new DOMDocument;
        $xsl->load(resource_path('xslt/reports.xsl'));

        $processor = new XSLTProcessor;
        $processor->importStylesheet($xsl);
        $html = $processor->transformToXml($xml);

        if ($html === false) {
            abort(500, 'XSLT transformation failed.');
        }

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }
}
