@extends('layouts.app', [
'namePage' => 'Dashboard',
'class' => 'login-page sidebar-mini ',
'activePage' => 'home',
'backgroundImage' => asset('now') . "/img/bg14.jpg",
])
@section('title','Dashboard')

@section('content')
<style>
    .hover-zoom {
        transition: transform 0.3s ease-in-out;
    }

    .hover-zoom:hover {
        transform: scale(1.05);
    }

    .card.card-stats {
        border-radius: 1rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        color: #fff;
    }

    .icon-box {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        background-color: rgba(255, 255, 255, 0.15);
    }

    .icon-box i {
        font-size: 2.8rem;
        
    }

    .icon-box img {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }

    .bg-user {
        background: linear-gradient(135deg, rgb(255, 0, 0) 0%,rgb(243, 34, 34) 100%);
    }

    .bg-field {
        background: linear-gradient(135deg, rgb(27, 202, 27) 0%, #38ef7d 100%);
    }

    .bg-shuttle {
        background: linear-gradient(135deg, rgb(62, 143, 235) 0%, rgb(37, 127, 245) 100%);
    }

    .numbers .card-category {
        font-size: 0.9rem;
        color: #eaeaea;
    }

    .numbers .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #fff;
    }
</style>


<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="row">
        {{-- Jumlah Pengguna --}}
        {{-- Jumlah Pengguna --}}
        <div class="col-lg-4 col-md-6">
            <div class="card card-stats hover-zoom bg-user">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-box">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                        <div class="col-7 d-flex align-items-center">
                            <div class="numbers">
                                <p class="card-category mb-1">Jumlah Pengguna</p>
                                <h4 class="card-title">{{ $userCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Lapangan --}}
        <div class="col-lg-4 col-md-6">
            <div class="card card-stats hover-zoom bg-field">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-box">
                                <img src="{{ asset('icon/field.svg') }}" alt="Lapangan">
                            </div>
                        </div>
                        <div class="col-7 d-flex align-items-center">
                            <div class="numbers">
                                <p class="card-category mb-1">Order Lapangan</p>
                                <h4 class="card-title">{{ $orderCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Shuttlecock --}}
        <div class="col-lg-4 col-md-6">
            <div class="card card-stats hover-zoom bg-shuttle">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-box">
                                <img src="{{ asset('icon/badminton.svg') }}" alt="Shuttlecock">
                            </div>
                        </div>
                        <div class="col-7 d-flex align-items-center">
                            <div class="numbers">
                                <p class="card-category mb-1">Order Shuttlecock</p>
                                <h4 class="card-title">{{ $shuttleCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Grafik --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card hover-zoom">
                    <div class="card-header">
                        <h5 class="card-title">Grafik Order Lapangan & Shuttlecock</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="orderChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('orderChart').getContext('2d');

            const orderChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Lapangan', 'Shuttlecock'],
                    datasets: [{
                        label: 'Total Order',
                        data: [{{$orderCount}}, {{$shuttleCount}}],
                        backgroundColor: ['#2dce89', '#f5365c'],
                        borderColor: ['#1abc9c', '#e74c3c'],
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush