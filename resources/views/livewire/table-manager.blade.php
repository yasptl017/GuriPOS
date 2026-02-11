<div class="table-cover" wire:poll>


    @foreach($tables as $table)
        <button wire:click="select_table({{ $table->id }})"
            @class([
                'table',
                'bg-blue-200' => $table->id == $active_table->id,
                'bg-red' => $table->occupied,
                'bg-green' => !$table->occupied,
            ])
        >
            <div>{{ $table->name }}</div>

        </button>
    @endforeach

    @livewireScripts
</div>

<script>
    document.addEventListener('livewire:load', function () {

        const meta = @js($meta);

        // function that fills in the customer details
        function updateCustomerID() {


            const element = $('#customer_id');
            const customer_id = element.val();

            $("#address_customer_id").val(customer_id);
            $("#order_customer_id").val(customer_id);

            const selectedIndex = element.prop('selectedIndex');
            const selectedOption = element.find('option')[selectedIndex];
            const customerName = selectedOption.text.split(" - ")[0];
            const customerPhone = selectedOption.text.split(" - ")[1];
            const customerAddress = selectedOption.getAttribute('data-address');

            let customerDetails = "Name: " + customerName + "\n";
            if (customerPhone) {
                customerDetails += "Phone: " + customerPhone + "\n";
            }
            if (customerAddress) {
                customerDetails += "Address: " + customerAddress;
            }
            $("#customer-input").val(customerDetails);
            $("#customerInput").val(customerDetails);
        }

        function updatePaymentStatusDropdown(status) {
            if (status) {
                $('#payment_option').val(status);
            } else {
                $('#payment_option').val('paid');
            }
        }

        function updatePaymentMethodButtons(method) {
            const m = method || 'card';
            $('#pm_card').removeClass('active-card');
            $('#pm_cash').removeClass('active-cash');
            $('#pm_unpaid').removeClass('active-unpaid');
            if (m === 'card')        $('#pm_card').addClass('active-card');
            else if (m === 'cash')   $('#pm_cash').addClass('active-cash');
            else if (m === 'unpaid') $('#pm_unpaid').addClass('active-unpaid');
            $('#order_payment_method').val(m);
        }

        function updateOrderOptionDropdown(status) {
            const t = status || 'DineIn';
            $('#order_option').val(t);
            // sync order type buttons
            $('#ot_dinein').removeClass('active-dinein');
            $('#ot_pickup').removeClass('active-pickup');
            $('#ot_delivery').removeClass('active-delivery');
            if (t === 'DineIn')        $('#ot_dinein').addClass('active-dinein');
            else if (t === 'Pickup')   $('#ot_pickup').addClass('active-pickup');
            else if (t === 'Delivery') $('#ot_delivery').addClass('active-delivery');
        }

        function updateCustomerOption(option) {
            const cid = option || 2;
            $('#customer_id').val(cid).trigger('change');
            updateCustomerID();
            // also update the trigger button display
            const $opt = $('#customer_' + cid);
            if ($opt.length) {
                const parts = $opt.text().trim().split(' - ');
                const cname = parts[0] || 'Select Customer';
                const cphone = parts[1] || '';
                const caddr  = $opt.attr('data-address') || '';
                $('#custAvatarIcon').addClass('selected');
                $('#custDisplayName').text(cname);
                let sub = cphone;
                if (caddr) sub += (sub ? ' · ' : '') + caddr.substring(0, 28) + (caddr.length > 28 ? '…' : '');
                $('#custDisplaySub').text(sub || 'Customer selected');
                // also pre-select in modal list
                _selectedCustomerId = cid;
                $('.cust-item').removeClass('selected');
                $('.cust-item[data-id="' + cid + '"]').addClass('selected');
            }
        }

        updatePaymentStatusDropdown(meta['payment_status'])
        updateOrderOptionDropdown(meta['order_type'])
        updateCustomerOption(meta['customer_id'])
        updatePaymentMethodButtons(meta['payment_method'])


        window.livewire.on('table-selected', meta => {

            console.table(meta);

            // replace .shopping-card-body with loading spinner
            $(".shopping-card-body").html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            $.ajax({
                type: 'get',
                url: "{{ url('admin/pos/load-cart') }}",
                success: function (response) {
                    $(".shopping-card-body").html(response)
                    calculateTotalFee();
                },
                error: function (response) {
                    toastr.error("{{__('user.Server error occured')}}")
                }
            });

            updatePaymentStatusDropdown(meta['payment_status'])
            updateOrderOptionDropdown(meta['order_type'])
            updatePaymentMethodButtons(meta['payment_method'])

            updateCustomerOption(meta['customer_id'])


        });


        $(document).on('change', '#payment_option', function () {
            if (!$(this).val()) return;
            @this.
            update_payment_status($(this).val())
        })

        $(document).on('change', '#order_option', function () {
            if (!$(this).val()) return;
            @this.
            update_order_option($(this).val())
        })

        $(document).on('change', '#customer_id', function () {
            if (!$(this).val()) return;
            @this.
            update_customer_id($(this).val())
        })

        document.addEventListener('payment-method-changed', function (e) {
            @this.update_payment_method(e.detail)
        })


    })

</script>


<style>
    .table-cover {
        display: grid;
        grid-template-columns: repeat(13, 1fr);
        grid-gap: 5px;
        padding: 5px;
    }

    .table {
        background-color: #f9f9f9;
        border-radius: 5px;
        padding: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .bg-blue-200 {
        background-color: #bee3f8 !important;
        color: black !important;
    }

    .bg-white {
        background-color: #fff;
    }

    span {
        font-size: 12px;
    }

    .text-red-500 {
        color: #f56565;
    }

    .text-green-500 {
        color: #48bb78;
    }

    .bg-red {
        background-color: #f56565;
        color: white;
        font-weight: bolder;
    }

    .bg-green {
        background-color: royalblue;
        color: white;
        font-weight: bolder;
    }

</style>
