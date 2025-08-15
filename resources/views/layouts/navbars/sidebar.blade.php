<div class="sidebar d-print-none" data-color="blue">
    <!--
    Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
-->
    <div class="logo">
        <a href="{{url('')}}" class="simple-text logo-normal">
            {{ __('BDM-Admin') }}
        </a>
    </div>
    <div class="sidebar-wrapper" id="sidebar-wrapper">
        <ul class="nav">
            <li class="@if ($activePage == 'home') active @endif">
                <a href="{{ route('admin.home') }}">
                    <i class="now-ui-icons design_app"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#master-menu">
                    <i class="fas fa-list"></i>
                    <p>
                        {{ __("Master") }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse @if (@$parent == 'master') show @endif" id="master-menu">
                    <ul class="nav">
                        <li class="@if ($activePage == 'user') active @endif">
                            <a href="{{route('admin.user.index')}}">
                                <i class="now-ui-icons users_single-02"></i>
                                <p> {{ __("Penyewa") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'field') active @endif">
                            <a href="{{route('admin.field.index')}}">
                                <i><img src="{{ asset('icon/field.svg') }}" alt="lapangan" style="height: 20px;"></i>
                                <p> {{ __("Lapangan") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'ball') active @endif">
                            <a href="{{route('admin.ball.index')}}">
                                <i><img src="{{ asset('icon/badminton.svg') }}" alt="Shuttlecock" style="height: 20px;"></i>

                                <p> {{ __("Merk Bola") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'shuttlecock') active @endif">
                            <a href="{{ route('admin.shuttlecock.index') }}">
                                <i><img src="{{ asset('icon/badminton.svg') }}" alt="Shuttlecock" style="height: 20px;"></i>
                                <p> {{ __("Shuttlecock") }} </p>
                            </a>
                        </li>


                        <li class="@if ($activePage == 'payment-type') active @endif">
                            <a href="{{route('admin.paymentType.index')}}">
                                <i class="fas fa-money-check"></i>
                                <p> {{ __("Metode Pembayaran") }} </p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#order-menu">
                    <i class="fas fa-receipt"></i>
                    <p>
                        {{ __("Orderan") }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse @if (@$parent == 'order') show @endif" id="order-menu">
                    <ul class="nav">
                        <li class="@if ($activePage == 'rekap') active @endif">
                            <a href="{{route('admin.summary.index')}}">
                                <i class="fas fa-receipt"></i>
                                <p> {{ __("Rekap Order") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'shuttle-order') active @endif">
                            <a href="{{ route('admin.shuttle_order.index') }}">
                                <i class="fas fa-receipt"></i>
                                <p> {{ __("Shuttle Order") }} </p>
                            </a>
                        </li>
                        <li class="@if ($activePage == 'income') active @endif">
                            <a href="{{ route('admin.laporan') }}">
                                <i class="fas fa-hand-holding-usd"></i>
                                <p>{{ __("Laporan") }}</p>
                            </a>
                        </li>



                    </ul>
                </div>
            </li>
            <!-- <li class="@if ($activePage == 'setting') active @endif">
                <a href="#">
                    <i class="fas fa-calendar-alt"></i>
                    <p>{{ __('Jadwal Futsal') }}</p>
                </a>
            </li>
            <li class="@if ($activePage == 'setting') active @endif">
                <a href="#">
                    <i class="fas fa-cogs"></i>
                    <p>{{ __('Pengaturan') }}</p>
                </a>
            </li> -->
        </ul>
    </div>
</div>