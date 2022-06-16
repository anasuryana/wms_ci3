<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row" id="stinventory_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Year</span>                    
                    <input type="number" class="form-control" id="stinventory_txt_year" onchange="stkinventory_getdate()">
                </div>
            </div>            
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Month</span>
                    <select class="form-select" id="stinventory_cmb_month" onchange="stkinventory_getdate()">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
            </div>            
        </div>
       
        <div class="row" id="stinventory_stack2">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Date</span>
                    <select class="form-select" id="stinventory_cmb_date">
                    </select>
                </div>
            </div>
            
            <div class="col-md-4 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="stinventory_btn_gen" onclick="stinventory_e_upload(this)">Upload</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div id="stinventory_div_infoAfterPost">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('stinventory_txt_year').value =new Date().getFullYear()
    stkinventory_getdate()
    function stkinventory_getdate() 
    {
        const invYear = document.getElementById('stinventory_txt_year').value
        const invMonth = document.getElementById('stinventory_cmb_month').value
        const txtdate = document.getElementById('stinventory_cmb_date')
        txtdate.innerHTML = '<option value="0">Please wait</option>'
        $.ajax({
            type: "GET",
            url: "<?=base_url('ITH/inventory_date')?>",
            data: {year: invYear, month: invMonth},
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length
                let strDate = ''
                for(let i=0;i<ttlrows;i++){
                    strDate += `<option value="${response.data[i].ICYC_STKDT}">${response.data[i].ICYC_STKDT}</option>`
                }
                txtdate.innerHTML = strDate
            }
        })
    }

    function stinventory_e_upload(p)
    {
        const invDate = document.getElementById('stinventory_cmb_date')
        const myAlert = document.getElementById("stinventory_div_infoAfterPost")
        if(invDate.value =='null') {
            alertify.warning('Date is invalid')
            invDate.focus()
            return
        }
        if(confirm('Are you sure ?')) {
            p.disabled = true
            p.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'
            $.ajax({
                type: "POST",
                url: "<?=base_url('ITH/MEGAAllLocationToInventory')?>",
                data: {indate: invDate.value},
                dataType: "JSON",
                success: function (response) {
                    p.disabled = false
                    p.innerHTML = 'Upload'
                    let alertType = response.status[0].cd==='1' ? 'alert-success' : 'alert-warning'                
                    myAlert.innerHTML = `<div class="alert ${alertType} alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Well done!</h4>
                    <p>
                    ${response.status[0].msg}
                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`
                }, error : function(xhr, xopt, xthrow){                
                    alertify.error(xthrow)
                    p.disabled = false
                    p.innerHTML = 'Upload'
                }
            })
        }
    }
</script>