@extends('admin.master_layout')
@section('title')
    <title>Category Order</title>
@endsection

<style>
    .sort-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
        padding: 22px;
    }

    .sort-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sort-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        margin-bottom: 6px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        cursor: grab;
        user-select: none;
        transition: background 0.15s, box-shadow 0.15s;
    }

    .sort-item:active {
        cursor: grabbing;
    }

    .sort-item.sortable-ghost {
        background: #eff6ff;
        border-color: #93c5fd;
        opacity: 0.7;
    }

    .sort-item.sortable-drag {
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.18);
    }

    .drag-handle {
        color: #9ca3af;
        font-size: 16px;
        flex-shrink: 0;
    }

    .sort-name {
        flex: 1;
        font-weight: 600;
        color: #111827;
        font-size: 14px;
    }

    .sort-pos {
        font-size: 11px;
        color: #9ca3af;
        background: #f1f5f9;
        border-radius: 6px;
        padding: 2px 7px;
    }

    .empty-note {
        text-align: center;
        color: #9ca3af;
        padding: 24px 0;
        font-size: 13px;
    }

    .tab-save-row {
        margin-top: 16px;
        display: flex;
        justify-content: flex-end;
    }

    .nav-tabs .nav-link {
        font-weight: 600;
        color: #6b7280;
    }

    .nav-tabs .nav-link.active {
        color: #2563eb;
    }
</style>

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Category Order</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Category Order</div>
                </div>
            </div>

            <div class="section-body">
                <p class="text-muted mb-4" style="font-size:13px;">
                    Drag and drop categories to set their display order. Click <strong>Save Order</strong> after reordering each tab.
                </p>

                <div class="sort-card">
                    <ul class="nav nav-tabs mb-4" id="orderTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab-home" role="tab">
                                <i class="fas fa-home mr-1"></i> Home Page
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pos-tab" data-toggle="tab" href="#tab-pos" role="tab">
                                <i class="fas fa-cash-register mr-1"></i> POS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="receipt-tab" data-toggle="tab" href="#tab-receipt" role="tab">
                                <i class="fas fa-receipt mr-1"></i> Receipt
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- HOME TAB --}}
                        <div class="tab-pane fade show active" id="tab-home" role="tabpanel">
                            <p style="font-size:12px; color:#6b7280;">
                                Affects category display order on the website home page. Only categories with <strong>Show Homepage = Yes</strong> and <strong>Status = Active</strong> are listed here.
                            </p>
                            @if ($homeCategories->isEmpty())
                                <div class="empty-note">No homepage categories found. Enable "Show Homepage" on some categories first.</div>
                            @else
                                <ul class="sort-list" id="home-list">
                                    @foreach ($homeCategories as $i => $cat)
                                        <li class="sort-item" data-id="{{ $cat->id }}">
                                            <i class="fas fa-grip-vertical drag-handle"></i>
                                            <span class="sort-name">{{ $cat->name }}</span>
                                            <span class="sort-pos">#{{ $i + 1 }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-save-row">
                                    <button class="btn btn-primary btn-save" data-context="home" data-list="home-list">
                                        <i class="fas fa-save mr-1"></i> Save Home Page Order
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- POS TAB --}}
                        <div class="tab-pane fade" id="tab-pos" role="tabpanel">
                            <p style="font-size:12px; color:#6b7280;">
                                Affects the category filter order on the POS screen. All <strong>Active</strong> categories are listed here.
                            </p>
                            @if ($posCategories->isEmpty())
                                <div class="empty-note">No active categories found.</div>
                            @else
                                <ul class="sort-list" id="pos-list">
                                    @foreach ($posCategories as $i => $cat)
                                        <li class="sort-item" data-id="{{ $cat->id }}">
                                            <i class="fas fa-grip-vertical drag-handle"></i>
                                            <span class="sort-name">{{ $cat->name }}</span>
                                            <span class="sort-pos">#{{ $i + 1 }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-save-row">
                                    <button class="btn btn-primary btn-save" data-context="pos" data-list="pos-list">
                                        <i class="fas fa-save mr-1"></i> Save POS Order
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- RECEIPT TAB --}}
                        <div class="tab-pane fade" id="tab-receipt" role="tabpanel">
                            <p style="font-size:12px; color:#6b7280;">
                                Affects the order items are grouped by category on the thermal printer receipt. All <strong>Active</strong> categories are listed here.
                            </p>
                            @if ($receiptCategories->isEmpty())
                                <div class="empty-note">No active categories found.</div>
                            @else
                                <ul class="sort-list" id="receipt-list">
                                    @foreach ($receiptCategories as $i => $cat)
                                        <li class="sort-item" data-id="{{ $cat->id }}">
                                            <i class="fas fa-grip-vertical drag-handle"></i>
                                            <span class="sort-name">{{ $cat->name }}</span>
                                            <span class="sort-pos">#{{ $i + 1 }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-save-row">
                                    <button class="btn btn-primary btn-save" data-context="receipt" data-list="receipt-list">
                                        <i class="fas fa-save mr-1"></i> Save Receipt Order
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Initialize SortableJS on each list
        ['home-list', 'pos-list', 'receipt-list'].forEach(function(listId) {
            var el = document.getElementById(listId);
            if (!el) return;
            Sortable.create(el, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function() {
                    // Update position badges after drag
                    var items = el.querySelectorAll('.sort-item');
                    items.forEach(function(item, idx) {
                        item.querySelector('.sort-pos').textContent = '#' + (idx + 1);
                    });
                }
            });
        });

        // Save button handler
        document.querySelectorAll('.btn-save').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var context = btn.getAttribute('data-context');
                var listId  = btn.getAttribute('data-list');
                var list    = document.getElementById(listId);
                var order   = Array.from(list.querySelectorAll('.sort-item')).map(function(el) {
                    return el.getAttribute('data-id');
                });

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';

                $.ajax({
                    url: '{{ route("admin.category-order.save") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        context: context,
                        order: order
                    },
                    success: function(res) {
                        toastr.success(res.message || 'Order saved successfully');
                    },
                    error: function() {
                        toastr.error('Failed to save order. Please try again.');
                    },
                    complete: function() {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save mr-1"></i> Save ' +
                            (context === 'home' ? 'Home Page' : context === 'pos' ? 'POS' : 'Receipt') + ' Order';
                    }
                });
            });
        });
    </script>
@endsection
