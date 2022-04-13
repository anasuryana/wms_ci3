<style>
    .anastylesel_sim{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;        
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 25px;
    }  
</style>

<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row" id="rconv_test_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Date</span>
                    <input type="text" class="form-control" id="rconv_test_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Model Code</span>
                    <input type="text" class="form-control" id="rconv_test_txt_model">
                </div>
            </div>
        </div>
       
        <div class="row" id="rconv_test_stack2">
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rconv_test_btn_gen" onclick="rconv_test_btn_gen_eCK()">Search</button>    
                    <div class="btn-group btn-group-sm" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-file-export"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="#" id="rconv_test_btn_xls" onclick="rconv_test_btn_xls_eCK()"><span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> XLS</a></li>
                        </ul>
                    </div>                   
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <span id="rconv_test_lblinfo" class="badge bg-info"></span>               
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rconv_test_divku">
                    <table id="rconv_test_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle ">Model Code</th>
                                <th  class="align-middle ">Model Description</th>
                                <th  class="align-middle ">Part Code</th>
                                <th  class="align-middle ">Part Description</th>
                                <th  class="align-middle text-center">PER</th>
                                <th  class="align-middle text-center">Bom Revision</th>                                                          
                            </tr>                            
                        </thead>
                        <tbody>                        
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-center">Total</td>
                                <td id="rconv_test_tbl_foot" class="text-center"></td>
                                <td class="text-center"></td>                                
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>           	
    </div>
</div>
<script>
    $("#rconv_test_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#rconv_test_txt_dt").datepicker('update', new Date())
    function rconv_test_btn_gen_eCK(){
        const ajuDate = document.getElementById('rconv_test_txt_dt').value
        const model = document.getElementById('rconv_test_txt_model').value
        const btn = document.getElementById('rconv_test_btn_gen')
        btn.disabled = true
        btn.innerHTML = 'Please wait'
        $.ajax({
            type: "GET",
            url: "<?=base_url('SER/conversion_test')?>",
            data: {ajuDate: ajuDate, model: model},
            dataType: "json",
            success: function (response) {
                btn.disabled = false
                btn.innerHTML = 'Search'
                const ttlrows = response.data.length
                let mydes = document.getElementById("rconv_test_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rconv_test_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rconv_test_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                const TotalPerContainer = myfrag.getElementById("rconv_test_tbl_foot")
                tableku2.innerHTML='';
                let ttlper = 0          
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].SER_ITMID
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].FGDESCRIPTION
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].SERD2_ITMCD
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].RMDESCRIPTION
                    newcell = newrow.insertCell(4)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].PER*1
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].PPSN1_BOMRV*1
                    ttlper+=response.data[i].PER*1
                }
                TotalPerContainer.innerHTML = ttlper
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow)
                btn.disabled = false
                btn.innerHTML = 'Search'
            }
        });
    }
</script>