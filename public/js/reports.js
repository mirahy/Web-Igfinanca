LANCAMENTO_PENDETE   = 0;
LANCAMENTO_APROVADO  = 1;
LANCAMENTO_REPROVADO = 2;

CONSTANT_MES = [
                'MES', 
                'JANEIRO', 
                'FEVEREIRO', 
                'MARÇO', 
                'ABRIL',
                'MAIO',
                'JUNHO',
                'JULHO',
                'AGOSTO',
                'SETEMBRO',
                'OUTUBRO',
                'NOVEMBRO',
                'DEZENBRO'
                ];

OPERATION_OUTPUT = 0;
OPERATION_INPUT  = 1;

$(function(){

        /** 
         * tabela fechamentos
        **/
        var dt_report_closing = $('#dt_report_closing').DataTable({
                                "oLanguage": DATATABLE_PTBR,
                                "autoWidth":  false,
                                "processing": true,
                                // "serverSide": true,
                                "ajax": baseUrl + 'query?status=1',
                                "columns": [
                                    { data: 'type_launch.name', name: 'launch.name' },
                                    { data: 'user.name', name: 'user.name' },
                                    { data: 'value', name: 'value',
                                    render: $.fn.dataTable.render.number( '.', ',', 2, 'R$' )
                                    },
                                    { data: 'caixa.name', name: 'caixa.name' },
                                    { data: 'operation_date', 
                                        render: function ( data, type, row ) {
                                            return type === "display" || type === "filter" ? Dataformat = FormatData(data):
                                            data;
                                        }
                                    },
                                    
                                ],
                                // "drawCallback": function(){
                                //     btn_aprov();
                                // },

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