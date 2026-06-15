@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Stock</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Stock</li>
                    </ol>
                </div>
            </div>

        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @include('message')

            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title">Edit Stock</h3>
                        </div>

                        <form action="{{ url('admin/stocks/edit/'.$getRecord->id) }}" method="post">
                            @csrf

                            <div class="card-body">

                                <!-- Medicine -->
                                <div class="form-group mb-3">
                                    <label>Medicine</label>
                                    <select name="medicine_id" class="form-control" required>
                                        <option value="">Select Medicine</option>

                                        @foreach($medicines as $medicine)
                                            <option value="{{ $medicine->id }}"
                                                {{ $getRecord->medicine_id == $medicine->id ? 'selected' : '' }}>
                                                {{ $medicine->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Batch ID -->
                                <div class="form-group mb-3">
                                    <label>Batch ID</label>
                                    <input type="text" name="batch_id" class="form-control"
                                           value="{{ $getRecord->batch_id }}" required>
                                </div>

                                <!-- Expiry Date -->
                                <div class="form-group mb-3">
                                    <label>Expiry Date</label>
                                    <input type="date" name="expiry_date" class="form-control"
                                           value="{{ $getRecord->expiry_date }}" required>
                                </div>

                                <!-- Quantity -->
                                <div class="form-group mb-3">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity" class="form-control"
                                           value="{{ $getRecord->quantity }}" required>
                                </div>

                                <!-- MRP -->
                                <div class="form-group mb-3">
                                    <label>MRP</label>
                                    <input type="number" name="mrp" class="form-control"
                                           value="{{ $getRecord->mrp }}" required>
                                </div>

                                <!-- Rate -->
                                <div class="form-group mb-3">
                                    <label>Rate (Selling Price)</label>
                                    <input type="number" name="rate" class="form-control"
                                           value="{{ $getRecord->rate }}" required>
                                </div>

                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">
                                    Update Stock
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>

        </div>
    </section>

</div>

@endsection