<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ $title }}</title>
<style>
body
{
   font-family: Arial, Helvetica, sans-serif;
   font-size: 11px;
}
 
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

thead
{
	border: 1px solid #ddd;
}
table th, #table td {
  text-align: left;
  padding: 8px;
}

table tr:nth-child(even) {
  background-color: #f2f2f2;
}

#company_name
{
	font-size : 14px;
	font-weight : bold;
}

#company_address
{
	font-size : 14px;
	font-weight : normal;
}

.heading
{
	font-size : 12px;
	font-weight : bold;
}

.divider
{
	border-top:2px solid #ddd;
}

</style>

</head>
<body>

	<div  style='clear:both;width:100%'> 
		<div><center><span id='company_address'><b>{{$title}}</b></span></center></div>
	</div>  
	
	<br>
	<br>
	
	<table width="100%" style="width:100%" border="0" id='table'>
		{!! $table_data !!}
    </table>


</body>
</html>