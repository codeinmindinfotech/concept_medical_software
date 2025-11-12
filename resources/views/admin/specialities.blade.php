<?php $page = 'specialities'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-7 col-auto">
                        <h3 class="page-title">Specialities</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Specialities</li>
                        </ul>
                    </div>
                    <div class="col-sm-5 col">
                        <a href="#Add_Specialities_details" data-bs-toggle="modal"
                            class="btn btn-primary float-end mt-2">Add</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0" id="specialities_data">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Specialities</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->


    <!-- Add Modal -->
    <div class="modal fade" id="Add_Specialities_details" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Specialities</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label class="mb-2">Specialities</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label class="mb-2">Image</label>
                                    <input type="file" class="form-control">
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /ADD Modal -->

    <!-- Edit Details Modal -->
    <div class="modal fade" id="edit_specialities_details" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Specialities</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label class="mb-2">Specialities</label>
                                    <input type="text" class="form-control" value="Cardiology">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label class="mb-2">Image</label>
                                    <input type="file" class="form-control">
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Details Modal -->

    <!-- Delete Modal -->
    <div class="modal fade" id="delete_modal" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!--	<div class="modal-header">
                   <h5 class="modal-title">Delete</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                   </button>
                  </div>-->
                <div class="modal-body">
                    <div class="form-content p-2">
                        <h4 class="modal-title">Delete</h4>
                        <p class="mb-4">Are you sure want to delete?</p>
                        <button type="button" class="btn btn-primary">Save </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Modal -->
    </div>
    <!-- /Main Wrapper -->
@endsection
