@extends('admin.master_layout')
@section('title')
<title>Printer Settings</title>
@endsection
@section('admin-content')
<div class="main-content printer-settings-page">
    <section class="section">
        <div class="section-header printer-page-header">
            <h1>Printer Settings</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                <div class="breadcrumb-item">Printer Settings</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-4 printer-grid">

                {{-- Printer Name Config --}}
                <div class="col-12 col-lg-6 d-flex">
                    <div class="card printer-card w-100">
                        <div class="card-header printer-card-header">
                            <h4>Printer Names</h4>
                        </div>
                        <div class="card-body printer-card-body">
                            <p class="text-muted small printer-intro-text">
                                Enter the <strong>exact Windows printer name</strong> as it appears in
                                <em>Control Panel -> Devices and Printers</em>.
                                The desktop Print Agent will use these names to route jobs.
                            </p>
                            <form action="{{ route('admin.update-printer-setting') }}" method="POST" class="printer-form">
                                @csrf
                                @method('PUT')

                                <div class="form-group printer-field">
                                    <label for="kitchen_printer">Kitchen Printer Name</label>
                                    <input
                                        type="text"
                                        id="kitchen_printer"
                                        name="kitchen_printer"
                                        class="form-control"
                                        value="{{ old('kitchen_printer', optional($setting)->kitchen_printer) }}"
                                        placeholder="e.g. EPSON TM-T82 Receipt"
                                    >
                                    <small class="text-muted">Prints kitchen tickets (items only, no prices).</small>
                                </div>

                                <div class="form-group printer-field">
                                    <label for="desk_printer">Desk / Receipt Printer Name</label>
                                    <input
                                        type="text"
                                        id="desk_printer"
                                        name="desk_printer"
                                        class="form-control"
                                        value="{{ old('desk_printer', optional($setting)->desk_printer) }}"
                                        placeholder="e.g. EPSON TM-T82 Receipt"
                                    >
                                    <small class="text-muted">Prints customer receipts at the front counter.</small>
                                </div>

                                <button type="submit" class="btn btn-primary printer-save-btn">{{ __('admin.Update') }}</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Print Agent Setup --}}
                <div class="col-12 col-lg-6 d-flex">
                    <div class="card printer-card w-100">
                        <div class="card-header printer-card-header">
                            <h4>Desktop Print Agent Setup</h4>
                        </div>
                        <div class="card-body printer-card-body">

                            <div class="alert alert-info printer-info-alert" style="color:black">
                                <strong>What is the Print Agent?</strong><br>
                                A small Windows app that runs on the restaurant PC.
                                It polls this server for pending print jobs and sends them
                                directly to your local printers - even when hosted on Hostinger.
                            </div>

                            <h6 class="printer-step-title">Step 1 - Copy your API Key</h6>
                            <div class="input-group mb-3 printer-key-group">
                                <input type="text" class="form-control font-monospace"
                                    id="apiKeyField"
                                    value="{{ env('PRINT_AGENT_KEY') }}"
                                    readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyKey()">Copy</button>
                                </div>
                            </div>

                            <h6 class="printer-step-title">Step 2 - Install the Print Agent on the restaurant PC</h6>
                            <ol class="small printer-step-list">
                                <li>Copy <code>PunjabiParadisePrintAgent.exe</code> to the restaurant PC</li>
                                <li>Double-click to run it</li>
                                <li>In <strong>Settings</strong>, enter:
                                    <ul>
                                        <li>Server URL: <code>{{ config('app.url') }}</code></li>
                                        <li>API Key: (paste from above)</li>
                                        <li>Receipt Printer: exact name from Devices & Printers</li>
                                        <li>Kitchen Printer: exact name from Devices & Printers</li>
                                    </ul>
                                </li>
                                <li>Click <strong>Save &amp; Connect</strong></li>
                                <li>Check "Start with Windows" so it auto-starts</li>
                            </ol>

                            <h6 class="printer-step-title">Step 3 - Test</h6>
                            <p class="small printer-step-note">Place a test order and the agent should print within 3 seconds.</p>

                            <hr>
                            <h6 class="printer-step-title">How to find your printer name on Windows</h6>
                            <ol class="small printer-step-list">
                                <li>Press <kbd>Win + R</kbd>, type <code>control printers</code>, press Enter</li>
                                <li>Right-click your printer -> <strong>See what's printing</strong></li>
                                <li>The window title is the exact printer name to use above</li>
                            </ol>

                        </div>
                    </div>
                </div>

                {{-- Pending Jobs Status --}}
                <div class="col-12 mt-2">
                    <div class="card printer-card printer-jobs-card">
                        <div class="card-header d-flex justify-content-between align-items-center printer-card-header">
                            <h4 class="mb-0">Recent Print Jobs</h4>
                            <button class="btn btn-sm btn-outline-secondary" onclick="loadJobs()">Refresh</button>
                        </div>
                        <div class="card-body p-0">
                            <div id="jobsTable">
                                <table class="table table-sm mb-0 printer-jobs-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Order #</th>
                                            <th>Printer</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Printed At</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jobsBody">
                                        <tr><td colspan="6" class="text-center text-muted py-3">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<style>
    .printer-settings-page .printer-page-header {
        border-radius: 14px;
        background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
        border: 1px solid #fed7aa;
        box-shadow: 0 8px 20px rgba(194, 65, 12, 0.08);
        padding-left: 18px;
        padding-right: 18px;
    }

    .printer-settings-page .printer-page-header h1 {
        margin-bottom: 0;
        font-weight: 700;
        letter-spacing: .2px;
    }

    .printer-settings-page .printer-card {
        border: 1px solid #f3e8d9;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .printer-settings-page .printer-card-header {
        background: linear-gradient(135deg, #fffaf0 0%, #fff5e6 100%);
        border-bottom: 1px solid #f3e8d9;
        padding-top: 14px;
        padding-bottom: 14px;
    }

    .printer-settings-page .printer-card-header h4 {
        margin-bottom: 0;
        font-weight: 700;
    }

    .printer-settings-page .printer-card-body {
        background: #ffffff;
        line-height: 1.65;
    }

    .printer-settings-page .printer-card-body p,
    .printer-settings-page .printer-card-body .small {
        margin-bottom: 0.9rem;
    }

    .printer-settings-page .printer-intro-text {
        line-height: 1.7;
    }

    .printer-settings-page .printer-form .printer-field label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    .printer-settings-page .printer-form .form-control {
        height: 44px;
        border-radius: 10px;
        border: 1px solid #e5dccf;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .printer-settings-page .printer-form .form-control:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.16);
    }

    .printer-settings-page .printer-field small {
        display: block;
        margin-top: 6px;
        line-height: 1.5;
    }

    .printer-settings-page .printer-save-btn {
        min-width: 130px;
        border-radius: 10px;
        font-weight: 600;
    }

    .printer-settings-page .printer-info-alert {
        border: 1px solid #bfdbfe;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 10px;
        margin-bottom: 18px;
        line-height: 1.7;
    }

    .printer-settings-page .printer-step-title {
        margin-top: 14px;
        margin-bottom: 10px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.4;
    }

    .printer-settings-page .printer-key-group .form-control {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        border-color: #e5dccf;
        background: #fffbf5;
    }

    .printer-settings-page .printer-key-group .btn {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .printer-settings-page .printer-step-list {
        padding-left: 18px;
        margin-bottom: 14px;
        line-height: 1.75;
    }

    .printer-settings-page .printer-step-list li {
        margin-bottom: 6px;
    }

    .printer-settings-page .printer-step-list ul {
        margin-top: 6px;
        margin-bottom: 6px;
        padding-left: 18px;
        line-height: 1.7;
    }

    .printer-settings-page .printer-step-note {
        line-height: 1.7;
    }

    .printer-settings-page kbd {
        background: #1f2937;
        color: #fff;
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 11px;
    }

    .printer-settings-page code {
        color: #7c2d12;
        background: #fff4e6;
        border-radius: 5px;
        padding: 2px 5px;
    }

    .printer-settings-page .printer-jobs-card .card-body {
        background: #fff;
    }

    .printer-settings-page .printer-jobs-table thead th {
        background: #f8fafc;
        border-bottom-color: #e2e8f0;
        font-weight: 700;
    }

    .printer-settings-page .printer-jobs-table td,
    .printer-settings-page .printer-jobs-table th {
        padding-top: 10px;
        padding-bottom: 10px;
        vertical-align: middle;
    }

    .printer-settings-page .printer-jobs-table tbody tr:nth-child(even) {
        background: #fcfcfd;
    }

    @media (max-width: 991.98px) {
        .printer-settings-page .printer-page-header {
            padding-top: 12px;
            padding-bottom: 12px;
        }
    }
</style>
@endsection

@push('scripts')
<script>
function copyKey() {
    var field = document.getElementById('apiKeyField');
    field.select();
    document.execCommand('copy');
    alert('API Key copied!');
}

function loadJobs() {
    fetch('{{ url("/api/print-agent/jobs") }}?key={{ env("PRINT_AGENT_KEY") }}')
        .then(r => r.json())
        .then(jobs => {
            var tbody = document.getElementById('jobsBody');
            if (!jobs.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">No pending jobs</td></tr>';
                return;
            }
            tbody.innerHTML = jobs.map(j => `
                <tr>
                    <td>${j.id}</td>
                    <td>${j.order_id ?? '-'}</td>
                    <td>${j.printer}</td>
                    <td><span class="badge badge-warning">Pending</span></td>
                    <td>${j.created_at}</td>
                    <td>-</td>
                </tr>
            `).join('');
        })
        .catch(() => {
            document.getElementById('jobsBody').innerHTML =
                '<tr><td colspan="6" class="text-center text-danger py-3">Failed to load jobs</td></tr>';
        });
}

// Auto load on page open
loadJobs();
</script>
@endpush
