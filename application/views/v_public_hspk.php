<div class="row block-hspk">
<div class="col-md-12">
<div class="table-responsive">
<table class="table table-condensed table-bordered">
<tbody>
<tr>
	<td class="w1px text-bold text-nowrap">Kode HSPK</td>
	<td class="w1px">:</td>
	<td class="text-bold"><?php echo $hspk_kode; ?></td>
</tr>
<tr>
	<td class="w1px text-bold text-nowrap">Program</td>
	<td class="w1px">:</td>
	<td class="hspk-nmprogram"></td>
</tr>
<tr>
	<td class="w1px text-bold text-nowrap">Nama Pekerjaan</td>
	<td class="w1px">:</td>
	<td class="hspk-nmpekerjaan"></td>
</tr>
<tr>
	<td class="w1px text-bold text-nowrap">Jenis Pekerjaan</td>
	<td class="w1px">:</td>
	<td class="hspk-jnpekerjaan"></td>
</tr>
<tr>
	<td class="w1px text-bold text-nowrap">Satuan</td>
	<td class="w1px">:</td>
	<td class="hspk-satuan"></td>
</tr>
</tbody>
</table>

<div class="table-responsive">
<table class="table table-condensed table-bordered">
<thead>
<tr>
	<td class="text-center text-bold">NO.</td>
	<td class="text-center text-bold">KOMPONEN</td>
	<td class="text-center text-bold">SATUAN</td>
	<td class="text-center text-bold">PERKIRAAN<BR>KUANTITAS</td>
	<td class="text-center text-bold">HARGA<br>SATUAN (Rp.)</td>
	<td class="text-center text-bold">JUMLAH<br>HARGA (Rp.)</td>
</tr>
</thead>
<tbody>
<tr class="active hspk-tenaga">
	<td class="text-center text-bold">A</td>
	<td class="text-bold" colspan="5"><u>TENAGA</u></td>
</tr>
<tr>
	<td></td>
	<td colspan="4" class="text-right text-bold">JUMLAH HARGA TENAGA</td>
	<td class="text-right text-bold nu2d hspk-total_tenaga"></td>
</tr>

<tr class="active hspk-bahan">
	<td class="text-center text-bold">B</td>
	<td class="text-bold" colspan="5"><u>BAHAN</u></td>
</tr>
<tr>
	<td></td>
	<td colspan="4" class="text-right text-bold">JUMLAH HARGA BAHAN</td>
	<td class="text-right text-bold nu2d hspk-total_bahan"></td>
</tr>

<tr class="active hspk-peralatan">
	<td class="text-center text-bold">C</td>
	<td class="text-bold" colspan="5"><u>PERALATAN</u></td>
</tr>
<tr>
	<td></td>
	<td colspan="4" class="text-right text-bold">JUMLAH HARGA PERALATAN</td>
	<td class="text-right text-bold nu2d hspk-total_peralatan"></td>
</tr>
<tr>
	<td class="text-center text-bold">D</td>
	<td colspan="4">JUMLAH HARGA TENAGA, BAHAN DAN PERALATAN ( A + B + C )</td>
	<td class="text-right nu2d hspk-total_tenaga_bahan_peralatan"></td>
</tr>
<tr>
	<td class="text-center text-bold">E</td>
	<td colspan="4">OVERHEAD & PROFIT ( <span class="hspk-overhead_percent"></span>% x D )</td>
	<td class="text-right nu2d hspk-overhead_profit"></td>
</tr>
<tr class="active">
	<td class="text-center text-bold">F</td>
	<td colspan="4">HARGA SATUAN PEKERJAAN ( D + E )</td>
	<td class="text-right text-bold nu2d hspk-harga_satuan_pekerjaan"></td>
</tr>

</tbody>
</table>
</div>
</div>
</div>

<script>
$(function() {
	$.ajax({
		type: 'GET',
		url: '<?php echo $api_detail; ?>',
		dataType: 'jsonp',
		crossDomain: true,
		success: function(res) {
			var detail = res.detail;
			var tenaga = detail.tenaga,
				bahan = detail.bahan,
				peralatan = detail.peralatan;
				
			$('.hspk-nmprogram').html(res.program);
			$('.hspk-nmpekerjaan').html(res.nmpeker);
			$('.hspk-jnpekerjaan').html(res.jspeker);
			$('.hspk-satuan').html(res.satuan);
			
			var htmltenaga = '',
				htmlbahan = '',
				htmlperalatan = '',
				no = 1;
			
			$.each(tenaga, function(k, v) {
				htmltenaga += ''+
				'<tr><td class="text-center">'+no+'.</td>'+
				'<td>'+v.komponen+'</td>'+
				'<td class="text-center">'+v.satuan+'</td>'+
				'<td class="text-right nu4d">'+v.perkiraan_kuantitas+'</td>'+
				'<td class="text-right nu2d">'+v.harga_satuan+'</td>'+
				'<td class="text-right nu2d">'+v.jumlah_harga+'</td></tr>';
				
				no++;
			});
			
			no = 1;
			$.each(bahan, function(k, v) {
				htmlbahan += ''+
				'<tr><td class="text-center">'+no+'.</td>'+
				'<td>'+v.komponen+'</td>'+
				'<td class="text-center">'+v.satuan+'</td>'+
				'<td class="text-right nu4d">'+v.perkiraan_kuantitas+'</td>'+
				'<td class="text-right nu2d">'+v.harga_satuan+'</td>'+
				'<td class="text-right nu2d">'+v.jumlah_harga+'</td></tr>';
				
				no++;
			});
			
			no = 1;
			$.each(peralatan, function(k, v) {
				htmlperalatan += ''+
				'<tr><td class="text-center">'+no+'.</td>'+
				'<td>'+v.komponen+'</td>'+
				'<td class="text-center">'+v.satuan+'</td>'+
				'<td class="text-right nu4d">'+v.perkiraan_kuantitas+'</td>'+
				'<td class="text-right nu2d">'+v.harga_satuan+'</td>'+
				'<td class="text-right nu2d">'+v.jumlah_harga+'</td></tr>';
				
				no++;
			});
			
			$('.hspk-tenaga').after(htmltenaga);
			$('.hspk-bahan').after(htmlbahan);
			$('.hspk-peralatan').after(htmlperalatan);
	
			$('.hspk-total_tenaga').html(res.total_tenaga);
			$('.hspk-total_bahan').html(res.total_bahan);
			$('.hspk-total_peralatan').html(res.total_peralatan);
			$('.hspk-total_tenaga_bahan_peralatan').html(res.total_tenaga_bahan_peralatan);
			$('.hspk-overhead_percent').html(res.overhead_persen);
			$('.hspk-overhead_profit').html(res.overhead_profit);
			$('.hspk-harga_satuan_pekerjaan').html(res.harga_satuan_pekerjaan);
			
			updateNum('.block-hspk');
		}
	});
});
</script>
