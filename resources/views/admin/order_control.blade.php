@extends('admin.master_layout')
@section('title')
<title>Order Control</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Order Control</h1>
           
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.update-order-control') }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- PICKUP --}}
                                <div class="order-control-block mb-4">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h5 class="mb-0">
                                            <i class="fas fa-store mr-2 text-primary"></i> Pickup
                                        </h5>
                                        <div class="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="pickup_enabled"
                                                name="pickup_enabled"
                                                value="1"
                                                {{ $orderControl->pickup_enabled ? 'checked' : '' }}
                                                onchange="toggleMessage('pickup', this.checked)"
                                            >
                                            <label class="custom-control-label" for="pickup_enabled">
                                                <span id="pickup_status_label" class="{{ $orderControl->pickup_enabled ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $orderControl->pickup_enabled ? 'Enabled' : 'Disabled' }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="pickup_message_wrap" style="{{ $orderControl->pickup_enabled ? 'display:none' : '' }}">
                                        <label class="form-label text-muted small">Message shown to customers when Pickup is disabled</label>
                                        <textarea
                                            name="pickup_disabled_message"
                                            class="form-control"
                                            rows="2"
                                            placeholder="e.g. Pickup is temporarily unavailable. Please try again later."
                                        >{{ old('pickup_disabled_message', $orderControl->pickup_disabled_message) }}</textarea>
                                    </div>
                                </div>

                                <hr>

                                {{-- DELIVERY --}}
                                <div class="order-control-block mt-4 mb-2">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h5 class="mb-0">
                                            <i class="fas fa-motorcycle mr-2 text-primary"></i> Delivery
                                        </h5>
                                        <div class="custom-control custom-switch">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="delivery_enabled"
                                                name="delivery_enabled"
                                                value="1"
                                                {{ $orderControl->delivery_enabled ? 'checked' : '' }}
                                                onchange="toggleMessage('delivery', this.checked)"
                                            >
                                            <label class="custom-control-label" for="delivery_enabled">
                                                <span id="delivery_status_label" class="{{ $orderControl->delivery_enabled ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $orderControl->delivery_enabled ? 'Enabled' : 'Disabled' }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="delivery_message_wrap" style="{{ $orderControl->delivery_enabled ? 'display:none' : '' }}">
                                        <label class="form-label text-muted small">Message shown to customers when Delivery is disabled</label>
                                        <textarea
                                            name="delivery_disabled_message"
                                            class="form-control"
                                            rows="2"
                                            placeholder="e.g. Delivery is unavailable today. Please use Pickup or try UberEats."
                                        >{{ old('delivery_disabled_message', $orderControl->delivery_disabled_message) }}</textarea>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function toggleMessage(type, isEnabled) {
        var wrap = document.getElementById(type + '_message_wrap');
        var label = document.getElementById(type + '_status_label');

        if (isEnabled) {
            wrap.style.display = 'none';
            label.textContent = 'Enabled';
            label.className = 'text-success font-weight-bold';
        } else {
            wrap.style.display = 'block';
            label.textContent = 'Disabled';
            label.className = 'text-danger font-weight-bold';
        }
    }
</script>
@endsection
