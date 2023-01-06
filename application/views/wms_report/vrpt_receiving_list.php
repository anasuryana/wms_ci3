<style>
    thead tr.first th,
    thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th,
    thead tr.second td {
        position: sticky;
        top: 25px;
    }
</style>

<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="rrcvlist_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">From</span>
                    <input type="text" class="form-control" id="rrcvlist_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">To</span>
                    <input type="text" class="form-control" id="rrcvlist_txt_dt2" readonly>
                </div>
            </div>
        </div>

        <div class="row" id="rrcvlist_stack2">
            <div class="col-md-7 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Search by</span>
                    <select class="form-select" id="rrcvlist_seachby" onchange="document.getElementById('rrcvlist_txt_search').focus()">
                        <option value="id">Item Code</option>
                        <option value="name">Item Description</option>
                        <option value="supname">Supplier</option>
                    </select>
                    <input type="text" class="form-control" id="rrcvlist_txt_search">
                </div>
            </div>

            <div class="col-md-3 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rrcvlist_btn_gen" onclick="rrcvlist_btn_gen_eClick(this)">Search</button>
                    <div class="btn-group btn-group-sm" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export to
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="#" id="rrcvlist_btn_xls" onclick="rrcvlist_btn_xls_eClick(this)"><span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> XLS</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-1 text-end">
                <span id="rrcvlist_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rrcvlist_divku">
                    <table id="rrcvlist_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th class="align-middle text-center">Type BC</th>
                                <th class="align-middle">No Aju</th>
                                <th class="align-middle">No BC</th>
                                <th class="align-middle">PPN</th>
                                <th class="align-middle">Tgl BC</th>
                                <th class="align-middle">Subject</th>
                                <th class="align-middle">Department</th>
                                <th class="align-middle text-center">Receiving No.</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">PO NO.</th>
                                <th>Vendor Code</th>
                                <th>Vendor Name</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th class="text-end">Qty Rcv</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Currency</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#rrcvlist_divku").css('height', $(window).height() -
        document.getElementById('rrcvlist_stack1').offsetHeight -
        document.getElementById('rrcvlist_stack2').offsetHeight -
        100)
    $("#rrcvlist_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $("#rrcvlist_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $("#rrcvlist_txt_dt").datepicker('update', new Date());
    $("#rrcvlist_txt_dt2").datepicker('update', new Date());

    function rrcvlist_btn_gen_eClick(p) {
        p.disabled = true
        const searchby = document.getElementById('rrcvlist_seachby').value
        const search = document.getElementById('rrcvlist_txt_search').value
        const date0 = document.getElementById('rrcvlist_txt_dt').value
        const date1 = document.getElementById('rrcvlist_txt_dt2').value
        $.ajax({
            type: "GET",
            url: "<?= base_url('RCVHistory/receiving') ?>",
            data: {
                searchby: searchby,
                search: search,
                date0: date0,
                date1: date1
            },
            dataType: "json",
            success: function(response) {
                p.disabled = false
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rrcvlist_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rrcvlist_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rrcvlist_tbl")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell
                tableku2.innerHTML = '';
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].RCV_BCTYPE
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].RCV_RPNO
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].RCV_BCNO
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].PO_VAT
                    newcell.classList.add('text-end')
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data[i].RCV_RPDATE
                    newcell = newrow.insertCell(5)
                    newcell.innerHTML = response.data[i].POSUBJECT
                    newcell = newrow.insertCell(6)
                    newcell.innerHTML = response.data[i].PODEPT
                    newcell = newrow.insertCell(7)
                    newcell.innerHTML = response.data[i].RECEIVINGNO
                    newcell = newrow.insertCell(8)
                    newcell.innerHTML = response.data[i].RCV_RCVDATE
                    newcell = newrow.insertCell(9)
                    newcell.innerHTML = response.data[i].RCV_PO
                    newcell = newrow.insertCell(10)
                    newcell.innerHTML = response.data[i].RCV_SUPCD
                    newcell = newrow.insertCell(11)
                    newcell.innerHTML = response.data[i].SUPNM
                    newcell = newrow.insertCell(12)
                    newcell.innerHTML = response.data[i].ITEMCODE
                    newcell = newrow.insertCell(13)
                    newcell.innerHTML = response.data[i].ITEMNAME
                    newcell = newrow.insertCell(14)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].RQT).format(',')
                    newcell = newrow.insertCell(15)
                    newcell.innerHTML = response.data[i].UOM
                    newcell = newrow.insertCell(16)
                    newcell.innerHTML = response.data[i].MSUP_SUPCR
                    newcell = newrow.insertCell(17)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].RCV_PRPRC).format(',')
                    newcell = newrow.insertCell(18)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].AMOUNT).format(',')
                }
                mydes.innerHTML = '';
                mydes.appendChild(myfrag);
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                p.disabled = false
            }
        })
    }

    function rrcvlist_btn_xls_eClick(p) {
        const infoDiv = document.getElementById('rrcvlist_lblinfo')
        infoDiv.innerHTML = 'Please wait . . .'
        p.classList.add('disabled')
        const searchby = document.getElementById('rrcvlist_seachby').value
        const search = document.getElementById('rrcvlist_txt_search').value
        const date0 = document.getElementById('rrcvlist_txt_dt').value
        const date1 = document.getElementById('rrcvlist_txt_dt2').value
        $.ajax({
            url: "<?= base_url('RCVHistory/receivingAsXLS') ?>",
            data: {
                searchby: searchby,
                search: search,
                date0: date0,
                date1: date1
            },
            success: function(response) {
                const blob = new Blob([response], {
                    type: "application/vnd.ms-excel"
                })
                const fileName = `Receiving List Report ${date0} - ${date1}.xlsx`
                saveAs(blob, fileName)
                p.classList.remove('disabled')
                alertify.success('Done')
                infoDiv.innerHTML = ''
            },
            xhr: function() {
                const xhr = new XMLHttpRequest()
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 2) {
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        } else {
                            p.classList.remove('disabled')
                            infoDiv.innerHTML = ''
                            xhr.responseType = "text";
                        }
                    }
                }
                return xhr
            },
        })
    }
</script>