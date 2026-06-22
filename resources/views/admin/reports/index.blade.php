@extends('layouts.app')
@section('title', 'Reports')

@section('content')
@include('partials.back-button', ['url' => route('admin.dashboard'), 'label' => 'Back to admin'])

<div class="page-hero mb-4">
    <h1 class="h4 mb-1">System Reports</h1>
    <p class="mb-0">Monitor activity for your capstone documentation & defense.</p>
</div>

<div class="card-lf p-3 mb-4">
    <p class="section-label mb-2">XML &amp; XSLT (IPT requirement)</p>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.reports.xml') }}" class="btn btn-lf-outline btn-sm" target="_blank">
            <i class="bi bi-filetype-xml me-1"></i> View XML report
        </a>
        <a href="{{ route('admin.reports.transform') }}" class="btn btn-lf btn-sm" target="_blank">
            <i class="bi bi-code-slash me-1"></i> View XSLT-transformed report
        </a>
    </div>
    @unless($xsltEnabled)
        <p class="small text-danger mb-0 mt-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            PHP <code>xsl</code> extension is disabled. Enable <code>extension=xsl</code> in php.ini for XSLT to work.
        </p>
    @endunless
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-lf p-0 h-100">
            <div class="card-header">Users</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span>Total users</span><strong>{{ $userStats['total'] }}</strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Verified</span><strong class="text-primary">{{ $userStats['verified'] }}</strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Pending ID</span><strong class="text-warning">{{ $userStats['pending'] }}</strong></li>
            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-lf p-0 h-100">
            <div class="card-header">Items</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span>Lost reports</span><strong>{{ $itemStats['lost'] }}</strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Found reports</span><strong>{{ $itemStats['found'] }}</strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Returned</span><strong class="text-success">{{ $itemStats['returned'] }}</strong></li>
            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-lf p-0 h-100">
            <div class="card-header">Claims</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span>Pending</span><strong>{{ $claimStats['pending'] }}</strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Approved</span><strong>{{ $claimStats['approved'] }}</strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Rejected</span><strong>{{ $claimStats['rejected'] }}</strong></li>
            </ul>
        </div>
    </div>
</div>

<div class="card-lf">
    <div class="card-header">Items by category</div>
    <table class="table mb-0">
        <thead class="table-light"><tr><th>Category</th><th>Count</th></tr></thead>
        <tbody>
            @foreach($byCategory as $cat)
                <tr><td>{{ $cat->name }}</td><td><strong>{{ $cat->items_count }}</strong></td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
