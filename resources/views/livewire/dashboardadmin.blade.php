<div class="card-body">
    <div class="row">
        <div class="col-lg-2 col-6">
            <div class="small-box bg-light">
                <div class="inner">
                    <h3>{{ $totalDespachos }}</h3>
                    <p>Total Despachos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Más Información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-2 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalSacas }}</h3>
                    <p>Total Sacas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <p class="small-box-footer">
                    {{ now()->format('Y-m-d') }}
                </p>
            </div>
        </div>

        <div class="col-lg-2 col-6">
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>{{ $totalDespachosAbiertos }}</h3>
                    <p>Total Despachos Abiertos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Más Información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-2 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalDespachosCerrados }}</h3>
                    <p>Total Despachos Cerrados</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Más Información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-2 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalDespachosExpeditos }}</h3>
                    <p>Total Despachos Expeditos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <p class="small-box-footer">
                    {{ now()->format('Y-m-d') }}
                </p>
            </div>
        </div>

        <div class="col-lg-2 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalDespachosAdmitidos }}</h3>
                    <p>Total Despachos Admitidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <p class="small-box-footer">
                    {{ now()->format('Y-m-d') }}
                </p>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <!-- DESPACHOS POR DEPARTAMENTO -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Despachos por Departamento</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartDespachos"></canvas>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- SACAS POR DEPARTAMENTO -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Sacas por Departamento</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartSacas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- PAQUETES POR DEPARTAMENTO -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Paquetes por Departamento</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartPaquetes"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.x/dist/chart.min.js"></script>
<script>
    // 1) DESPACHOS POR DEPARTAMENTO
    const despachosData = @json($despachosPorDepartamento);
    const despachosLabels = despachosData.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const despachosValues = despachosData.map(item => item.total);

    const ctxDespachos = document.getElementById('chartDespachos').getContext('2d');
    new Chart(ctxDespachos, {
        type: 'pie',
        data: {
            labels: despachosLabels,
            datasets: [{
                data: despachosValues,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#1cc88a', '#F5B7B1', '#BB8FCE'
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // 2) SACAS POR DEPARTAMENTO
    const sacasData = @json($sacasPorDepartamento);
    const sacasLabels = sacasData.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const sacasValues = sacasData.map(item => item.total);

    const ctxSacas = document.getElementById('chartSacas').getContext('2d');
    new Chart(ctxSacas, {
        type: 'pie',
        data: {
            labels: sacasLabels,
            datasets: [{
                data: sacasValues,
                backgroundColor: [
                    '#17a2b8', '#8e44ad', '#f39c12', '#2ecc71', '#e74c3c', '#a569bd'
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // 3) PAQUETES POR DEPARTAMENTO
    const paquetesData = @json($paquetesPorDepartamento);
    const paquetesLabels = paquetesData.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const paquetesValues = paquetesData.map(item => item.total);

    const ctxPaquetes = document.getElementById('chartPaquetes').getContext('2d');
    new Chart(ctxPaquetes, {
        type: 'pie',
        data: {
            labels: paquetesLabels,
            datasets: [{
                data: paquetesValues,
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#858796', '#f6c23e'
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
