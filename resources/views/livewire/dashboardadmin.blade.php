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
</div>
