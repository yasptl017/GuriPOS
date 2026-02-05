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
        }

        function updatePaymentStatusDropdown(status) {
            if (status) {
                $('#payment_option').val(status);
            } else {
                $('#payment_option').val('paid');
            }
        }

        function updateOrderOptionDropdown(status) {
            if (status) {
                $('#order_option').val(status);
            } else {
                $('#order_option').val('DineIn');
            }
        }

        function updateCustomerOption(option) {


            if (option) {
                $('#customer_' + option).prop('selected', true);
                // trigger change event
                $('#customer_id').trigger('change');
                updateCustomerID();
            } else {
                $('#customer_2').prop('selected', true);
                // trigger change event
                $('#customer_id').trigger('change');
                updateCustomerID();
            }
        }

        updatePaymentStatusDropdown(meta['payment_status'])
        updateOrderOptionDropdown(meta['order_type'])
        updateCustomerOption(meta['customer_id'])


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
