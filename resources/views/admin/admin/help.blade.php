@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Help & Support Resources</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Support</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row g-4">
                <!-- User Guide Accordion -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0 text-white">System User Manual & Guide</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="accordion" id="helpAccordion">
                                <div class="accordion-item bg-transparent">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button fw-bold bg-transparent" style="color: var(--bs-body-color);" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            1. How do I register new medicines?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body text-muted small">
                                            First, navigate to the <strong>Medicine Management</strong> module. Register the supplier if not already registered, then click "Add Medicine". Fill in the packaging size, generic name, brand name, and supplier name, then submit.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item bg-transparent">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold bg-transparent" style="color: var(--bs-body-color);" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            2. How are inventory levels updated?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body text-muted small">
                                            Stock is incremented when you log a new supplier <strong>Purchase Order</strong>. It is automatically decremented when you make a sale and generate an <strong>Invoice</strong> (uses FEFO batch tracking). Admin users can also make manual adjustments inside <strong>Inventory Control > Stock Adjust</strong>.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item bg-transparent">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold bg-transparent" style="color: var(--bs-body-color);" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            3. How do I generate Business Intelligence reports?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body text-muted small">
                                            Navigate to the <strong>Reports</strong> module. You can filter and view detailed Sales, Purchases, Inventory, Customer, and Supplier analytics, profit reports, and export reports directly to Excel spreadsheets.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Support Info -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h5 class="fw-bold mb-0 text-white">Contact System Developer</h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-headset text-success" style="font-size: 3.5rem;"></i>
                            <h5 class="fw-bold mt-3" style="color: var(--bs-body-color);">Medora Technical Support</h5>
                            <p class="text-muted small mt-2">For custom features requests, system crashes, or reporting software bugs, please reach out to the developer helpdesk.</p>
                            <hr>
                            <div class="text-start mt-3 small text-muted">
                                <p class="mb-1"><i class="bi bi-envelope text-primary me-2"></i> support@medora.com</p>
                                <p class="mb-1"><i class="bi bi-phone text-success me-2"></i> +880 2-9876543</p>
                                <p class="mb-0"><i class="bi bi-geo-alt text-danger me-2"></i> Dhaka, Bangladesh</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
