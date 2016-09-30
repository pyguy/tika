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
                            Traceroute
                        </h4>

                        <div class="widget-chart text-center">
                            <div class="tika_table">
                                <table class="table table-responsive">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>IP</th>
                                        <th>Usage</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-left">
                                    <tr>
                                        <th scope="row">1</th>
                                        <th>192.168.1.1</th>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar
                                                progress-bar-success"
                                                     role="progressbar"
                                                     aria-valuenow="18%"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"
                                                     style="width:18%">
                                                    18%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <th>93.45.1.100</th>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar
                                                progress-bar-warning"
                                                     role="progressbar"
                                                     aria-valuenow="40%"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"
                                                     style="width:40%">
                                                    40%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <th>2.110.43.9</th>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar
                                                progress-bar-danger"
                                                     role="progressbar"
                                                     aria-valuenow="90%"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"
                                                     style="width:90%">
                                                    90%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
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