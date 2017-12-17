<?php 
if(isset($_POST['method'])){
	switch ($_POST['method']) {
		case 'getJson':
			get();
			break;
		case 'setJson':
			save(json_decode($_POST['data']));
			break;
		default:
			echo 'NAH NAH NAH AH';
			break;
	}
	die();	
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
body{
	margin:0;
}

input[type="text"] {
	font-family: "Courier New", Courier, monospace;
	outline: none;
	padding: 10px;
	border: none;
	border-bottom: solid 2px #c9c9c9;
	transition: border 0.3s;
	box-sizing:content-box;
}	
input[type="text"]:focus,
input[type="text"].focus {
	outline: none;
	border-bottom: solid 2px #969696;
}

.button {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  overflow: hidden;
  margin: 10px;
  padding: 12px 12px;
  cursor: pointer;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  -webkit-transition: all 60ms ease-in-out;
  transition: all 60ms ease-in-out;
  text-align: center;
  white-space: nowrap;
  text-decoration: none !important;
  text-transform: none;
  text-transform: capitalize;
  color: #fff;
  border: 0 none;
  border-radius: 4px;
  font-size: 13px;
  font-weight: 500;
  line-height: 1.3;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  -webkit-box-pack: center;
      -ms-flex-pack: center;
          justify-content: center;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  -webkit-box-flex: 0;
      -ms-flex: 0 0 160px;
          flex: 0 0 160px;
  box-shadow: 2px 5px 10px rgba(22, 22, 22, 0.1);
}
.button:hover {
  -webkit-transition: all 60ms ease;
  transition: all 60ms ease;
  opacity: .85;
}
.button:active {
  -webkit-transition: all 60ms ease;
  transition: all 60ms ease;
  opacity: .75;
}
.button:focus {
  outline: 1px dotted #959595;
  outline-offset: -4px;
}

.button.-regular {
  color: #202129;
  background-color: #f2f2f2;
}
.button.-regular:hover {
  color: #202129;
  background-color: #e1e2e2;
  opacity: 1;
}
.button.-regular:active {
  background-color: #d5d6d6;
  opacity: 1;
}

.button.-dark {
  color: #FFFFFF;
  background: #161616;
}
.button.-dark:focus {
  outline: 1px dotted white;
  outline-offset: -4px;
}

.button.-green {
  color: #FFFFFF;
  background: #3dd28d;
}

.button.-blue {
  color: #FFFFFF;
  background: #416dea;
}

.button.-salmon {
  color: #FFFFFF;
  background: #F32C52;
}

.button.-sun {
  color: #f15c5c;
  background: #feee7d;
}

.button.-alge {
  color: #e7ff20;
  background: #7999a9;
}

.button.-flower {
  color: #FE8CDF;
  background: #353866;
}
.row{
	width:100%;
}
.row>div{
	display: inline-block;
	padding: 0 10px;
}

.row-deletable:nth-child(odd), .row-deletable:nth-child(odd) input{
  background-color: #ececec;
}

.table{
	margin-bottom: 100px;
}

#deletables{
	/*margin-top: 107px;*/
}

.row-adder {
  /*position: fixed;*/
  /*top: 0;*/
  /*left: 0;*/
  background-color: #6b6b6b;
  padding-top:10px;
  
}

.row-adder input{
  background-color: #ffffff;
  font-weight: bold;
}


#save {
	position:fixed;
	right: 20px;
	bottom: 20px;
}
/*.row:nth-child(even) {
    background-color: #000000;
}*/
	</style>
	<script type="text/javascript" src="../js/mustache.min.js"></script>
