<?php 
/**
 * @author Ahmad Zaelani 
 * @param cekApikey check the key api
 * @param Siswalimit is limit the siswa table for pagination siswa
 * @param Allsiswa is show all data for counting paginatinNumbersiswa
 * 
**/
namespace Api\WebApi\Data;

use App\Connection\Database;

class Siswa extends Database
{
	protected $stmt;
	protected $row;
	protected $api;

	protected function cekApikey($key)
	{
		try
		{
			$sql = "SELECT api_key, api_open, api_close, api_status FROM web_api WHERE api_key=:api_key";
			$this->stmt =$this->connection->prepare($sql);	
			$this->stmt->bindParam(":api_key",$key);	
			$this->stmt->execute();	
			
			$this->api = $this->stmt->fetch(\PDO::FETCH_ASSOC);
			return $this->api;

		}
		catch(\PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	private function Siswalimit($start, $finish)
	{
		try
		{
			$sql = "SELECT id_siswa, users.uid, nisn, nis, nama_siswa, kelas, jl, tempat_lahir, tanggal_lahir, al_lengkap, kontak_siswa, ay_nama, ay_kontak, status_siswa, field_kelas.nama_kelas as nama_kelas, status_pendaftaran, users.name as name, users.mail as mail, users.role as role  FROM field_siswa 
				LEFT JOIN users ON users.uid=field_siswa.uid 
				LEFT JOIN field_kelas ON field_kelas.id_kelas=field_siswa.kelas
				WHERE status_siswa=1 ORDER by kelas  LIMIT $start, $finish";
			$this->stmt =$this->connection->prepare($sql);		
			return $this->stmt;

		}

		catch(\PDOException $e)
		{
			echo "Can't select siswa :".$e->getMessage();
		}
	}
	private function AllSiswa()
	{
		try
		{
			$sql = "SELECT * FROM field_siswa WHERE status_siswa=1";
			$this->stmt =$this->connection->prepare($sql);		
			return $this->stmt;

		}

		catch(\PDOException $e)
		{
			echo "Can't select siswa :".$e->getMessage();
		}
	}
	public function Selectkelas()
	{
		try
		{
			$sql ="SELECT id_kelas, nama_kelas, COUNT(kelas) AS jml_siswa FROM field_kelas 
			LEFT JOIN field_siswa ON field_kelas.id_kelas=field_siswa.kelas
			GROUP by nama_kelas
			ORDER by jml_siswa ";
			$this->stmt = $this->connection->prepare($sql);
			$this->stmt->execute();
			while ($data=$this->stmt->fetch(\PDO::FETCH_ASSOC)){
				$this->row[]=$data;
			
				
			}
			if(!empty($this->row))
			{
				$respone = array('status'=>1,'message'=>'Success','data'=>$this->row);

				header('Content-type: application/json');
				echo json_encode($respone);
			}else{
				$respone = array('status'=>0,'message'=>'Data not Found','data'=>$this->row);

				header('Content-type: application/json');
				echo json_encode($respone);
			}
		}
		catch(\PDOException $e)
		{
			echo "Can't select table kelas :".$e->getMessage();
		}
	}
	protected function Siswakelas($kelas)
	{
		try
		{
			$sql = "SELECT id_siswa, users.uid, nisn, nis, nama_siswa, kelas, jl, tempat_lahir, tanggal_lahir, al_lengkap, kontak_siswa, ay_nama, ay_kontak, field_kelas.nama_kelas as nama_kelas, status_pendaftaran, users.name as name, users.mail as mail, users.role as role  FROM field_siswa 
				LEFT JOIN users ON users.uid=field_siswa.uid 
				LEFT JOIN field_kelas ON field_kelas.id_kelas=field_siswa.kelas
				WHERE status_siswa=1 AND kelas=:kelas ORDER by nama_siswa";
			$this->stmt =$this->connection->prepare($sql);
			$this->stmt->bindParam(":kelas", $kelas);	
			$this->stmt->execute();

			while ($data=$this->stmt->fetch(\PDO::FETCH_ASSOC)){
				$this->row[]=$data;
			
				
			}
			if(!empty($this->row))
			{
				$respone = array('status'=>1,'message'=>'Success','data'=>$this->row);

				header('Content-type: application/json');
				echo json_encode($respone);
			}else{
				$respone = array('status'=>0,'message'=>'Data not Found','data'=>$this->row);

				header('Content-type: application/json');
				echo json_encode($respone);
			}

		}

		catch(\PDOException $e)
		{
			echo "Can't select siswa :".$e->getMessage();
		}
	}
	protected function getSiswa($uid)
	{
		try
		{
			
			$sql = "SELECT *FROM field_siswa WHERE uid=:uid";
			$this->stmt = $this->connection->prepare($sql);
			$this->stmt->bindParam(":uid",$uid);
			$this->stmt->execute();
			
			while ($data=$this->stmt->fetch(\PDO::FETCH_ASSOC)){
				$this->row[]=$data;
			
				
			}
			if(!empty($this->row))
			{
				$respone = array('status'=>1,'message'=>'Success','data'=>$this->row);
				header('Content-type: application/json');
				echo json_encode($respone);
			}else{
				$respone = array('status'=>0,'message'=>'Data not Found','data'=>$this->row);

				header('Content-type: application/json');
				echo json_encode($respone);
			}

		}
		catch(\PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	protected function paginationSiswa($number)
	{
		
		$finish=$number;
		$page=isset($_GET['halaman'])? (int)$_GET['halaman']:1;	
		$start=($page>1) ? ($page * $finish) - $finish:0;
		$this->stmt=self::Siswalimit($start, $finish);	
		$this->stmt->execute();
		while ($data=$this->stmt->fetch(\PDO::FETCH_ASSOC))
		{
			$this->row[]=$data;		
		}
			
			
		if(!empty($this->row))
		{
				$respone = array('status'=>1,'message'=>'Success','data'=>$this->row);
				header('Content-type: application/json');
				echo json_encode($respone);
		}else{
			
				$respone = array('status'=>0,'message'=>'Data not Found','data'=>$this->row);
				header('Content-type: application/json');
				echo json_encode($respone);
		}
	}
	protected function paginationNumberSiswa($number)
	{
		//$nopage = isset($_GET[$name])? (int)$_GET[$name]:1;	
		$this->stmt=self::AllSiswa();	
		$this->stmt->execute();
		$total=$this->stmt->rowCount();
		$jumpage=ceil($total/$number);
		
		$respone = array('status'=>1,'total'=>$total,'message'=>'ok','page'=>$jumpage);
		header('Content-type: application/json');
		echo json_encode($respone);
	}
	private function Userlimit($start, $finish)
	{
		try
		{
			$sql = "SELECT users.uid, users.name, users.mail, pass, photo, status, role, id_profile, user_profile.nama_lengkap, user_profile.tempat_lahir, user_profile.tgl_lahir, user_profile.jenis_kelamin, user_profile.kontak_whatsapp,status_pendaftaran, nama_kelas FROM users 
			LEFT JOIN user_profile ON user_profile.uid=users.uid
			LEFT JOIN field_siswa ON field_siswa.uid=users.uid
			LEFT JOIN field_kelas ON field_kelas.id_kelas=field_siswa.kelas
			WHERE  status=1 AND role='siswa' AND status_pendaftaran='Diterima' OR status_pendaftaran='Terverifikasi' ORDER by users.uid LIMIT $start, $finish";
			$this->stmt =$this->connection->prepare($sql);		
			return $this->stmt;
		}
		catch(\PDOException $e)
		{
			echo "Can't select user :".$e->getMessage();
		}
	}
	protected function paginationUserlimit($number)
	{
		
		$finish=$number;
		$page=isset($_GET['halaman'])? (int)$_GET['halaman']:1;	
		$start=($page>1) ? ($page * $finish) - $finish:0;
		$this->stmt=self::Userlimit($start, $finish);	
		$this->stmt->execute();
		while ($data=$this->stmt->fetch(\PDO::FETCH_ASSOC))
		{
			$this->row[]=$data;		
		}
			
			
		if(!empty($this->row))
		{
				$respone = array('status'=>1,'message'=>'Success','data'=>$this->row);
				header('Content-type: application/json');
				echo json_encode($respone);
		}else{
			
				$respone = array('status'=>0,'message'=>'Data not Found','data'=>$this->row);
				header('Content-type: application/json');
				echo json_encode($respone);
		}
	}
	protected function paginationNumberUserlimit($number)
	{
		//$nopage = isset($_GET[$name])? (int)$_GET[$name]:1;	
		$this->stmt=self::Alluser();	
		$this->stmt->execute();
		$total=$this->stmt->rowCount();
		$jumpage=ceil($total/$number);
		
		$respone = array('status'=>1,'total'=>$total,'message'=>'ok','page'=>$jumpage);
		header('Content-type: application/json');
		echo json_encode($respone);
	}
	private function AllUser()
	{
		try
		{
			$sql = "SELECT users.uid FROM users 
			LEFT JOIN user_profile ON user_profile.uid=users.uid
			LEFT JOIN field_siswa ON field_siswa.uid=users.uid
			WHERE status=1 AND role='siswa' AND status_pendaftaran='Diterima' OR status_pendaftaran='Terverifikasi'";
			$this->stmt =$this->connection->prepare($sql);		
			return $this->stmt;
		}
		catch(\PDOException $e)
		{
			echo "Can't select user :".$e->getMessage();
		}
	}

}