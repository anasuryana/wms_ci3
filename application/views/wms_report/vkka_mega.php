<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row" id="rkka_mega_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Date [from]</span>
                    <input type="text" class="form-control" id="rkka_mega_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Date [to]</span>
                    <input type="text" class="form-control" id="rkka_mega_txt_dt2" readonly>
                </div>
            </div>           
        </div>
        <div class="row" id="rkka_mega_stack0">
            <div class="col-md-6 mb-1 ">
                <div class="card">
                    <div class="card-header text-center">Report Type</div>
                    <div class="card-body d-grid">
                        <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="rkka_mega_radio" id="btnradio1" autocomplete="off" value="RM" checked>
                            <label class="btn btn-outline-success" for="btnradio1">Material</label>
                            <input type="radio" class="btn-check" name="rkka_mega_radio" id="btnradio2" autocomplete="off" value="FG">
                            <label class="btn btn-outline-success" for="btnradio2">Fresh Finished Goods</label>
                            <input type="radio" class="btn-check" name="rkka_mega_radio" id="btnradio3" autocomplete="off" value="FG-RTN">
                            <label class="btn btn-outline-success" for="btnradio3">Return Finished Goods</label>
                            <input type="radio" class="btn-check" name="rkka_mega_radio" id="btnradio4" autocomplete="off" value="SCR">
                            <label class="btn btn-outline-success" for="btnradio4">Scrap</label>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-md-6 mb-1 d-grid text-center">
                <button type="button" class="btn btn-success btn-lg" onclick="rkka_toexcel()">Excel</button>
            </div>
        </div>      
    </div>
</div>
<script>
    $("#rkka_mega_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#rkka_mega_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#rkka_mega_txt_dt").datepicker('update', new Date());
    $("#rkka_mega_txt_dt2").datepicker('update', new Date())
    function rkka_toexcel(){
        const radioButtons = document.querySelectorAll('input[name="rkka_mega_radio"]')
        let reportType = ''
        for (const radioButton of radioButtons) { 
            if (radioButton.checked) {
                reportType = radioButton.value
                break
            }
        }
        if(reportType.length==0){
            alertify.message('Please select Report Type !')
            return
        }
        const date1 = document.getElementById('rkka_mega_txt_dt').value
        const date2 = document.getElementById('rkka_mega_txt_dt2').value
        Cookies.set('CKPSI_DDATE', date1 , {expires:365})
        Cookies.set('CKPSI_DDATE2', date2 , {expires:365})
        Cookies.set('CKPSI_DREPORT', reportType , {expires:365})
        window.open("<?=base_url('kka_mega_as_xls')?>" ,'_blank')
    }
</script>