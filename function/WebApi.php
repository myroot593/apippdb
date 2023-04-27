<?php 

namespace Api\WebApi;
require_once 'WebData.php';
use Api\WebApi\Data\Siswa;

class RequestHttp extends Siswa
{
	
	public static function today()
	{
		$today = date('Y-m-d');
		return $today;
	}

	public function cekApiTime ($today, $time)
	{
		
		if($today>=$time['api_open'] && $today<$time['api_close']):
		
			return true;
		else:
			return false;
		endif;
	}
	public function requestType($type, $get='', $halaman='', $key='')
	{

		if($this->cekApikey($key))
		{
			if($this->cekApiTime(RequestHttp::today(), $this->api))
			{
				self::parsingRequest($type, $get);
			}
			else
			{
				$respone = array(
						'status'=>0,
						'message'=>'Api not yet open or was close',
						'open'=>$this->api['api_open'],
						'close'=>$this->api['api_close']
					);

				header('Content-type: application/json');
				echo json_encode($respone);
			}
			
		}
		else
		{
			$respone = array('status'=>0,'message'=>'Invalid Api key','data'=>$this->row);
			header('Content-type: application/json');
			echo json_encode($respone);
		}
	}
	protected function parsingRequest($type, $get='')
	{
		switch ($type)
		{
			
			case 'siswa':
				self::paginationSiswa(50);
				break;
			case 'paging_siswa':
				self::paginationNumberSiswa(50);
				break;
			case 'kelas':
				self::Selectkelas();
				break;
			case 'siswa_kelas':
				self::Siswakelas($get);
				break;
			case 'getsiswa':
				self::getSiswa($get);
				break;
			case 'user':
				self::paginationUserlimit(10);
				break;
			case 'paging_user':
				self::paginationNumberUserlimit(10);
				break;

			default:
				$respone = array('status'=>0,'message'=>'Data not Found','data'=>$this->row);
				header('Content-type: application/json');
				echo json_encode($respone);
			break;
		}
	}
	public function __destruct()
	{
		return true;
	}
	
}

namespace Api\WebApi\RequestHttp;

class Filter
{
	public static function filter($data)
	{
		$data = htmlspecialchars($data);
		$data = trim($data);
		$data = stripcslashes($data);
		return $data;
	}
	public static function post($data)
	{
		$data = $_POST[$data];
		$data = self::filter($data);
		return $data;
	}
	public static function get($data)
	{
		$data = $_GET[$data];
		$data = self::filter($data);
		return $data;
	}
	public static function getEmpty($page)
	{
			
		if(!isset($_GET[$page])){$_GET[$page]='';}
	}
}

