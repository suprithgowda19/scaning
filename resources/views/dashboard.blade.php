@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-sm-6">
        <h3>Dashboard</h3>
      </div>
      <div class="col-12 col-sm-6">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a class="home-item" href="#">
              <i data-feather="home"></i>
            </a>
          </li>
          <li class="breadcrumb-item">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="container mt-4">
  <div class="row g-4">
    <div class="col-md-6">
      <label class="fw-bold mb-1 fs-6">Date</label>
      <div class="card p-2 mb-0">
        <input type="date" class="form-control border-0 text-center" value="2025-11-22">
      </div>
    </div>
    <div class="col-md-6">
      <label class="fw-bold mb-1 fs-6">Current Slot</label>
      <div class="card p-2 mb-0">
        <select class="form-select border-0 text-center">
          <option selected>Slot 1</option>
          <option>Slot 2</option>
          <option>Slot 3</option>
          <option>Slot 4</option>
        </select>
      </div>
    </div>
  </div>
</div>

<div class="container mt-3 mb-3">
  <div class="row g-3">
    @for ($i = 1; $i <= 6; $i++)
      <div class="col-md-4">
        <div class="card p-3 text-center mb-0 shadow-sm hover-shadow">
          <h4>Screen {{ $i }}</h4>
          <h5 class="fw-bold">Movie Title – Movie</h5>
          <div class="row mt-2 g-2">
            <div class="col-6">
              <div class="card p-2 text-center mb-0 shadow-sm hover-shadow">
                <small class="text-muted">Total Capacity</small>
                <h6 class="fw-bold">{{ rand(150,300) }}</h6>
              </div>
            </div>
            <div class="col-6">
              <div class="card p-2 text-center mb-0 shadow-sm hover-shadow">
                <small class="text-muted">Scanned count</small>
                <h6 class="fw-bold">{{ rand(50,200) }}</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endfor
  </div>
</div>

<div class="container">
  <div class="card p-4" style="border-radius: 20px;">
    <div class="row g-4">
      <div class="col-md-5">
        <div class="card p-4 h-100">
          <h5 class="fw-bold mb-3">Category of Counts</h5>
          <ul class="list-unstyled mb-0">
            <li class="d-flex justify-content-between border-bottom py-2"><span>Guest</span><span>100</span></li>
            <li class="d-flex justify-content-between border-bottom py-2"><span>Delegate</span><span>29</span></li>
            <li class="d-flex justify-content-between border-bottom py-2"><span>Students</span><span>30</span></li>
            <li class="d-flex justify-content-between border-bottom py-2"><span>VVIP</span><span>10</span></li>
            <li class="d-flex justify-content-between border-bottom py-2"><span>VIP</span><span>80</span></li>
            <li class="d-flex justify-content-between py-2"><span>Organization</span><span>5</span></li>
          </ul>
        </div>
      </div>
      <div class="col-md-7">
        <div class="card p-4 h-100">
          <h5 class="fw-bold mb-3">Category Pie Chart</h5>
          <div id="categoryPieChart" style="height:350px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card p-4 shadow-sm" style="border-radius: 20px;">
  <h5 class="fw-bold mb-3">Attended Count by Screen</h5>
  <div id="attendedBarGraph"></div>
</div>

@endsection

@push('scripts')

{{-- MASTER-COMPATIBLE ASSET PATHS --}}
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    if (typeof ApexCharts === 'undefined') {
        console.error('ApexCharts not loaded');
        return;
    }

    new ApexCharts(
        document.querySelector("#categoryPieChart"),
        {
            chart: { type: 'pie', height: 330 },
            series: [100, 29, 30, 10, 80, 5],
            labels: ['Guest','Delegate','Students','VVIP','VIP','Organization'],
            legend: { position: 'bottom' }
        }
    ).render();

    new ApexCharts(
        document.querySelector("#attendedBarGraph"),
        {
            chart: { type: 'bar', height: 350, toolbar: { show: false } },
            series: [{
                name: "Attended",
                data: [80,120,60,90,140,110,70,95,50,130,85]
            }],
            plotOptions: { bar: { borderRadius: 8, columnWidth: '30%' } },
            xaxis: {
                categories: [
                  "Screen 1","Screen 2","Screen 3","Screen 4","Screen 5",
                  "Screen 6","Screen 7","Screen 8","Screen 9","Screen 10","Screen 11"
                ]
            },
            colors: ["#4154f1"]
        }
    ).render();

});
</script>

@endpush
