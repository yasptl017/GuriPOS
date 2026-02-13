@extends('admin.master_layout')
@section('title')
<title>POS Tables</title>
@endsection
@section('admin-content')

<style>
    .pos-tables-page .page-header {
        background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
        border: 1px solid #fed7aa;
        border-radius: 14px;
        box-shadow: 0 8px 20px rgba(194,65,12,.08);
        padding-left: 18px;
        padding-right: 18px;
    }
    .pos-tables-page .page-header h1 {
        font-weight: 700;
        margin-bottom: 0;
    }

    /* Grid of table cards */
    .table-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 18px;
    }

    .table-card {
        border: 1px solid #e5dccf;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(15,23,42,.06);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: box-shadow .2s, transform .15s;
    }
    .table-card:hover {
        box-shadow: 0 8px 28px rgba(15,23,42,.11);
        transform: translateY(-2px);
    }

    .table-card-top {
        padding: 20px 18px 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex: 1;
    }

    .table-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .table-icon.occupied {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }
    .table-icon.free {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .table-name {
        font-weight: 700;
        font-size: 1.05rem;
        color: #1f2937;
        text-align: center;
    }

    .table-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: .3px;
    }
    .table-badge.occupied {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .table-badge.free {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .cart-summary {
        font-size: 12px;
        color: #6b7280;
        text-align: center;
        line-height: 1.5;
    }

    .table-card-actions {
        display: flex;
        border-top: 1px solid #f3e8d9;
        background: #fffbf5;
    }
    .table-card-actions a,
    .table-card-actions button {
        flex: 1;
        border: none;
        background: none;
        padding: 10px 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s, color .15s;
        text-decoration: none;
        color: #374151;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }
    .table-card-actions a:hover, .table-card-actions button:hover {
        background: #f3f4f6;
    }
    .table-card-actions .btn-danger-soft { color: #dc2626; }
    .table-card-actions .btn-warning-soft { color: #d97706; }
    .table-card-actions .separator {
        width: 1px;
        background: #f3e8d9;
        flex: 0;
    }

    /* Add table card */
    .add-table-card {
        border: 2px dashed #d1d5db;
        border-radius: 16px;
        background: #fafafa;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 180px;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        color: #6b7280;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
    }
    .add-table-card:hover {
        border-color: #f59e0b;
        background: #fffbf5;
        color: #d97706;
        text-decoration: none;
    }
    .add-table-card i { font-size: 26px; }

    /* Modal */
    .modal-header { border-bottom: 1px solid #f3e8d9; background: linear-gradient(135deg, #fffaf0, #fff5e6); }
    .modal-footer { border-top: 1px solid #f3e8d9; }
</style>

<div class="main-content pos-tables-page">
    <section class="section">
        <div class="section-header page-header">
            <h1><i class="fas fa-chair mr-2"></i>POS Tables</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">POS Tables</div>
            </div>
        </div>

        <div class="section-body">

            {{-- Stats row --}}
            <div class="row mt-4 mb-2">
                <div class="col-6 col-md-3">
                    <div class="card text-center py-3">
                        <div class="text-2xl font-weight-bold text-primary" style="font-size:1.7rem">{{ $tables->count() }}</div>
                        <div class="text-muted small">Total Tables</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center py-3">
                        <div style="font-size:1.7rem;font-weight:700;color:#d97706">{{ $tables->filter(fn($t) => !!$t->cart)->count() }}</div>
                        <div class="text-muted small">Occupied</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center py-3">
                        <div style="font-size:1.7rem;font-weight:700;color:#059669">{{ $tables->filter(fn($t) => !$t->cart)->count() }}</div>
                        <div class="text-muted small">Free</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center py-3">
                        <div style="font-size:1.7rem;font-weight:700;color:#6366f1">
                            ${{ number_format($tables->sum(function($t) {
                                if (!$t->cart) return 0;
                                return collect($t->cart)->sum(function($item) {
                                    $base = ($item['price'] ?? 0) * ($item['qty'] ?? 1);
                                    $opt  = ($item['options']['optional_item_price'] ?? 0) * ($item['qty'] ?? 1);
                                    return $base + $opt;
                                });
                            }), 2) }}
                        </div>
                        <div class="text-muted small">Open Cart Value</div>
                    </div>
                </div>
            </div>

            {{-- Table cards grid --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" style="background:linear-gradient(135deg,#fffaf0,#fff5e6);border-bottom:1px solid #f3e8d9;">
                    <h4 class="mb-0">All Tables</h4>
                    <button class="btn btn-primary btn-sm px-3" data-toggle="modal" data-target="#addTableModal">
                        <i class="fas fa-plus mr-1"></i> Add Table
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-cards-grid">

                        @foreach($tables as $table)
                        @php
                            $cartItems = collect($table->cart ?? []);
                            $cartCount = $cartItems->sum('qty');
                            $cartValue = $cartItems->sum(function($item) {
                                $base = ($item['price'] ?? 0) * ($item['qty'] ?? 1);
                                $opt  = ($item['options']['optional_item_price'] ?? 0) * ($item['qty'] ?? 1);
                                return $base + $opt;
                            });
                            $isOccupied = !!$table->cart;
                        @endphp
                        <div class="table-card">
                            <div class="table-card-top">
                                <div class="table-icon {{ $isOccupied ? 'occupied' : 'free' }}">
                                    <i class="fas fa-utensils"></i>
                                </div>
                                <div class="table-name">{{ $table->name }}</div>
                                <span class="table-badge {{ $isOccupied ? 'occupied' : 'free' }}">
                                    <i class="fas fa-circle" style="font-size:7px"></i>
                                    {{ $isOccupied ? 'Occupied' : 'Free' }}
                                </span>
                                @if($isOccupied)
                                <div class="cart-summary">
                                    {{ $cartCount }} item{{ $cartCount != 1 ? 's' : '' }}
                                    &nbsp;&bull;&nbsp;
                                    ${{ number_format($cartValue, 2) }}
                                </div>
                                @endif
                            </div>

                            <div class="table-card-actions">
                                {{-- Edit name --}}
                                <button class="btn-warning-soft"
                                    data-toggle="modal"
                                    data-target="#editTableModal"
                                    data-id="{{ $table->id }}"
                                    data-name="{{ $table->name }}"
                                    title="Rename">
                                    <i class="fas fa-pencil-alt"></i> Rename
                                </button>

                                <div class="separator"></div>

                                @if($isOccupied)
                                {{-- Clear cart --}}
                                <form method="POST" action="{{ route('admin.pos-tables.clear', $table->id) }}"
                                    onsubmit="return confirm('Clear cart for {{ $table->name }}? This cannot be undone.')">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn-warning-soft" title="Clear Cart">
                                        <i class="fas fa-broom"></i> Clear
                                    </button>
                                </form>
                                <div class="separator"></div>
                                @endif

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.pos-tables.destroy', $table->id) }}"
                                    onsubmit="return confirm('Delete table {{ $table->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-soft" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach

                        {{-- Add table card --}}
                        <a href="#" class="add-table-card" data-toggle="modal" data-target="#addTableModal">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add Table</span>
                        </a>

                    </div>
                </div>
            </div>

            {{-- Cart details table --}}
            @if($tables->filter(fn($t) => !!$t->cart)->count())
            <div class="card mt-3">
                <div class="card-header" style="background:linear-gradient(135deg,#fffaf0,#fff5e6);border-bottom:1px solid #f3e8d9;">
                    <h4 class="mb-0"><i class="fas fa-shopping-cart mr-2 text-warning"></i>Open Carts Detail</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" style="font-size:13px">
                            <thead class="thead-light">
                                <tr>
                                    <th>Table</th>
                                    <th>Item</th>
                                    <th>Size</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Unit Price</th>
                                    <th class="text-right">Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tables->filter(fn($t) => !!$t->cart) as $table)
                                    @php $cartItems = collect($table->cart ?? []); @endphp
                                    @foreach($cartItems as $i => $item)
                                    <tr class="{{ $i === 0 ? 'table-warning' : '' }}">
                                        @if($i === 0)
                                        <td rowspan="{{ $cartItems->count() }}" class="align-middle font-weight-bold" style="border-right:2px solid #fed7aa">
                                            <i class="fas fa-utensils mr-1 text-warning"></i>{{ $table->name }}
                                        </td>
                                        @endif
                                        <td>{{ $item['name'] ?? '-' }}</td>
                                        <td>{{ $item['options']['size'] ?? '-' }}</td>
                                        <td class="text-center">{{ $item['qty'] ?? 1 }}</td>
                                        <td class="text-right">${{ number_format(($item['price'] ?? 0), 2) }}</td>
                                        <td class="text-right">
                                            ${{ number_format((($item['price'] ?? 0) + ($item['options']['optional_item_price'] ?? 0)) * ($item['qty'] ?? 1), 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>

{{-- Add Table Modal --}}
<div class="modal fade" id="addTableModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Table</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="POST" action="{{ route('admin.pos-tables.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label for="new_table_name">Table Name</label>
                        <input type="text" id="new_table_name" name="name" class="form-control"
                            placeholder="e.g. Table 5, Counter, Outdoor 1"
                            autofocus required maxlength="100">
                        <small class="text-muted">Must be unique.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Add Table</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Table Modal --}}
<div class="modal fade" id="editTableModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rename Table</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form method="POST" id="editTableForm" action="">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label for="edit_table_name">Table Name</label>
                        <input type="text" id="edit_table_name" name="name" class="form-control"
                            required maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Populate edit modal
$('#editTableModal').on('show.bs.modal', function (e) {
    var btn = $(e.relatedTarget);
    var id  = btn.data('id');
    var name = btn.data('name');
    $('#edit_table_name').val(name);
    $('#editTableForm').attr('action', '/admin/pos-tables/' + id);
});

// Auto-open add modal if validation failed
@if ($errors->has('name'))
    $(function() { $('#addTableModal').modal('show'); });
@endif
</script>
@endpush
