<div class="card-body">
    <!-- ESTADÍSTICAS GLOBALES (SIN FILTRO DE SERVICIO) -->
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
        <!-- Repite el resto de cajas globales que tenías: Sacas, Abiertos, Cerrados, etc. -->
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
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>{{ $totalDespachosExpeditos }}</h3>
                    <p>Total Despachos Expeditos</p>
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
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalDespachosAdmitidos }}</h3>
                    <p>Total Despachos Admitidos</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Más Información <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- GRÁFICAS GLOBALES -->
    <div class="row mt-4">
        <!-- Despachos por Dpto (Global) -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Despachos por Departamento (GLOBAL)</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartDespachosGlobal"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sacas por Dpto (Global) -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Sacas por Departamento (GLOBAL)</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartSacasGlobal"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paquetes por Dpto (Global) -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Paquetes por Departamento (GLOBAL)</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartPaquetesGlobal"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ====================================================== -->
    <!-- SECCIÓN PARA EMS -->
    <!-- ====================================================== -->
    <hr>
    <h4>Estadísticas para Servicio EMS</h4>
    <div class="row">
        <div class="col-lg-2 col-6">
            <div class="small-box bg-light">
                <div class="inner">
                    <h3>{{ $totalDespachosEMS }}</h3>
                    <p>Despachos EMS</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalSacasEMS }}</h3>
                    <p>Sacas EMS</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <p class="small-box-footer">
                    {{ now()->format('Y-m-d') }}
                </p>
            </div>
        </div>
        <!-- Repite para Abiertos, Cerrados, etc. -->
        <div class="col-lg-2 col-6">
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>{{ $totalDespachosAbiertosEMS }}</h3>
                    <p>Despachos EMS (Abiertos)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalDespachosCerradosEMS }}</h3>
                    <p>Despachos EMS (Cerrados)</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <!-- ... repite Expeditos y Admitidos -->
        <div class="col-lg-2 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalDespachosExpeditosEMS }}</h3>
                    <p>Despachos EMS (Expeditos)</p>
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
                    <h3>{{ $totalDespachosAdmitidosEMS }}</h3>
                    <p>Despachos EMS (Admitidos)</p>
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

    <!-- GRÁFICAS PARA EMS -->
    <div class="row mt-4">
        <!-- Despachos por Departamento (EMS) -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Despachos EMS por Departamento</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartDespachosEMS"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sacas por Departamento (EMS) -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Sacas EMS por Departamento</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartSacasEMS"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paquetes por Departamento (EMS) -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Paquetes EMS por Departamento</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartPaquetesEMS"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ====================================================== -->
    <!-- SECCIÓN PARA LC -->
    <!-- ====================================================== -->
    <hr>
    <h4>Estadísticas para Servicio LC</h4>
    <div class="row">
        <div class="col-lg-2 col-6">
            <div class="small-box bg-light">
                <div class="inner">
                    <h3>{{ $totalDespachosLC }}</h3>
                    <p>Despachos LC</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalSacasLC }}</h3>
                    <p>Sacas LC</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <p class="small-box-footer">
                    {{ now()->format('Y-m-d') }}
                </p>
            </div>
        </div>
        <!-- Abiertos, Cerrados, etc. -->
        <div class="col-lg-2 col-6">
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>{{ $totalDespachosAbiertosLC }}</h3>
                    <p>Despachos LC (Abiertos)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalDespachosCerradosLC }}</h3>
                    <p>Despachos LC (Cerrados)</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalDespachosExpeditosLC }}</h3>
                    <p>Despachos LC (Expeditos)</p>
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
                    <h3>{{ $totalDespachosAdmitidosLC }}</h3>
                    <p>Despachos LC (Admitidos)</p>
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

    <!-- GRÁFICAS PARA LC -->
    <div class="row mt-4">
        <!-- Despachos por Departamento (LC) -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Despachos LC por Departamento</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartDespachosLC"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sacas por Departamento (LC) -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Sacas LC por Departamento</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartSacasLC"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paquetes por Departamento (LC) -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Paquetes LC por Departamento</h5>
                </div>
                <div class="card-body" style="height: 500px;">
                    <div style="height: 100%;">
                        <canvas id="chartPaquetesLC"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- INCLUYE TUS SCRIPTS DE CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.x/dist/chart.min.js"></script>
