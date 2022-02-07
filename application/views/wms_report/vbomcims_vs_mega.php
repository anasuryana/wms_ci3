<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Assy Code</label>
                    <input type="text" class="form-control" id="cmpr_txtAssycode">                    
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Revision</label>
                    <input type="number" class="form-control" id="cmpr_txtREV" value="0" min="0">
                </div>
            </div>
        </div>            
        <div class="row">
            <div class="col-md-12 mb-2 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="cmpr_btncompare" onclick="cmpr_btncompare()" class="btn btn-primary" >Compare</button>
                    <button title="Copy to clipboard" id="cmpr_btnexcel" onclick="cmpr_btnexcel()" class="btn btn-success" ><i class="fas fa-clipboard"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2" >
                <div class="table-responsive" id="cmpr_tbl_div">
                    <table id="cmpr_tbl" class="table table-sm table-hover table-bordered" style="width:100%;font-size:80%">
                        <thead class="table-light">
                            <tr>
                                <th colspan="2" class="text-center">ASSY CODE</th>                            
                                <th colspan="2" class="text-center">REV</th>
                                <th rowspan="2" class="align-middle">MAIN PARTS</th>
                                <th rowspan="2" class="align-middle">Maker Part No</th>
                                <th rowspan="2" class="align-middle">MAIN PARTS CIMS</th>
                                <th rowspan="2" class="align-middle">Maker Part No</th>
                                <th colspan="2" class="text-center">QTY PER</th>
                                <th rowspan="2" class="align-middle">Diff</th>
                            </tr>
                            <tr>
                                <th class="text-center">MEGA</th>
                                <th class="text-center">CIMS</th>
                                <th class="text-center">MEGA</th>
                                <th class="text-center">CIMS</th>
                                <th class="text-center">MEGA</th>
                                <th class="text-center">CIMS</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" class="text-center">Total</td>
                                <td id="cmpr_tbl_foot_1" class="text-center"></td>
                                <td id="cmpr_tbl_foot_2" class="text-center"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>                
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
    function cmpr_btnexcel(){
        cmpr_selectElementContents(document.getElementById('cmpr_tbl'))
        document.execCommand("copy");
        alertify.message("Copied");
    }

    function cmpr_btncompare(){
        let assycode = document.getElementById('cmpr_txtAssycode').value;
        let rev = document.getElementById('cmpr_txtREV').value;
        if(assycode.trim().length==0){
            document.getElementById('cmpr_txtAssycode').focus();
            alertify.warning("Assy code is required");
            return;
        }
        $("#cmpr_tbl tbody").empty();
        document.getElementById('cmpr_btncompare').innerHTML = "<i class='fas fa-spinner fa-spin'></i>";
        document.getElementById('cmpr_tbl_foot_1').value = ''
        document.getElementById('cmpr_tbl_foot_2').value = ''
        $.ajax({
            type: "post",
            url: "<?=base_url('MSTITM/compare_bom_mega_cims')?>",
            data: {assycode: assycode, rev: rev},
            dataType: "json",
            success: function (response) {
                document.getElementById('cmpr_btncompare').innerHTML = "Compare";
                const ttlrows = response.data.length;
                let mydes = document.getElementById("cmpr_tbl_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("cmpr_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("cmpr_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let ttl_1 =0;
                let ttl_2 = 0;
                let diffres;
                for (let i = 0; i<ttlrows; i++){
                    const megaPER = numeral(response.data[i].MBOM_PARQT).value()
                    const cimsPER = numeral(response.data[i].CIMSPER).value()
                    ttl_1+=megaPER
                    ttl_2+=cimsPER
                    if(!response.data[i].MBOM_BOMPN || !response.data[i].MBLA_MITMCD){
                        diffres = '#N/A';
                    } else if (response.data[i].MBOM_BOMPN === response.data[i].MBLA_MITMCD && megaPER == cimsPER) {
                        diffres = 'OK';
                    } else {
                        diffres = '#N/A.';
                    }
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode(response.data[i].MBOM_MDLCD);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].MBLA_MDLCD);
                    newcell.appendChild(newText);
                    
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(rev);                   
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(rev);                    
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(response.data[i].MBOM_BOMPN);
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].MG_SPTNO);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(6);
                    newcell.title = "CIMS: Part No";
                    newText = document.createTextNode(response.data[i].MBLA_MITMCD);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(7);
                    newcell.title = "CIMS: maker part no";
                    newText = document.createTextNode(response.data[i].CM_SPTNO);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(8);
                    newcell.title = "MEGA: PER";
                    newText = document.createTextNode(numeral(response.data[i].MBOM_PARQT).format(','));
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);                    
                    newcell = newrow.insertCell(9);
                    newcell.title = "CIMS: PER";
                    newText = document.createTextNode(numeral(response.data[i].CIMSPER).format(','));
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(10);
                    newText = document.createTextNode(diffres);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);
                }
                myfrag.getElementById("cmpr_tbl_foot_1").innerText = ttl_1 
                myfrag.getElementById("cmpr_tbl_foot_2").innerText = ttl_2
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                if(ttlrows==0){
                    alertify.message("Not found");
                }
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('cmpr_btncompare').innerHTML = "Compare";
            }
        });
    }
</script>