$(function(){

        /** 
         * tabela de logs
        **/
        var dt_report_closing = $('#dt_logs').DataTable({
                                "oLanguage": DATATABLE_PTBR,
                                "autoWidth":  false,
                                "processing": true,
                                // "serverSide": true,
                                "ajax": baseUrl + 'query-log',
                                "order": [],
                                "columns": [
                                    { data: 'log_name', name: 'log_name' },
                                    { data: 'description', name: 'description' },
                                    { data: 'subject_id', name: 'subject_id'},
                                    { data: 'properties', name: 'properties'},
                                    { data: 'name', name: 'name'},
                                    { data: 'created_at', 
                                        render: function ( data, type, row ) {
                                            return type === "display" || type === "filter" ? Dataformat = FormatDataGMT(data):
                                            data;
                                        }
                                    },
                             
                                ],
                               
                                "footerCallback": function ( row, data, start, end, display ) {
                                    var api = this.api(), data;

                                    // Remove the formatting to get integer data for summation
                                    var intVal = function ( i ) {
                                        return typeof i === 'string' ?
                                            i.replace(/[\$,]/g, '')*1 :
                                            typeof i === 'number' ?
                                                i : 0;
                                    };

                                    // Total over all pages
                                    total = api
                                        .column( 2 )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        }, 0 );

                                    // Total over this page
                                    pageTotal = api
                                        .column( 2, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        }, 0 );

                                    // Update footer
                                    $( api.column( 4 ).footer() ).html(
                                        'R$'+pageTotal +' ( R$'+ total +' total)'
                                    );

                                    
                                }
            });


});