</head>
<body>
	<div class="table">
		<div class="table-content">
			<div class="table-body">
				<div class="row row-adder">
					<div><input type="text" data-type="plato" placeholder="plato"></div>
					<div><input type="text" data-type="comentario" placeholder="comentario"></div>
					<div><input type="text" data-type="img" placeholder="img"></div>
					<div><input type="text" data-type="cocinero" placeholder="cocinero"></div>
					<div><input type="text" data-type="precio" placeholder="precio"></div>
					<div><input type="text" data-type="direccion" placeholder="direccion"></div>
					<div><input type="text" data-type="mail" placeholder="mail"></div>
					<div><input type="text" data-type="horario" placeholder="horario"></div>
					<div><input type="text" data-type="lat" placeholder="lat"></div>
					<div><input type="text" data-type="lng" placeholder="lng"></div>
					<div><button class="add-row button -blue center">AGREGAR</button></div>
				</div>
				<div id="deletables"></div>
			</div>
			<div class="table-footer">
				<button id="save" class="button -green center">GUARDAR</button>
			</div>
		</div>		
	</div>
	
	<script id="table-template" type="x-tmpl-mustache">
		<div class="row row-deletable">
			<div><input type="text" data-type="plato" value="{{plato}}" placeholder="plato"></div>
			<div><input type="text" data-type="comentario" value="{{comentario}}" placeholder="comentario"></div>
			<div><input type="text" data-type="img" value="{{img}}" placeholder="img"></div>
			<div><input type="text" data-type="cocinero" value="{{cocinero}}" placeholder="cocinero"></div>
			<div><input type="text" data-type="precio" value="{{precio}}" placeholder="precio"></div>
			<div><input type="text" data-type="direccion" value="{{direccion}}" placeholder="direccion"></div>
			<div><input type="text" data-type="mail" value="{{mail}}" placeholder="mail"></div>
			<div><input type="text" data-type="horario" value="{{horario}}" placeholder="horario"></div>
			<div><input type="text" data-type="lat" value="{{lat}}" placeholder="lat"></div>
			<div><input type="text" data-type="lng" value="{{lng}}" placeholder="lng"></div>			
			<div><button class="delete-row button -salmon center">ELIMINAR</button></div>
		</div>
	</script>

	<script type="text/javascript">		
		;(function(){
			var template, container;
			var xhr = new XMLHttpRequest();			

			// Init
			init();

			function init(){
				getRemoteData();
			}

			// Methods
			function getRemoteData(){
				xhr.open('POST', 'index.php');
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						var data = JSON.parse(xhr.responseText);
						// renderTableOld(data);
						renderTable(data.platosdeldia);
					}
				};
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.send('method=getJson');
			}

			
			// DOM
			var addBtn = document.getElementsByClassName("add-row");
			var saveBtn = document.getElementById("save");

			// Binds
			addBtn[0].addEventListener('click', addRow);
			saveBtn.addEventListener('click', function(){
				data = { platosdeldia: getLocalData() };
				setData(data);
			});

			function bindEvents(){
				var deleteBtn = document.getElementsByClassName("delete-row");

				for (var i = 0; i < deleteBtn.length; i++) {
					deleteBtn[i].addEventListener('click', deleteRow);
				}
			}

			function getLocalData(){
				var a = [];
				var rows = document.querySelectorAll(".row-deletable");    
			    for (var i = 0; i < rows.length; i++) {
			    	a.push({
			    		'id': '',
			    		'plato': rows[i].querySelectorAll("input[data-type='plato']")[0].value,
						'comentario': rows[i].querySelectorAll("input[data-type='comentario']")[0].value,
						'img': rows[i].querySelectorAll("input[data-type='img']")[0].value,
						'cocinero': rows[i].querySelectorAll("input[data-type='cocinero']")[0].value,
						'precio': rows[i].querySelectorAll("input[data-type='precio']")[0].value,
						'direccion': rows[i].querySelectorAll("input[data-type='direccion']")[0].value,
						'mail': rows[i].querySelectorAll("input[data-type='mail']")[0].value,
						'horario': rows[i].querySelectorAll("input[data-type='horario']")[0].value,
						'location':{
							'lat': parseFloat(rows[i].querySelectorAll("input[data-type='lat']")[0].value),
							'lng': parseFloat(rows[i].querySelectorAll("input[data-type='lng']")[0].value)
						}
			    	});
			    }
			    return a;
			}

			function setData(data){
				data.platosdeldia.forEach(function(v,k){
					data.platosdeldia[k]['id'] = k;
				})
				xhr.open('POST', 'index.php');
				xhr.onreadystatechange = function() {
				    if (xhr.readyState == 4 && xhr.status == 200) {
				    	console.log(xhr.responseText);
				    }
				 };
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

				// xhr.setRequestHeader("Content-Type", "application/json");
				xhr.send('method=setJson&data='+JSON.stringify(data));
				// xhr.send('method=getJson');
			}

			function renderTable(data){
				console.log(data)
				template = document.getElementById('table-template').innerHTML;
				container = document.getElementById('deletables');
				_render(data, true);
				bindEvents();

			}

			function addRow(){
				data = {
					'plato': document.querySelectorAll(".row-adder input[data-type='plato']")[0].value,
					'comentario': document.querySelectorAll(".row-adder input[data-type='comentario']")[0].value,
					'img': document.querySelectorAll(".row-adder input[data-type='img']")[0].value,
					'cocinero': document.querySelectorAll(".row-adder input[data-type='cocinero']")[0].value,
					'precio': document.querySelectorAll(".row-adder input[data-type='precio']")[0].value,
					'direccion': document.querySelectorAll(".row-adder input[data-type='direccion']")[0].value,
					'mail': document.querySelectorAll(".row-adder input[data-type='mail']")[0].value,
					'horario': document.querySelectorAll(".row-adder input[data-type='horario']")[0].value,
					'location':{
						'lat': parseFloat(document.querySelectorAll(".row-adder input[data-type='lat']")[0].value),
						'lng': parseFloat(document.querySelectorAll(".row-adder input[data-type='lng']")[0].value)		
					}
				};
				renderTable([data]);
				console.log('asd')
				var inputs = document.querySelectorAll(".row-adder input");
				for(var i=0; i < inputs.length; i++) inputs[i].value = '';

			}

			function deleteRow(){
				this.parentElement.parentElement.remove();
			}

			function _render(data, append){
				Mustache.parse(template);
				var rendered = '';
				console.log(data);
				data.forEach((v)=>rendered += Mustache.render(template, {
					plato: v.plato,
					comentario: v.comentario,
					img: v.img,
					cocinero: v.cocinero,
					precio: v.precio,
					direccion: v.direccion,
					mail: v.mail,
					horario: v.horario,
					lat: v.location.lat,
					lng: v.location.lng
				}));
				if(append) rendered = container.innerHTML + rendered;
				container.innerHTML = rendered;
			}

		})();
	</script>
</body>
</html>
<?php 
// functions
function get(){	
	$file = '../platosdeldia.json';
	$data = json_decode(file_get_contents($file));
 	if (json_last_error() != JSON_ERROR_NONE) $data = array('error'=>'Invalid JSON');
	header('Content-type: application/json');
	echo json_encode($data);
}

function save($data) {
	$file = '../platosdeldia.json';
	if (json_last_error() == JSON_ERROR_NONE) {
		file_put_contents($file, json_encode($data,JSON_PRETTY_PRINT));		
	}
}
?>