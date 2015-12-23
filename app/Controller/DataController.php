<?php
App::import('Vendor', 'excel_reader');


class DataController extends AppController 
{
	public $components = array('Paginator', 'Attempt.Attempt');

	public $uses = array('Data','DataTel', 'Cliente');


	public function recepcion()
	{
		if ($this->request->is('post')) 
		{			
				
			$data = new Spreadsheet_Excel_Reader($this->data['Data']['archivo']['tmp_name'], true);
		    $temp = $data->dumptoarray(0);

		    $datas = array();
		    $tlfs = array();

		    for($i=2; $i< count($temp); $i++)
		    {

		    	$CedulaOrif	= 	$temp[$i][2] . $temp[$i][3];
		    	$Nombre		=	$temp[$i][4] . " " . $temp[$i][5] . " " . $temp[$i][6] . " " . $temp[$i][7];
		    	$Sexo 		=  $temp[$i][8];
		    	$FechaRaw 	= 	strtotime($temp[$i][9]);
		    	$FechaNac	=	date('Y-m-d',$FechaRaw);
		    	$GrupoSocial=  $temp[$i][10];
		    	$Estado		=  $temp[$i][11];
		    	$Direccion 	=  $temp[$i][12];
		    	$Email 		=  $temp[$i][13];
		    	$Fecha 		= date('d/m/Y');

		    	//Fecha

		    	$j = 14;

				while($temp[$i][$j] != ' ' && $j<42){
					$tlf = array(
						"CedulaOrif" => $CedulaOrif,
						"Telefono" 	 => $temp[$i][$j] . $temp[$i][$j + 1]  
					);

					if(!empty($CedulaOrif))
					array_push($tlfs, $tlf);

					$j += 2;
				}

				$data = array(
					"CedulaOrif" => $CedulaOrif,
					"Nombre" 	 => $Nombre,
					"Sexo" 		 => $Sexo,
					"FechaNac" 	 => $FechaNac,
					"GrupoSocial"=> $GrupoSocial,
					"Estado" 	 => $Estado,
					"Direccion"	 => $Direccion,
					"Email" 	 => $Email,
					"Fecha"		 => $Fecha 
				);

				if(!empty($CedulaOrif))
				array_push($datas, $data);
		    }

			unlink($this->data['Data']['archivo']['tmp_name']);
			
     	   $this->Data->create();
		   $this->Data->saveMany($datas);

		   $this->DataTel->create();
		   $this->DataTel->saveMany($tlfs);
		  // debug($tlfs);
		   debug("Fin de la guardada");
		}
	}

}

?>
