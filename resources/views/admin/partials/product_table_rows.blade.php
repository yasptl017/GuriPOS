@forelse ($products as $index => $product)
    <tr>
        <td>{{ $products->firstItem() + $index }}</td>
        <td>
            <a target="_blank" href="#">{{ $product->name }}</a>
        </td>
        <td>{{ $setting->currency_icon }}{{ $product->price }}</td>
        <td>
            @if ($product->today_special)
                <span class="badge badge-success">{{ __('admin.Yes') }}</span>
            @else
                <span class="badge badge-danger">{{ __('admin.No') }}</span>
            @endif
        </td>
        <td>
            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary btn-sm">
                <i class="fa fa-edit" aria-hidden="true"></i>
            </a>
            @php
                $existOrder = isset($orderedProductIds[$product->id]);
            @endphp
            @if (!$existOrder)
                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $product->id }})">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </a>
            @else
                <a href="javascript:;" data-toggle="modal" data-target="#canNotDeleteModal" class="btn btn-danger btn-sm" disabled>
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </a>
            @endif
            <div class="dropdown d-inline">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton2-{{ $product->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2-{{ $product->id }}">
                    <a class="dropdown-item has-icon" href="{{ route('admin.product-gallery', $product->id) }}">
                        <i class="far fa-image"></i> {{ __('admin.Image Gallery') }}
                    </a>
                    <a class="dropdown-item has-icon" href="{{ route('admin.product-variant', $product->id) }}">
                        <i class="fas fa-cog"></i> {{ __('admin.Size / Optional Item') }}
                    </a>
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-muted py-4">No matching products found.</td>
    </tr>
@endforelse

