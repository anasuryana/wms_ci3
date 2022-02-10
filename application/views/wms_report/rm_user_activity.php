<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row" id="rm_ua_stack0">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="rm_ua_btnsync" onclick="rm_ua_btnsync_eCK()" title="Refresh"><i class="fas fa-sync"></i></button>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <canvas id="rm_ua_chart"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
function rm_ua_btnsync_eCK() {
    // myChart.data.labels = labels
    // myChart.data.datasets = [ {
    //   label: 'Dataset 1',
    //   data: [20,30,22],            
    //   borderColor: wms_randomRGB(),
    //   tension: 0.3
    // },
    // {
    //   label: 'Dataset 2',
    //   data: [20,10,40],         
    //   borderColor: wms_randomRGB(),
    //   tension: 0.3
    // }]
    // myChart.update();
    $.ajax({
        type: "GET",
        url: "<?=base_url('User/getUserActivity')?>",
        data: {year: 2022},
        dataType: "json",
        success: function (response) {
            myChart.data.labels = response.labels
            myChart.data.datasets = response.datasets
            myChart.update();
        }
    });
}
function wms_randomNum () {
    return Math.floor(Math.random() * (235 - 52 + 1) + 52)
} 
function wms_randomRGB() {
    return `rgb(${wms_randomNum()}, ${wms_randomNum()}, ${wms_randomNum()})`
} 
var ctx = document.getElementById('rm_ua_chart');
var DATA_COUNT = 7;
var NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

var labels = ['202101', '202102','202103']
var data = {
  labels: labels,
  datasets: [
    {
      label: 'Dataset 1',
      data: [20,30,22],            
      borderColor: wms_randomRGB(),
      tension: 0.3
    },
    {
      label: 'Dataset 2',
      data: [20,33,40],         
      borderColor: wms_randomRGB(),
      tension: 0.3
    }
  ]
};
var config = {
  type: 'line',
  data: data,
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Kitting Activity'
      }
    }
  },
};
var myChart = new Chart(ctx, config);

</script>