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
        <div class="row" id="rrcvlist_ics_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">From</span>
                    <input type="text" class="form-control" id="rrcvlist_ics_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">To</span>
                    <input type="text" class="form-control" id="rrcvlist_ics_txt_dt2" readonly>
                </div>
            </div>
        </div>

        <div class="row" id="rrcvlist_ics_stack2">
            <div class="col-md-7 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Search by</span>
                    <select class="form-select" id="rrcvlist_ics_seachby" onchange="document.getElementById('rrcvlist_ics_txt_search').focus()">
                        <option value="0">Item Code</option>
                        <option value="1">Item Description</option>
                        <option value="2">Supplier</option>
                    </select>
                    <input type="text" class="form-control" id="rrcvlist_ics_txt_search">
                </div>
            </div>

            <div class="col-md-3 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rrcvlist_ics_btn_gen" onclick="rrcvlist_ics_btn_gen_eClick(this)">Search</button>                    
                </div>
            </div>
            <div class="col-md-2 mb-1 text-end">
                <span id="rrcvlist_ics_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rrcvlist_ics_divku">
                    <table id="rrcvlist_ics_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light text-center align-middle">
                            <tr class="first">
                                <th>Trans No.</th>
                                <th>Trans Date</th>
                                <th>Location to</th>
                                <th>Vendor</th>
                                <th>DN Vendor No.</th>
                                <th>PO. No.</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Item Group</th>
                                <th>Item Type</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Currency</th>
                                <th>Price</th>
                                <th>Amount</th>
                                <th>(custom_no)</th>
                                <th>(nopen)</th>
                                <th>(custom_doc)</th>
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
    $("#rrcvlist_ics_divku").css('height', $(window).height() -
        document.getElementById('rrcvlist_ics_stack1').offsetHeight -
        document.getElementById('rrcvlist_ics_stack2').offsetHeight -
        100)
    $("#rrcvlist_ics_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        endDate: '2021-08-09'
    });
    $("#rrcvlist_ics_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        endDate: '2021-08-09'
    });
    $("#rrcvlist_ics_txt_dt").datepicker('update', '2019-11-01');
    $("#rrcvlist_ics_txt_dt2").datepicker('update', '2021-08-09');

    function rrcvlist_ics_btn_gen_eClick(p) {
        p.disabled = true
        const searchby = document.getElementById('rrcvlist_ics_seachby').value
        const search = document.getElementById('rrcvlist_ics_txt_search').value
        const date0 = document.getElementById('rrcvlist_ics_txt_dt').value
        const date1 = document.getElementById('rrcvlist_ics_txt_dt2').value
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>"+"ics/receive",
            data: {
                searchBy: searchby,
                searchValue: search,
                date0: date0,
                date1: date1
            },
            dataType: "json",
            success: function(response) {
                p.disabled = false
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rrcvlist_ics_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rrcvlist_ics_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rrcvlist_ics_tbl")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell
                tableku2.innerHTML = '';
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.style.cssText = 'white-space: nowrap'
                    newcell.innerHTML = response.data[i].trans_no
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].trans_date
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].location_to
                    newcell = newrow.insertCell(3)
                    newcell.style.cssText = 'white-space: nowrap'
                    newcell.innerHTML = response.data[i].vendor_code
                    newcell = newrow.insertCell(4)
                    newcell.style.cssText = 'white-space: nowrap'
                    newcell.innerHTML = response.data[i].delivery_no
                    newcell = newrow.insertCell(5)
                    newcell.innerHTML = response.data[i].po_no
                    newcell = newrow.insertCell(6)
                    newcell.style.cssText = 'white-space: nowrap'
                    newcell.innerHTML = response.data[i].item_code
                    newcell = newrow.insertCell(7)
                    newcell.innerHTML = response.data[i].item_name
                    newcell = newrow.insertCell(8)
                    newcell.innerHTML = response.data[i].item_group_code
                    newcell = newrow.insertCell(9)
                    newcell.innerHTML = response.data[i].item_type_code
                    newcell = newrow.insertCell(10)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].rcv_qty
                    newcell = newrow.insertCell(11)
                    newcell.innerHTML = response.data[i].unit_code
                    newcell = newrow.insertCell(12)
                    newcell.innerHTML = response.data[i].curr_code
                    newcell = newrow.insertCell(13)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].net_price
                    newcell = newrow.insertCell(14)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].amount).format(',')
                    newcell = newrow.insertCell(15)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].custom_no
                    newcell = newrow.insertCell(16)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].nopen
                    newcell = newrow.insertCell(17)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].custom_doc
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

</script>