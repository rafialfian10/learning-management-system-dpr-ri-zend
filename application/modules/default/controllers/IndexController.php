<?php
class IndexController extends Zend_Controller_Action
{
	
	public function init() {  
		$this->_helper->_acl->allow();
		//$this->_helper->_acl->allow('admin');
		//$this->_helper->_acl->allow('super');
		//$this->_helper->_acl->allow('user', array('index', 'edit'));
	}
	
	public function preDispatch() {
		$this->BannerService = new BannerService();
		$this->PelatihanService = new PelatihanService();
		$this->BatchService = new BatchService();
		$this->PengajarService = new PengajarService();
		$this->SilabusService = new SilabusService();
		$this->RatingService = new RatingService();
		$this->UserService = new UserService();
		$this->PesertaService = new PesertaService();
	}
	
	public function indexAction() {	
		$this->view->pelatihans = $this->PelatihanService->getAllData();
		$this->view->ratings = $this->RatingService->getAllData();
		$this->view->batchs = $this->BatchService->getAllDataByPelatihan();
		$this->view->batch = $this->BatchService->getAllData();
		$this->view->pengajars = $this->PengajarService->getAllData();
		$this->view->banners = $this->BannerService->getAllData();

		if ($this->getRequest()->isPost()) {
			$pengguna = $this->getRequest()->getParam('pengguna');
			$sandi = $this->getRequest()->getParam('sandi');
			// Ambil respons CAPTCHA dari form.
			$captchaResponse = $this->getRequest()->getPost('g-recaptcha-response');
			$secretKey = '6LcrjkwnAAAAAJQ234vZEApM0Pi_lV92Ws5-i_b3';
			$verificationUrl = 'https://www.google.com/recaptcha/api/siteverify';
				$data = array(
					'secret' => $secretKey,
					'response' => $captchaResponse
				);

				$options = array(
					'http' => array(
						'header' => "Content-type: application/x-www-form-urlencoded\r\n",
						'method' => 'POST',
						'content' => http_build_query($data)
					)
				);
				$context = stream_context_create($options);
				$result = file_get_contents($verificationUrl, false, $context);
				$responseData = json_decode($result);
				// Periksa hasil verifikasi.
				if ($responseData && $responseData->success) {
					// CAPTCHA terverifikasi. Lanjutkan dengan pemrosesan formulir.
		
					// Tambahkan kode selanjutnya untuk memeriksa kredensial pengguna dan melanjutkan dengan proses login.
		
					
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://portal.dpr.go.id/login/gapenting',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
				"waktu":"0",
				"token":"belajarlms",
				"pengguna":"'.$pengguna.'",
				"sandi":"'.$sandi.'"
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Cookie: PHPSESSID=1s2gf4vv1oa5mul3spnrkhhtj7; TS016a9b7c=0108f7efbd231701708d8f94da4c32d187910015b0f919ed8e9f67b4d5f0ecc45810396cb1d665aacd78b2e00b74fb8287356d8e0273e0516096b23d1cb280fe35ef277d84'
			),
			));

			$response = curl_exec($curl);

			curl_close($curl);

			// var_dump($response);
			// exit;

			// if response contain 'Error' then login failed
			if (preg_match('/Error/', $response)) {
				var_dump($response);
				exit;
			} else {
				$data = json_decode($response, true);
				// var_dump($data); die();

				$id = $data['id'];
				$nama = $data['nama'];

				// Jika nama memiliki huruf kapital semua atau campuran huruf besar-kecil
				
				// $nama = preg_replace('/(?<!^)([A-Z])/', ' $1', $nama);
				
				// Ubah ke format teks standar (huruf pertama setiap kata besar, sisanya kecil)
				$nama = ucwords(strtolower($nama));
				
				// $nama = preg_replace('/(?<!^)([A-Z])/', ' $1', $data['nama']);

				// var_dump($nama); die();

				if (!empty($nama)) {
					// Check apakah nama sudah ada di PesertaService
					$namaExists = $this->PesertaService->checkIfExists($nama);
				
					if ($namaExists) {
						// Menampilkan pesan error dengan popup JavaScript jika nama sudah terdaftar
						echo "<script>alert('Nama Sudah Terdaftar Silahkan Login Sebagai ASN.'); window.location.href = '/';</script>";
					} else {
						try {
							// Jika nama belum terdaftar, tambahkan data peserta baru
							$id_peserta = $this->PesertaService->addData($nama);
							$this->UserService->addData($pengguna, $sandi, $id, $nama, $id_peserta);
							$this->_redirect('/admin');
						} catch (Exception $e) {
							$this->view->error = $e->getMessage();
						}
					}
				} else {
					// Menampilkan pesan error dengan popup JavaScript jika username atau password salah
					echo "<script>alert('Username & Password Salah Silahkan Mencoba Kembali.'); window.location.href = '/';</script>";
				}
				
			}
				} else {
					// Verifikasi CAPTCHA gagal. Tampilkan pesan kesalahan atau ambil tindakan yang sesuai.
					echo "<script>alert('Verifikasi CAPTCHA gagal. Silakan coba lagi.'); window.location.href = '/';</script>";
				}
			
			


			

		}
	}
	
	public function pilihanPelatihanAction() {	
		$this->view->rows = $this->PelatihanService->getAllData();
		$this->view->rows2 = $this->PengajarService->getAllData();
	}

	public function detailPelatihanAction() {	
		$id = $this->getRequest()->getParam('id');
		$this->view->pelatihan = $this->PelatihanService->getData($id);
		$this->view->silabus = $this->SilabusService->getAllDataPelatihan($id);
		$this->view->pengajar = $this->PengajarService->getAllData();
		$this->view->materi = $this->MateriSilabusService->getAllData();
	}

	public function dashboardAction() {	
		// $this->view->pengajar = $this->PengajarService->getAllData();
	}

	public function pelatihanAction() {	
		// $this->view->pengajar = $this->PengajarService->getAllData();
	}

	public function forumAction() {	
		// $this->view->pengajar = $this->PengajarService->getAllData();
	}

	public function preTestAction() {	
		// $this->view->pengajar = $this->PengajarService->getAllData();
	}

	public function quizAction() {	
		// $this->view->pengajar = $this->PengajarService->getAllData();
	}

	public function tugasAkhirAction() {	
		// $this->view->pengajar = $this->PengajarService->getAllData();
	}

	public function sertifikatAction() {	
		// $this->view->pengajar = $this->PengajarService->getAllData();
	}

	public function messageAction() {	
		// $this->view->pengajar = $this->PengajarService->getAllData();
	}
}