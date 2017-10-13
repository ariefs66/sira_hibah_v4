/* Your Custom JS for BDG Webkit = Script here */

/* Function Keyup */
function SetNumber(id){

	var numbers = $("#"+id).val();

	if (numbers != ""){
		var number_string = numeral().unformat(numbers);

		var number = numeral(number_string);

		var set_number = number.format('0,0');

		$("#"+id).val(set_number);
	}

}


$(window).load(function(){

	$('input.table-search').keyup( function () {
		$('.table').DataTable().search($('.table-search').val()).draw();
	});
	
	$("select.dtSelect").on('click',function() {
		$('.table').DataTable().page.len($('.dtSelect').val()).draw();
	});

	function show_inputbar(){
		
		$('.open-rincian').on('click',function(){
			$('.overlay').fadeIn('fast',function(){
				$('.input-rincian').animate({'right':'0'},"linear");	
				$("html, body").animate({ scrollTop: 0 }, "slow");
			});	
		});	
		$('.open-tahapan').on('click',function(){
			$('.overlay').fadeIn('fast',function(){
				$('.input-tahapan').animate({'right':'0'},"linear");	
				$("html, body").animate({ scrollTop: 0 }, "slow");
			});	
		});	
		$('.open-form-btl').on('click',function(){
			$('.overlay').fadeIn('fast',function(){
				$('.input-btl').animate({'right':'0'},"linear");	
				$("html, body").animate({ scrollTop: 0 }, "slow");
			});	
		});	
		$('.open-form-pendapatan').on('click',function(){
			$('.overlay').fadeIn('fast',function(){
				$('.input-pendapatan').animate({'right':'0'},"linear");	
				$("html, body").animate({ scrollTop: 0 }, "slow");
			});	
		});	
		$('.open-form-pembiayaan').on('click',function(){
			$('.overlay').fadeIn('fast',function(){
				$('.input-pembiayaan').animate({'right':'0'},"linear");	
				$("html, body").animate({ scrollTop: 0 }, "slow");
			});	
		});	
		$('.open-staff').on('click',function(){
					$('.overlay').fadeIn('fast',function(){
						$('.input-staff').animate({'right':'0'},"linear");	
						$("html, body").animate({ scrollTop: 0 }, "slow");
					});	
				});	

		$('.open-dd').on('click',function(){
					$('.overlay').fadeIn('fast',function(){
						$('.input-dd').animate({'right':'0'},"linear");	
						$("html, body").animate({ scrollTop: 0 }, "slow");
					});	
				});
		$('.open-ubah-komponen').on('click',function(){
					$('.overlay').fadeIn('fast',function(){
						$('.input-ubah-komponen').animate({'right':'0'},"linear");	
						$("html, body").animate({ scrollTop: 0 }, "slow");
					});	
				});
		$('.open-tambah-rekening').on('click',function(){
							$('.overlay').fadeIn('fast',function(){
								$('.input-tambah-rekening').animate({'right':'0'},"linear");	
								$("html, body").animate({ scrollTop: 0 }, "slow");
							});	
						});


		// $('.open-rincian').on('click',function(){
		// 	$('.overlay').fadeIn('fast',function(){
		// 		$('.input-rincian').animate({'right':'0'},"linear");					
		// 	});			
		// });		
		// $('.open-staff').on('click',function(){
		// 	$('.overlay').fadeIn('fast',function(){
		// 		$('.input-staff').animate({'right':'0'},"linear");					
		// 	});			
		// });		

		$('.overlay').on('click',function(){			
			$('.input-sidebar').animate({'right':'-1050px'},"linear",function(){
				$('.overlay').fadeOut('fast');
			});	
		});	

		$('a.tutup-form').click(function(){
			$('.input-spp,.input-spp-langsung,.input-sidebar').animate({'right':'-1050px'},function(){
				$('.overlay').fadeOut('fast');
			});
			
		});	

	};
	show_inputbar();

	//Show kegiatan
	$('.btn-kegiatan,.aktifitas-total').on('click',function(){
			$('#kegiatan-popup').modal();
	});

	//show capaian kerja
	$('.btn-capaian-kerja').on('click',function(){
			$('#kegiatan-popup').modal();
	});
	

	//Tambah kegiatan
	$('.add_kegiatan').on('click',function(){
		$('#add_kegiatan').animate({'right':'0px'},'fast','linear');
	});

	//Back to program list
	$('.back_kegiatan_list').on('click',function(){
		$('#add_kegiatan').animate({'right':'-1010px'},'slow','linear');
	});

	//close modal
	$('.close_modal').on('click',function(){
		$('#kegiatan-popup,#aktivasi-popup').modal('hide');
		
	});

	//Aktivasi
	$('.aktivasi input').on('change',function(){
		if($(this).attr('checked') == 'checked'){
			$(this).attr('checked',false);

			$(this).parent().parent().parent().find('td').css({'color':'#b8bcce'});
		}else{
			$(this).attr('checked','checked');
			$(this).parent().parent().parent().find('td').css({'color':'#555b70'});
		}		
	});

	//Edit Kegiatan
	$('.edit_kegiatan').on('click',function(){		
			$('.th_search').attr('colspan','6');
			$('.aktivasi').fadeIn('fast');			
			$(this).fadeOut('fast',function(){
				$('.save_wrapper').fadeIn('fast');	
			});
	});

	//Save kegiatan
	$('.save_kegiatan').on('click',function(){
			$('.th_search').attr('colspan','5');
			$('.aktivasi').fadeOut('fast');			
			$('.save_wrapper').fadeOut('fast',function(){
				$('.edit_kegiatan').fadeIn('fast');	
			});
	});

	//Select All
	$('.select_all').on('click',function(){
			
			if($(this).attr('data-status') == 'active') {
				$('.aktivasi input').each(function(){
					$(this).trigger('click');							
				});	
				$(this).attr('data-status','inactive');
			}else{
				$('.aktivasi input').each(function(){
					$(this).attr('checked','checked');							
				});	
			}
			$('.table-aktivasi tbody tr td').css({'color':'#555b70'});

			
			
	});

	$('.clear_all').on('click',function(){
			
			$('.aktivasi input').each(function(){
				$(this).attr('checked',false);			
			});
			$('.table-aktivasi tbody tr td').css({'color':'#b8bcce'});
			$('.select_all').attr('data-status','active');
	});
	

	

    $('.table-usulan').on('click', '.table-usulan > tbody > tr ', function () {
		id = $(this).children("td").eq(0).html();
		$('#button'+id).removeClass('hide');
    });

    

    $('.table-pembiayaan').on('click', '.table-pembiayaan > tbody > tr ', function () {
		if($("tr").hasClass('pembiayaan-rincian') == false){
			skpd = $(this).children("td").eq(0).html();
		}
		if(!$(this).hasClass('pembiayaan-rincian')){
			if($(this).hasClass('shown')){			
				$('.pembiayaan-rincian').slideUp('fast').remove();	
				$(this).removeClass('shown');	
			}else{
				$('.pembiayaan-rincian').slideUp('fast').remove();	
				$(this).addClass('shown');
				btl_detail = '<tr class="pembiayaan-rincian"><td style="padding:0!important;" colspan="3">'+$('#table-detail-pembiayaan').html()+'</td></tr>';
				$(btl_detail).insertAfter('.table-pembiayaan .table tbody tr.shown');
				$('.table-detail-pembiayaan-isi').DataTable({
					sAjaxSource: "/main/2018/murni/pembiayaan/getDetail/"+skpd,
					aoColumns: [
					{ mData: 'NO' },
					{ mData: 'REKENING' },
					{ mData: 'RINCIAN' },
					{ mData: 'TOTAL' },
					{ mData: 'AKSI' }
					]
				});
			}
		}
    });

	$('.table-program').on('click', '.table-program-head > tbody > tr > td:nth-child(3) ', function () {
		
		if($("tr").hasClass('program_rincian') == false){
			idprogram = $(this).parent().children("td").eq(0).html();
		}
		if(!$(this).parent().hasClass('program_rincian')){
			if($(this).parent().hasClass('shown')){			
				$('.program_rincian').slideUp('fast').remove();	
				$(this).parent().removeClass('shown');	
				$('.mi-caret-up',this).addClass('mi-caret-down').css({'color':'#b5bbc2'}).removeClass('mi-caret-up');
				$('.table-detail-program-isi').DataTable().destroy();				
			}else{
				$(this).parent().addClass('shown');		
				var data_detail = '<tr class="program_rincian table-detail-1"><td style="background-color: #ffffff !important;padding: 0 0 0 !important;" colspan="4">' + $('#table-detail-program').html() + '</td></tr>';
				$(data_detail).insertAfter('.table-program tbody tr.shown');
				$('.mi-caret-down',this).addClass('mi-caret-up').css({'color':'#00b0ef'}).removeClass('mi-caret-down');
				$('.table-detail-program-isi').DataTable({
					sAjaxSource: "/main/2018/murni/pengaturan/program/getDataDetail/"+idprogram,
					aoColumns: [
					{ mData: 'KEGIATAN_ID',class: 'hide' },
					{ mData: 'KEGIATAN_KODE' },
					{ mData: 'KEGIATAN_NAMA' },
					{ mData: 'AKSI', class: 'table20' }
					]
				});
			}
		}
    });

	$('.table-program-adum').on('click', '.table-program-head-adum > tbody > tr > td:nth-child(2) ', function () {
		
		if($("tr").hasClass('program_rincian') == false){
			idprogram = $(this).parent().children("td").eq(0).html();
		}
		if(!$(this).parent().hasClass('program_rincian')){
			if($(this).parent().hasClass('shown')){			
				$('.program_rincian').slideUp('fast').remove();	
				$(this).parent().removeClass('shown');	
				$('.mi-caret-up',this).addClass('mi-caret-down').css({'color':'#b5bbc2'}).removeClass('mi-caret-up');
				$('.table-detail-program-isi').DataTable().destroy();				
			}else{
				$(this).parent().addClass('shown');		
				var data_detail = '<tr class="program_rincian table-detail-1"><td style="background-color: #ffffff !important;padding: 0 0 0 !important;" colspan="4">' + $('#table-detail-program').html() + '</td></tr>';
				$(data_detail).insertAfter('.table-program-adum tbody tr.shown');
				$('.mi-caret-down',this).addClass('mi-caret-up').css({'color':'#00b0ef'}).removeClass('mi-caret-down');
				$('.table-detail-program-isi').DataTable({
					sAjaxSource: "/main/2018/murni/pengaturan/adum/program/getDataDetail/"+idprogram,
					aoColumns: [
					{ mData: 'KEGIATAN_KODE' },
					{ mData: 'KEGIATAN_NAMA' },
					{ mData: 'AKSI', class: 'table20' }
					]
				});
			}
		}
    });
});