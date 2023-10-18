<style>
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 26px;
    }
</style>
<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="fginventory_btn_gen"><i class="fas fa-sync"></i></button>
                    <button class="btn btn-success" type="button" id="fginventory_btn_export" onclick="fginventory_btn_export_on_click()"><i class="fas fa-file-excel"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-center border-start">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-danger" type="button" id="fginventory_btn_trash"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <span id="fginventory_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="fginventory_divku">
                    <table id="fginventory_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle">Assy Number</th>
                                <th  class="align-middle">Model</th>
                                <th  class="align-middle">Lot</th>
                                <th  class="align-middle">Job Number</th>
                                <th  class="text-end">Qty</th>
                                <th  class="text-center">Reff. Number</th>
                                <th  class="text-center">PIC</th>
                                <th  class="text-center">Time</th>
                                <th  class="text-center">Location</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1 text-end">
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >TOTAL</span>
                    <input type="text" class="form-control" id="fginventory_txt_total" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function fginventory_btn_export_on_click(){
        window.open("http://192.168.0.29:8080/ems-glue/api/export/inventory-fg", '_blank');
    }
    $("#fginventory_divku").css('height', $(window).height()*72/100);
    $("#fginventory_btn_trash").click(function (e) {
        if(confirm("Are You sure ?")){
            let pw = prompt("PIN");
            $.ajax({
                type: "get",
                url: "<?=base_url('Inventory/clearFG')?>",
                data: {inpin: pw},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        alertify.message(response.status[0].msg);
                        $("#fginventory_tbl tbody").empty();
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                }, error : function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#fginventory_btn_gen").click(function (e) {
        fginventory_btn_gen.innerHTML = 'Please wait...'
        fginventory_btn_gen.disabled = true
        $.ajax({
            type: "get",
            url: "<?=base_url('Inventory/getlist')?>",
            dataType: "json",
            success: function (response) {
                fginventory_btn_gen.innerHTML = `<i class="fas fa-sync"></i>`
                fginventory_btn_gen.disabled = false
                let ttlrows = response.data.length;
                let mydes = document.getElementById("fginventory_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("fginventory_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("fginventory_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].CQTY).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.data[i].CASSYNO;

                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].CMODEL

                    newcell = newrow.insertCell(2);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].CLOTNO

                    newcell = newrow.insertCell(3);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].SER_DOC

                    newcell = newrow.insertCell(4);
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.innerHTML = numeral(response.data[i].CQTY).format('0,0')

                    newcell = newrow.insertCell(5);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].REFNO

                    newcell = newrow.insertCell(6);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].FULLNAME

                    newcell = newrow.insertCell(7);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].CDATE

                    newcell = newrow.insertCell(8);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].CLOC
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('fginventory_lblinfo').innerHTML = ttlrows>0 ? '': 'data not found';
                document.getElementById('fginventory_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                fginventory_btn_gen.disabled = false
                fginventory_btn_gen.innerHTML = `<i class="fas fa-sync"></i>`
                alertify.error(xthrow);
            }
        });
    });
</script>