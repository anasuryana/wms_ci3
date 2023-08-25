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
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Date [from]</span>
                    <input type="text" class="form-control" id="rconv_test_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Date [to]</span>
                    <input type="text" class="form-control" id="rconv_test_txt_dt2" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Nomor Aju</span>
                    <input type="text" class="form-control" id="rconv_test_txt_aju">
                </div>
            </div>
            <div class="col-md-3 mb-1">
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
                            <li><a class="dropdown-item" href="#" onclick="rconv_test_btn_clipboard_eCK()"><i class="fas fa-clipboard"></i> Clipboard</a></li>                            
                        </ul>
                    </div>                   
                </div>
            </div>            
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rconv_test_divku">
                    <table id="rconv_test_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle ">Nomor Aju</th>
                                <th  class="align-middle ">Model Code</th>
                                <th  class="align-middle text-end">Model Qty</th>
                                <th  class="align-middle ">Part Code</th>
                                <th  class="align-middle ">Part Description</th>
                                <th  class="align-middle text-center">PER</th>
                                <th  class="align-middle text-end">Part Qty</th>
                                <th  class="align-middle text-end">Part Price</th>
                                <th  class="align-middle text-end">Amount</th>
                                <th  class="align-middle text-center">Bom Revision</th>                                                          
                            </tr>                            
                        </thead>
                        <tbody>                        
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-center">Total</td>
                                <td id="rconv_test_tbl_foot" class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
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
    $("#rconv_test_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#rconv_test_txt_dt").datepicker('update', new Date())
    $("#rconv_test_txt_dt2").datepicker('update', new Date())
    function rconv_test_btn_xls_eCK(){
        const ajuDate = document.getElementById('rconv_test_txt_dt').value
        const ajuDate2 = document.getElementById('rconv_test_txt_dt2').value
        const model = document.getElementById('rconv_test_txt_model').value
        const nomorAju = document.getElementById('rconv_test_txt_aju').value
        Cookies.set('CKPSI_DATE1', ajuDate , {expires:365})
        Cookies.set('CKPSI_DATE2', ajuDate2 , {expires:365})
        Cookies.set('CKPSI_MODEL', model , {expires:365})
        Cookies.set('CKPSI_AJU', nomorAju , {expires:365})
        window.open("<?=base_url('conversion_test_doc_as_xls')?>" ,'_blank');
    }
    function rconv_test_btn_gen_eCK(){
        const ajuDate = document.getElementById('rconv_test_txt_dt').value
        const ajuDate2 = document.getElementById('rconv_test_txt_dt2').value
        const model = document.getElementById('rconv_test_txt_model').value
        const nomorAju = document.getElementById('rconv_test_txt_aju').value
        const btn = document.getElementById('rconv_test_btn_gen')
        btn.disabled = true
        btn.innerHTML = 'Please wait'
        $.ajax({
            type: "GET",
            url: "<?=base_url('SER/conversion_test')?>",
            data: {ajuDate: ajuDate, model: model, nomoraju: nomorAju, ajuDate2:ajuDate2},
            dataType: "json",
            success: function (response) {
                btn.disabled = false
                btn.innerHTML = 'Search'
                const ttlrows = response.data.length
                const ttlrows_ = response.data_.length
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
                    let Amount = response.data[i].RMQT * response.data[i].PART_PRICE
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].DLV_ZNOMOR_AJU
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].SER_ITMID
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].DLVQT).format(',')
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].SERD2_ITMCD
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data[i].PARTDESCRIPTION
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].PER*1
                    newcell = newrow.insertCell(6)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].RMQT).format(',')
                    newcell = newrow.insertCell(7)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].PART_PRICE
                    newcell = newrow.insertCell(8)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(Amount).format('0,0.0000')
                    newcell = newrow.insertCell(9)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].PPSN1_BOMRV
                    ttlper+=response.data[i].PER*1
                }
                for (let i = 0; i<ttlrows_; i++){
                    newrow = tableku2.insertRow(-1)
                    newrow.classList.add('table-info')
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data_[i].DLV_ZNOMOR_AJU
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data_[i].SER_ITMID
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data_[i].DLVQT).format(',')
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data_[i].SERD2_ITMCD
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data_[i].PARTDESCRIPTION
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data_[i].PER*1
                    newcell = newrow.insertCell(6)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data_[i].RMQT).format(',')
                    newcell = newrow.insertCell(7)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].PART_PRICE
                    newcell = newrow.insertCell(8)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(Amount).format('0,0.0000')
                    newcell = newrow.insertCell(9)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data_[i].PPSN1_BOMRV
                    ttlper+=response.data_[i].PER*1
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

    function rconv_test_btn_clipboard_eCK(){
        cmpr_selectElementContents(document.getElementById('rconv_test_tbl'))
        document.execCommand("copy");
        alertify.message("Copied");
    }

    $("#rconv_test_divku").css('height', $(window).height()   
    -document.getElementById('rconv_test_stack1').offsetHeight 
    -document.getElementById('rconv_test_stack2').offsetHeight     
    -100);
</script>