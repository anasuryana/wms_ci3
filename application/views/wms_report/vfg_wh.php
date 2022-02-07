<style>
    .mapael .map {
        position: relative;
        background-color:#cddee0;
        margin-bottom:10px;
    }

    .mapael .mapTooltip {
        position : absolute;
        background-color : #474c4b;
        -moz-opacity:0.70;
        opacity: 0.70;
        filter:alpha(opacity=70);
        border-radius:10px;
        padding : 10px;
        z-index: 1000;
        max-width: 200px;
        display:none;
        color:#fff;
    }

    /* For all zoom buttons */
    .mapael .zoomButton {
        background-color: #fff;
        border: 1px solid #ccc;
        color: #000;
        width: 15px;
        height: 15px;
        line-height: 15px;
        text-align: center;
        border-radius: 3px;
        cursor: pointer;
        position: absolute;
        top: 0;
        font-weight: bold;
        left: 10px;

        -webkit-user-select: none;
        -khtml-user-select : none;
        -moz-user-select: none;
        -o-user-select : none;
        user-select: none;
    }

    /* Reset Zoom button first */
    .mapael .zoomReset {
        top: 10px;
    }

    /* Then Zoom In button */
    .mapael .zoomIn {
        top: 30px;
    }

    /* Then Zoom Out button */
    .mapael .zoomOut {
        top: 50px;
    }
</style>
<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Item Code</label>                    
                    <input type="text" class="form-control" id="vfgwh_itemcd">                    
                    <button title="Search" class="btn btn-primary" id="vfgwh_btn_gen"> <i class="fas fa-search"></i> </button>                    
                </div>
            </div>            
        </div>             
    
        <div class="row">                        
            <div class="col-md-12 mb-1 text-end">
                <span id="vfgwh_status" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-0">
                <div class="mapcontainer">
                    <div class="map">
                        <span>Alternative content for the map</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    function vfgwh_init_map(){
        $(".mapcontainer").mapael({
            map: {
                // Set the name of the map to display
                name: "pt_smt_fg_map",
                zoom: {
                    enabled: true,
                    maxLevel: 20
                }, defaultArea: {
                    attrs: {
                        fill: "#f4f4e8"
                        , stroke: "#ced8d0",
                        "stroke-width":0.1,
                    }
                    , attrsHover: {
                        fill: "#a4e100"
                    }
                }
            }
            // , areas: {
            //         "A2" : {                    
            //             attrs : {                    
            //                 fill: "#a4e100"
            //             }                    
            //         },
            //         "A1" : {                    
            //             attrs : {                    
            //                 fill: "#a4e100"
            //             }                    
            //         }
            //     }
        });
    }
    vfgwh_init_map();
    function vfgwh_get_data(){
        let mitem = document.getElementById('vfgwh_itemcd').value;
        document.getElementById('vfgwh_status').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/get_vis_fg_location')?>",
            data: {initem: mitem},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    let updatedOptions = {'areas': {}, 'plots': {}};
                    let ttlrows = response.data.length;
                    document.getElementById('vfgwh_status').innerHTML = ttlrows + ' data found';
                    for(let i=0;i<ttlrows;i++){
                        updatedOptions.areas[response.data[i].ITH_LOC] = {
                            tooltip: {
                                content: '['+response.data[i].ITH_LOC+'] ' + response.data[i].SER_ITMID + ' Total Box : ' + response.data[i].TTLLBL
                            },
                            attrs : {                    
                                fill: "#2A7FFF"
                            },
                            text: {content: "===="}
                        };
                    }
                    $(".mapcontainer").trigger('update', [{
                        mapOptions: updatedOptions, 
                        animDuration: 1000
                    }]);
                    // alertify.message('updated');
                } else {
                    alertify.message(response.status[0].msg);
                    document.getElementById('vfgwh_status').innerHTML = '';
                }
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow);
                document.getElementById('vfgwh_status').innerHTML = '';
            }
        });
    }
    
    $("#vfgwh_btn_gen").click(function (e) { 
        vfgwh_init_map();
        vfgwh_get_data();
    });

    $("#vfgwh_itemcd").keypress(function (e) { 
        if(e.which==13){
            vfgwh_init_map();
            vfgwh_get_data();
        }
    });
</script>