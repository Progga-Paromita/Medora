@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">

                <!-- Title -->
                <div class="col-sm-6">
                    <h1>Administrators</h1>
                </div>

                <!-- Breadcrumb -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Administrators</li>
                    </ol>
                </div>

            </div>

            <!-- Add Button -->
            <div class="row mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/users/create') }}" class="btn btn-primary">
                        Add New
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">

                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title">Administrators List</h3>
                        </div>

                        <div class="card-body">
                            <!-- table content here -->
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

</div>

@endsection