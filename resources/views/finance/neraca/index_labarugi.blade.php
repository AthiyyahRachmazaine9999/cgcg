@extends('layouts.head')

@section('content')

<div class="content mb-3">

    <div class="row">

        <div class="col-lg-7">

            <div class="card">

                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-12">

                            <h2 class="mb-0 font-weight-semibold">Laporan Laba/Rugi</h2>

                            Periode Januari s/d Desember 2022

                        </div>

                    </div>



                </div>

                <table class="table table-striped table-hover m_datatable" id="ptable">

                    <tbody>

                        <tr>

                            <td>Penjualan</td>

                            <td class="text-right">{{number_format($penjualan)}}</td>

                        </tr>

                        <!-- total  -->

                        <tr>

                            <td class="font-weight-bold">TOTAL PENJUALAN</td>

                            <td class="text-right text-danger font-weight-bold">{{number_format($penjualan)}}</td>

                        </tr>

                        <tr>

                            <td colspan="2"></td>

                        </tr>

                        <!-- end total -->

                        <tr id="hargapokok"></tr>



                        <tr>

                            <td class="font-weight-bold">LABA KOTOR</td>

                            <td class="text-right text-danger font-weight-bold">{{number_format(0)}}</td>

                        </tr>

                        <tr>

                            <td colspan="2"></td>

                        </tr>



                        <tr>

                            <td class="font-weight-bold" colspan="2">BIAYA USAHA</td>

                        </tr>



                        <tr id="hargapemasaran"></tr>

                        <tr id="hargaadmin"></tr>



                        <!-- total  -->

                        <tr>

                            <td class="font-weight-bold">TOTAL BIAYA USAHA</td>

                            <td class="text-right text-danger font-weight-bold">{{number_format(0)}}</td>

                        </tr>

                        <tr>

                            <td colspan="2"></td>

                        </tr>

                        <!-- end total -->



                        <tr id="hargaincome"></tr>

                        <tr id="hargaexpense"></tr>



                        <!-- total  -->

                        <tr>

                            <td class="font-weight-bold">Net Profit / (Loss)</td>

                            <td class="text-right text-danger font-weight-bold">{{number_format(0)}}</td>

                        </tr>

                        <tr>

                            <td colspan="2"></td>

                        </tr>

                        <!-- end total -->

                    </tbody>

                </table>

            </div>

        </div>

        <div class="col-lg-5">

            <div class="card">

                <div class="card-body" id="filter">

                    <div class="row col-lg-12 mb-3">

                        {!! Form::label('start_date', 'Select Year', ['class' => 'col-form-label date']) !!}

                        <input id="years" type="date" id="start_date" class="form-control date" name="start_date" placeholder="Enter Date">

                    </div>

                    <div class="row col-lg-12">

                        <div class="form-group">

                            <button type="button" class="btn btn-primary"><i class="fas fa-search"></i> Generate</button>

                            <button type="button" class="btn btn-success"><i class="far fa-file-excel"></i> Download</button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@include('sales.quotation.attribute.modal')

@endsection

@section('script')

<script src="{{ asset('ctrl/finance/neraca.js?v=').rand() }}" type="text/javascript"></script>

@endsection



