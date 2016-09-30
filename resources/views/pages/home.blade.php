@extends('layout')

@section('main-container')

    <div class="wrapper">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Welcome !</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5">
                    <div class="card-box">
                        <h4 class="text-dark  header-title m-t-0 m-b-30">
                            Network Usage
                        </h4>

                        <div class="widget-chart text-center">
                            <div class="tika_table table-responsive">
                                <table id="network" class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Interface</th>
                                        <th>IP</th>
                                        <th>Receive</th>
                                        <th>Transmit</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4">
                    <div class="card-box">
                        <h4 class="text-dark  header-title m-t-0 m-b-30">
                            Traceroute <small id="traceroute_to"></small>
                        </h4>

                        <div class="widget-chart text-center">
                            <div id="traceroute" class="tika_table">
                                <table class="table table-responsive">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>IP</th>
                                        <th>Usage</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-left"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-3">
                    <div class="card-box">
                        <h4 class="text-dark header-title m-t-0 m-b-30">
                            Services
                        </h4>
                        <div class="widget-chart text-center">
                            <div class="tika_table">
                                <table id="services" class="table table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <footer class="footer text-right">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            2016 © Tika.
                            <a class="pull-right github" href="#">
                                Fork on github
                                <i class="fa fa-github"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </footer>

        </div>

    </div>

@stop