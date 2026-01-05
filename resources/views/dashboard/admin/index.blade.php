@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

{{-- =========================
   PAGE HEADER
========================= --}}
<div class="container-fluid mb-3">
  <div class="page-title d-flex justify-content-between align-items-center">
    <div>
      <h3 class="mb-0">Dashboard</h3>
    </div>
  </div>
</div>

{{-- TODAY + TIME --}}
<div class="container-fluid mb-3">
  <div class="row g-3">
    <div class="col-md-6">
      <div class="card p-3 text-center shadow-sm">
        <small class="text-muted d-block">Today</small>
        <span class="fw-bold fs-6">{{ \Carbon\Carbon::today()->format('d M Y') }}</span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3 text-center shadow-sm">
        <small class="text-muted d-block">Current Time</small>
        <span class="fw-bold fs-6">{{ now()->format('h:i A') }}</span>
      </div>
    </div>
  </div>
</div>

{{-- =========================
   LIVE SCREEN CARDS
========================= --}}
<div class="container">
  <div class="row g-3">

    @foreach ($liveCards as $card)
      <div
        class="col-xl-3 col-lg-4 col-md-6"
        data-screen="{{ $card['screen_name'] }}"
      >
        <div class="card shadow-sm h-100 text-center">
          <div class="card-body">

            <h5 class="mb-1">{{ $card['screen_name'] }}</h5>
            <div class="fw-bold text-primary mb-2">
              {{ $card['movie_title'] }}
            </div>

            <div class="row g-2">
              <div class="col-6">
                <div class="border rounded py-2">
                  <small class="text-muted d-block">Capacity</small>
                  <span class="fw-bold">{{ $card['capacity'] }}</span>
                </div>
              </div>
              <div class="col-6">
                <div class="border rounded py-2">
                  <small class="text-muted d-block">Scanned</small>
                  <span class="fw-bold scanned-count">
                    {{ $card['scanned_count'] }}
                  </span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    @endforeach

  </div>
</div>

{{-- =========================
   ANALYTICS FILTERS
========================= --}}
<div class="container mt-5">
  <div class="card p-3 shadow-sm">
    <form id="filterForm" class="row g-3 align-items-end">

      <div class="col-md-4">
        <label class="fw-bold">Date</label>
        <input type="date" id="filter_date" value="{{ $filterDate }}" class="form-control">
      </div>

      <div class="col-md-4">
        <label class="fw-bold">Screen</label>
        <select id="filter_screen_id" class="form-select">
          <option value="">All Screens</option>
          @foreach ($screens as $screen)
            <option value="{{ $screen->id }}" @selected($filterScreen == $screen->id)>
              {{ $screen->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <button class="btn btn-primary w-100">Apply Filters</button>
      </div>

    </form>
  </div>
</div>

{{-- =========================
   CATEGORY + PIE
========================= --}}
<div class="container mt-4">
  <div class="card p-4 shadow-sm">
    <div class="row g-4 align-items-center">

      <div class="col-lg-5">
        <h5 class="fw-bold mb-3">Category Counts</h5>
        <ul class="list-unstyled mb-0" id="categoryList">
          @foreach ($categoryStats as $cat => $count)
            <li class="d-flex justify-content-between border-bottom py-2">
              <span>{{ ucfirst($cat) }}</span>
              <strong>{{ $count }}</strong>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="col-lg-7">
        <div id="categoryPieChart" style="height:320px;"></div>
      </div>

    </div>
  </div>
</div>

{{-- =========================
   BAR CHART
========================= --}}
<div class="container mt-4 mb-5">
  <div class="card p-4 shadow-sm">
    <h5 class="fw-bold mb-3">Attended Count by Screen</h5>
    <div id="attendedBarGraph" style="height:360px;"></div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>

<script>
let pieChart, barChart;

function initCharts(categoryStats, screenStats) {

  pieChart = new ApexCharts(document.querySelector("#categoryPieChart"), {
    chart: { type: 'pie', height: 320 },
    series: Object.values(categoryStats),
    labels: Object.keys(categoryStats),
    legend: { position: 'bottom' }
  });
  pieChart.render();

  barChart = new ApexCharts(document.querySelector("#attendedBarGraph"), {
    chart: { type: 'bar', height: 350, toolbar: { show: false } },
    series: [{ name: "Attended", data: screenStats.map(s => s.total) }],
    xaxis: { categories: screenStats.map(s => s.name), labels: { rotate: -45 } },
    plotOptions: {
      bar: {
        borderRadius: 6,
        columnWidth: Math.max(12, Math.floor(80 / screenStats.length)) + '%'
      }
    },
    dataLabels: { enabled: false }
  });
  barChart.render();
}

function updateDashboard() {
  const params = new URLSearchParams({
    filter_date: document.getElementById('filter_date').value,
    filter_screen_id: document.getElementById('filter_screen_id').value
  });

  fetch(`{{ route('dashboard.admin.index') }}?${params}`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(res => res.json())
  .then(data => {

    /* LIVE CARD UPDATE */
    data.liveCards.forEach(card => {
      const el = document.querySelector(`[data-screen="${card.screen_name}"]`);
      if (el) {
        el.querySelector('.scanned-count').innerText = card.scanned_count;
      }
    });

    /* CATEGORY LIST */
    const list = document.getElementById('categoryList');
    list.innerHTML = '';
    Object.entries(data.categoryStats).forEach(([k, v]) => {
      list.insertAdjacentHTML('beforeend', `
        <li class="d-flex justify-content-between border-bottom py-2">
          <span>${k.charAt(0).toUpperCase() + k.slice(1)}</span>
          <strong>${v}</strong>
        </li>
      `);
    });

    pieChart.updateSeries(Object.values(data.categoryStats));

    barChart.updateOptions({
      xaxis: { categories: data.screenStats.map(s => s.name) }
    });
    barChart.updateSeries([{ data: data.screenStats.map(s => s.total) }]);
  });
}

document.getElementById('filterForm').addEventListener('submit', e => {
  e.preventDefault();
  updateDashboard();
});

document.addEventListener('DOMContentLoaded', function () {
  initCharts(@json($categoryStats), @json($screenStats));
  setInterval(updateDashboard, 3000);
});
</script>
@endpush
