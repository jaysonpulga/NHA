<!--
<footer class="main-footer">
<center>
<strong>Copyright &copy; </strong> All rights reserved.
</center>
</footer>
-->


<script>
$('#city_show').change(function() {
	var value =  $(this).val(); 	

	
	if( value == ""){
		
		$('select[id="barangay_show"]').attr('disabled', 'disabled');
		$('#barangay_show').val("");
		
	}
	else
	{
		
		$('select[id="barangay_show"]').removeAttr("disabled");
		
	
		
		$.ajax({
			url: "{{ asset('getbarangaybelongtoCity') }}",
			data : {'city':value},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = '<option value="">All Barangay</option>';
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.id+"\">"+value.name+"</option>";
						
				});

				
				$('#barangay_show').empty().html(selectValue);
							
			}	
		});
		
	}
	
});
</script>