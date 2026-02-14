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

                {{-- Print Mode --}}
                <div class="col-12 mt-2">
                    <div class="card printer-card">
                        <div class="card-header printer-card-header d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Print Mode</h4>
                            <span class="badge badge-pill {{ optional($setting)->print_mode === 'push' ? 'badge-success' : 'badge-secondary' }} px-3 py-2" style="font-size:13px;">
                                {{ optional($setting)->print_mode === 'push' ? 'Push Mode Active' : 'Poll Mode Active' }}
                            </span>
                        </div>
                        <div class="card-body printer-card-body">
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <p class="text-muted small mb-3">
                                        Choose how the Print Agent receives print jobs from the server.
                                    </p>
                                    <form action="{{ route('admin.update-printer-setting') }}" method="POST" id="printModeForm">
                                        @csrf
                                        @method('PUT')
                                        {{-- Hidden inputs carry the existing printer names through --}}
                                        <input type="hidden" name="kitchen_printer" value="{{ optional($setting)->kitchen_printer }}">
                                        <input type="hidden" name="desk_printer"    value="{{ optional($setting)->desk_printer }}">

                                        <div class="print-mode-options mb-3">
                                            <div class="print-mode-option {{ optional($setting)->print_mode !== 'push' ? 'active' : '' }}" id="optPoll">
                                                <label class="d-flex align-items-start mb-0" style="cursor:pointer;">
                                                    <input type="radio" name="print_mode" value="poll"
                                                        {{ optional($setting)->print_mode !== 'push' ? 'checked' : '' }}
                                                        class="mt-1 mr-2" onchange="onModeChange(this)">
                                                    <div>
                                                        <strong>Poll Mode</strong> <span class="text-muted small">(Default)</span><br>
                                                        <span class="small text-muted">
                                                            Agent asks server for pending jobs every N seconds.
                                                            Works reliably from anywhere. Slight delay (≤ poll interval).
                                                        </span>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="print-mode-option {{ optional($setting)->print_mode === 'push' ? 'active' : '' }} mt-2" id="optPush">
                                                <label class="d-flex align-items-start mb-0" style="cursor:pointer;">
                                                    <input type="radio" name="print_mode" value="push"
                                                        {{ optional($setting)->print_mode === 'push' ? 'checked' : '' }}
                                                        class="mt-1 mr-2" onchange="onModeChange(this)">
                                                    <div>
                                                        <strong>Push Mode</strong> <span class="badge badge-warning badge-pill small">Instant</span><br>
                                                        <span class="small text-muted">
                                                            Server pushes directly to the agent the moment you click Print.
                                                            Zero delay. Requires agent running on the same PC as the browser,
                                                            or on the local network with the port accessible.
                                                        </span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <div id="pushPortRow" class="form-group print-mode-port-row mb-3" style="{{ optional($setting)->print_mode === 'push' ? '' : 'display:none;' }}">
                                            <label class="font-weight-600 mb-1">Agent Listen Port</label>
                                            <div class="input-group" style="max-width:220px;">
                                                <input type="number" name="agent_port" class="form-control"
                                                    value="{{ optional($setting)->agent_port ?? 5757 }}"
                                                    min="1024" max="65535" placeholder="5757">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">TCP</span>
                                                </div>
                                            </div>
                                            <small class="text-muted">Default: 5757. Must match the port set in the Print Agent app.</small>
                                        </div>

                                        <button type="submit" class="btn btn-primary printer-save-btn">Save Print Mode</button>
                                    </form>
                                </div>

                                <div class="col-12 col-md-5 mt-3 mt-md-0">
                                    <div class="alert alert-light border small" style="line-height:1.7;">
                                        <strong>Which mode to choose?</strong><br>
                                        <table class="table table-sm table-borderless mb-0 mt-2" style="font-size:12px;">
                                            <thead><tr><th></th><th class="text-center">Poll</th><th class="text-center">Push</th></tr></thead>
                                            <tbody>
                                                <tr><td>Print Speed</td><td class="text-center">≤1 sec</td><td class="text-center text-success font-weight-bold">Instant</td></tr>
                                                <tr><td>Setup</td><td class="text-center text-success">Easy</td><td class="text-center">Port needed</td></tr>
                                                <tr><td>Works remotely</td><td class="text-center text-success">Yes</td><td class="text-center text-warning">LAN only</td></tr>
                                                <tr><td>Fallback if agent down</td><td class="text-center text-success">Yes</td><td class="text-center">Auto-fallback</td></tr>
                                            </tbody>
                                        </table>
                                        <hr class="my-2">
                                        <span class="text-muted">In Push mode the job is still saved to DB. If the agent is unreachable, it falls back to Poll automatically.</span>
                                    </div>
                                </div>
                            </div>
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
                                In <strong>Poll mode</strong> it checks every second for pending jobs.
                                In <strong>Push mode</strong> the server notifies it instantly when you click Print —
                                zero delay. Select your preferred mode in the <em>Print Mode</em> card above.
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
                            <p class="small printer-step-note">Place a test order. In Push mode it prints instantly; in Poll mode within 1 second.</p>

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

    /* Print Mode */
    .printer-settings-page .print-mode-options .print-mode-option {
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 12px 14px;
        transition: border-color .2s, background .2s;
        background: #fafafa;
    }

    .printer-settings-page .print-mode-options .print-mode-option.active,
    .printer-settings-page .print-mode-options .print-mode-option:has(input:checked) {
        border-color: #f59e0b;
        background: #fffbf5;
    }

    .printer-settings-page .print-mode-port-row label {
        font-weight: 600;
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

function onModeChange(radio) {
    var portRow = document.getElementById('pushPortRow');
    var optPoll = document.getElementById('optPoll');
    var optPush = document.getElementById('optPush');
    if (radio.value === 'push') {
        portRow.style.display = '';
        optPush.classList.add('active');
        optPoll.classList.remove('active');
    } else {
        portRow.style.display = 'none';
        optPoll.classList.add('active');
        optPush.classList.remove('active');
    }
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
