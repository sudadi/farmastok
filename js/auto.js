	   //autocomplete script
		$(document).on('focus','.autocomplete_txt',function(){
			type = $(this).data('type');		
			tipe = type.split("_");
			console.log(tipe);
			if(tipe[0] == 'kd' ) autoTypeNo=0;
			if(tipe[0] == 'nm' ) autoTypeNo=1; 
			if(tipe[1] == 'supp') { 
                            ajaxurl='carisupp.php'; 
                        }else if(tipe[1] == 'obat') { 
                            ajaxurl='cariobat.php'; 
                        }else{
                            ajaxurl='carisat.php';
                        }
			$(this).autocomplete({
				source: function( request, response ) {
					$.ajax({
						url : ajaxurl,
						dataType: "json",
						method: 'post',
						data: {
						   name_startsWith: request.term,
						   field: tipe[0] 
						},
						success: function( data ) {
							 response( $.map( data, function( item ) {
								var code = item.split("|");
								return {
									label: code[0]+" | "+code[1],
									value: code[autoTypeNo],
									data : item
								}
							}));
							//console.log(type);
						}
					});
				},
				autoFocus: true,	      	
				minLength: 1,
				select: function( event, ui ) {
					var names = ui.item.data.split("|");
					if( tipe[1] == 'supp' ) {
						$('#kdsupp').val(names[0]);
						$('#nmsupp').val(names[1]);
						$('#almsupp').val(names[2]);
					} else if ( tipe[1] == 'obat' ){
						$('#kdobat').val(names[0]);
						$('#nmobat').val(names[1]);
					} else {
                                            $('#kdsat').val(names[0]);
                                            $('#nmsat').val(names[1]);
                                        }
				}
			});
		});