<script>
    // ============================
    //   GRÁFICAS GLOBALES
    // ============================
    const despachosDataGlobal = @json($despachosPorDepartamento);
    const labelsDespachosGlobal = despachosDataGlobal.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const valuesDespachosGlobal = despachosDataGlobal.map(item => item.total);

    new Chart(document.getElementById('chartDespachosGlobal').getContext('2d'), {
        type: 'pie',
        data: {
            labels: labelsDespachosGlobal,
            datasets: [{
                data: valuesDespachosGlobal,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#1cc88a', '#F5B7B1', '#BB8FCE'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const sacasDataGlobal = @json($sacasPorDepartamento);
    const labelsSacasGlobal = sacasDataGlobal.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const valuesSacasGlobal = sacasDataGlobal.map(item => item.total);

    new Chart(document.getElementById('chartSacasGlobal').getContext('2d'), {
        type: 'pie',
        data: {
            labels: labelsSacasGlobal,
            datasets: [{
                data: valuesSacasGlobal,
                backgroundColor: ['#17a2b8', '#8e44ad', '#f39c12', '#2ecc71', '#e74c3c', '#a569bd'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const paquetesDataGlobal = @json($paquetesPorDepartamento);
    const labelsPaquetesGlobal = paquetesDataGlobal.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const valuesPaquetesGlobal = paquetesDataGlobal.map(item => item.total);

    new Chart(document.getElementById('chartPaquetesGlobal').getContext('2d'), {
        type: 'pie',
        data: {
            labels: labelsPaquetesGlobal,
            datasets: [{
                data: valuesPaquetesGlobal,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#858796', '#f6c23e'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });


    // ============================
    //   GRÁFICAS PARA EMS
    // ============================
    const emsDespachosData = @json($emsDespachosPorDepartamento);
    const emsDespachosLabels = emsDespachosData.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const emsDespachosValues = emsDespachosData.map(item => item.total);

    new Chart(document.getElementById('chartDespachosEMS').getContext('2d'), {
        type: 'pie',
        data: {
            labels: emsDespachosLabels,
            datasets: [{
                data: emsDespachosValues,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#1cc88a', '#F5B7B1', '#BB8FCE'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const emsSacasData = @json($emsSacasPorDepartamento);
    const emsSacasLabels = emsSacasData.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const emsSacasValues = emsSacasData.map(item => item.total);

    new Chart(document.getElementById('chartSacasEMS').getContext('2d'), {
        type: 'pie',
        data: {
            labels: emsSacasLabels,
            datasets: [{
                data: emsSacasValues,
                backgroundColor: ['#17a2b8', '#8e44ad', '#f39c12', '#2ecc71', '#e74c3c', '#a569bd'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const emsPaquetesData = @json($emsPaquetesPorDepartamento);
    const emsPaquetesLabels = emsPaquetesData.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const emsPaquetesValues = emsPaquetesData.map(item => item.total);

    new Chart(document.getElementById('chartPaquetesEMS').getContext('2d'), {
        type: 'pie',
        data: {
            labels: emsPaquetesLabels,
            datasets: [{
                data: emsPaquetesValues,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#858796', '#f6c23e'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });


    // ============================
    //   GRÁFICAS PARA LC
    // ============================
    const lcDespachosData = @json($lcDespachosPorDepartamento);
    const lcDespachosLabels = lcDespachosData.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const lcDespachosValues = lcDespachosData.map(item => item.total);

    new Chart(document.getElementById('chartDespachosLC').getContext('2d'), {
        type: 'pie',
        data: {
            labels: lcDespachosLabels,
            datasets: [{
                data: lcDespachosValues,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#1cc88a', '#F5B7B1', '#BB8FCE'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const lcSacasData = @json($lcSacasPorDepartamento);
    const lcSacasLabels = lcSacasData.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const lcSacasValues = lcSacasData.map(item => item.total);

    new Chart(document.getElementById('chartSacasLC').getContext('2d'), {
        type: 'pie',
        data: {
            labels: lcSacasLabels,
            datasets: [{
                data: lcSacasValues,
                backgroundColor: ['#17a2b8', '#8e44ad', '#f39c12', '#2ecc71', '#e74c3c', '#a569bd'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const lcPaquetesData = @json($lcPaquetesPorDepartamento);
    const lcPaquetesLabels = lcPaquetesData.map(item => item.depto || 'SIN_DEPARTAMENTO');
    const lcPaquetesValues = lcPaquetesData.map(item => item.total);

    new Chart(document.getElementById('chartPaquetesLC').getContext('2d'), {
        type: 'pie',
        data: {
            labels: lcPaquetesLabels,
            datasets: [{
                data: lcPaquetesValues,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#858796', '#f6c23e'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
