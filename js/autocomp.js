//autocomplete script
		$(document).on('focus','.autocomplete_txt',function(){
			type = $(this).data('type');		
			tipe = type.split("_");
			console.log(tipe);
			if(tipe[0] =='kdbrg' )autoTypeNo=0;
			if(tipe[0] =='nmbrg' )autoTypeNo=1; 	
			$(this).autocomplete({
				source: function( request, response ) {
					$.ajax({
						url : 'nmbrg.php',
						dataType: "json",
						method: 'post',
						data: {
						   name_startsWith: request.term,
						   type: tipe[0],
						   field: tipe[1] 
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
					$('#kdbrg').val(names[0]);
					$('#nmbrg').val(names[1]);
					$('#qty').val(1);
					$('#hrg').val(names[2]);
					$('#total').val( 1*names[2] );
					//calculateTotal();
				}		      	
			});
		});

