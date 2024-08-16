@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="/style/home.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
@endsection

@section('container')

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">Brands List</div>
            <div class="card-body">
                <div class="p-2 g-col-6">
                    <!-- Button to trigger add Brands modal -->
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addBrandModal">
                        Add Brands
                    </button>
                </div>
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">Name</th>
                                <th class="text-center" scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $brand->name }}</td>
                                    <td class="text-center">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#editBrandModal{{ $brand->id }}">
                                            Edit
                                        </button>

                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#deleteBrandModal{{ $brand->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Brands Modal -->
                                <div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="editbrandModalLabel{{ $brand->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editBrandModalLabel{{ $brand->id }}">Edit
                                                    Brand</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('brands.update', $brand->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="brandName{{ $brand->id }}">Brand Name</label>
                                                        <input type="text" class="form-control"
                                                            id="brandName{{ $brand->id }}" name="name"
                                                            value="{{ $brand->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Brands Modal -->
                                <div class="modal fade" id="deleteBrandModal{{ $brand->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="deleteBrandModalLabel{{ $brand->id  }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteBrandModalLabel{{ $brand->id  }}">
                                                    Delete Brand</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="reassignBrand{{ $brand->id  }}">Reassign items
                                                            to:</label>
                                                        <select class="form-control" id="reassignBrand{{ $brand->id  }}"
                                                            name="reassign_brand_id" required>
                                                            @foreach ($allBrands->where('id', '!=', $brand->id) as $reassignBrand)
                                                                <option value="{{ $reassignBrand->id }}">
                                                                    {{ $reassignBrand->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <p>Are you sure you want to delete this brand? All related items will
                                                        be reassigned to the selected Brand.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $brands->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="addBrandModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBrandModalLabel">Add Brand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('brands.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="brandName">Brand Name</label>
                        <input type="text" class="form-control" id="brandName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9g6phBOFVrEA40xzY7xlrE5BhHZp66obkW5zQ"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
@endsection