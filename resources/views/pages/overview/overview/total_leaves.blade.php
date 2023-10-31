<div class="card-body p-9">
    <style>
        .canvasjs-chart-credit {
            display: none !important;
        }
    </style>
    @php
        $leave_details = getTotalLeaveCount($info->id);
        $leave_chart_data = [$leave_details['allocated_total_leave'], $leave_details['taken_leave'], $leave_details['balance_leave'] ];
        $leave_chart_label = ['Allocated', 'Taken', 'Balance'];
    @endphp
    <div class="fs-2hx fw-bolder">{{ $leave_details['taken_leave'] }}</div>
    <div class="fs-4 fw-bold text-gray-400 mb-7">Total Leaves</div>

    <div class="d-flex flex-center me-9 mb-5">
        <canvas id="leave_chart" style="height: 200px; width: 100%;"></canvas>
    </div>
    <div class="d-flex flex-wrap">

        <div class="d-flex flex-column justify-content-center flex-row-fluid pe-11 mb-5">
            <div class="d-flex fs-6 fw-bold align-items-center mb-3">
                <div class="bullet bg-primary me-3"></div>
                <div class="text-gray-400">Allocated</div>
                <div class="ms-auto fw-bolder text-gray-700">{{ $leave_details['allocated_total_leave'] ?? 0 }}</div>
            </div>

            <div class="d-flex fs-6 fw-bold align-items-center mb-3">
                <div class="bullet bg-danger me-3"></div>
                <div class="text-gray-400">Taken</div>
                <div class="ms-auto fw-bolder text-gray-700">{{ $leave_details['taken_leave'] ?? 0 }}</div>
            </div>

            <div class="d-flex fs-6 fw-bold align-items-center">
                <div class="bullet bg-success me-3"></div>
                <div class="text-gray-400">Balance</div>
                <div class="ms-auto fw-bolder text-gray-700">{{ $leave_details['balance_leave'] ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var canvas = document.getElementById('leave_chart');
        var context = canvas.getContext('2d');
       
        var config = {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: @json($leave_chart_data),
                    backgroundColor: ['#00A3FF', '#cf6262', '#43a43c']
                }],
                labels: @json($leave_chart_label)
            },
            options: {
                chart: {
                    fontFamily: 'inherit'
                },
                cutout: '65%',
                cutoutPercentage: 70,
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: false
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                tooltips: {
                    enabled: true,
                    intersect: false,
                    mode: 'nearest',
                    bodySpacing: 30,
                    yPadding: 30,
                    xPadding: 30,
                    caretPadding: 0,
                    displayColors: false,
                    backgroundColor: '#20D489',
                    titleFontColor: '#ffffff',
                    cornerRadius: 5,
                    footerSpacing: 0,
                    titleSpacing: 20
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }                
            }
        };
        var myDoughnut = new Chart(canvas, config);

    })
</script>
