<section class="content m-4">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>



    <!-- Content Wrapper -->
    <section class="content m-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="far fa-chart-bar"></i>
                    Distribusi Modal Aset
                </h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="donutChart"
                    style="min-height: 300px; height: 300px; max-height: 300px; width: 100%;"></canvas>
            </div>
            <!-- /.card-body-->
        </div>
    </section>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- Inisialisasi Chart.js -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const donutCtx = document.getElementById("donutChart").getContext("2d");

        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ["Modal", "Utang", "Asset"],
                datasets: [{
                    data: [50, 30, 20],
                    backgroundColor: ['#007bff', '#dc3545', '#ffc107'],
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    position: 'bottom',
                }
            }
        });
    });
    </script>
    </body>



</section>