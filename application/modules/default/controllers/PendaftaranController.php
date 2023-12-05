<?php
//belum berhasil
// use Zend\Mail\Message;
// use Zend\Mail\Transport\Smtp as SmtpTransport;
// use Zend\Mail\Transport\SmtpOptions;

class PendaftaranController extends Zend_Controller_Action
{
	public function init()
	{
		$this->_helper->_acl->allow();
		//$this->_helper->_acl->allow('admin');
		//$this->_helper->_acl->allow('super');
		//$this->_helper->_acl->allow('user', array('index', 'edit'));
	}

	public function preDispatch()
	{
		$this->PesertaNonAsnService = new PesertaNonAsnService();
		$this->PesertaService = new PesertaService();
		$this->UserService = new UserService();

	}

	public function indexAction()
	{
		if ($this->getRequest()->isPost()) {
			$username = $this->getRequest()->getParam('usernamex');
			$email = $this->getRequest()->getParam('email');
			$password = $this->getRequest()->getParam('password');
			$nama = $this->getRequest()->getParam('nama');
			$identitas = $this->getRequest()->getParam('identitas');
			$tanggal_lahir = $this->getRequest()->getParam('tanggal_lahir');
			$tempatlahir = $this->getRequest()->getParam('tempatlahir');
			$jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
			$pekerjaan = $this->getRequest()->getParam('pekerjaan');
			$kewarganegaraan = $this->getRequest()->getParam('kewarganegaraan');
			$telepon = $this->getRequest()->getParam('telepon');

			$last_id = $this->PesertaService->registerNonAsn($nama, $email, $identitas, $tempatlahir, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $kewarganegaraan, $telepon);

			$id = 1000000 + $last_id;

			$this->UserService->register($id, $username, $password, $nama, $email, $telepon, $last_id);

			$this->_redirect('/');
			// $this->PesertaService->registerNonAsn($last_id, $nama);

		}
	}


	public function ubahpasswordAction()
	{

		$session = new Zend_Session_Namespace('loggedInUser');
		$auth = $session->user;

		$id = $auth->id;
		$old_password = $this->getRequest()->getParam('old_password');
		$new_password = $this->getRequest()->getParam('new_password');
		$confirm_password = $this->getRequest()->getParam('confirm_password');

		if ($new_password !== $confirm_password) {
			// Handle case where new password and confirmation do not match
			$this->view->error = "Password baru dan konfirmasi tidak cocok.";
			return;
		}

		try {
			$this->UserService->UbahPasswordData($id, $new_password);
			
			$this->_redirect('/pengajarnonasn');

		} catch (Exception $e) {
			$this->view->error = $e->getMessage();
		}

	}






	public function indexActionxxx()
	{
		if ($this->getRequest()->isPost()) {
			$username = $this->getRequest()->getParam('usernamex');
			$email = $this->getRequest()->getParam('email');
			$password = $this->getRequest()->getParam('password');
			$nama = $this->getRequest()->getParam('nama');
			$identitas = $this->getRequest()->getParam('identitas');
			$tanggal_lahir = $this->getRequest()->getParam('tanggal_lahir');
			$tempatlahir = $this->getRequest()->getParam('tempatlahir');
			$jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
			$pekerjaan = $this->getRequest()->getParam('pekerjaan');
			$kewarganegaraan = $this->getRequest()->getParam('kewarganegaraan');
			$telepon = $this->getRequest()->getParam('telepon');
			$file_name = "";

			// Simpan pengguna ke dalam database dan dapatkan ID terakhir
			$last_id = $this->PesertaService->registerNonAsn($nama, $email, $identitas, $tempatlahir, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $kewarganegaraan, $telepon, $file_name);

			// Generate unique verification token
			$random_bytes = openssl_random_pseudo_bytes(32);
			$verification_token = bin2hex($random_bytes);

			$id = 1000000 + $last_id;

			// Simpan token verifikasi dan informasi pengguna lainnya ke dalam database
			$this->UserService->registerWithToken($id, $username, $password, $nama, $email, $telepon, $last_id, $verification_token);

			// Kirim email verifikasi menggunakan SMTP Gmail
			$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
				->setUsername('hatta1996@gmail.com') // Ganti dengan alamat email Gmail Anda
				->setPassword('mareta96'); // Ganti dengan kata sandi Gmail Anda

			$mailer = new Swift_Mailer($transport);
			$verification_link = "https://belajar.dpr.go.id/verify.php?token=" . $verification_token;
			$to = $email;
			$subject = "Verifikasi Akun Anda";
			$message = "Halo " . $nama . ",\n\nTerima kasih telah mendaftar. Silakan klik tautan berikut untuk verifikasi akun Anda:\n" . $verification_link;
			$headers = "From: no-reply@dpr.go.id"; // Ganti dengan alamat email Anda

			$email = (new Swift_Message($subject))
				->setFrom(['no-reply@dpr.go.id' => 'DPR'])
				->setTo([$to])
				->setBody($message);

			$result = $mailer->send($email);



			if ($result) {
				$this->_redirect('/verify');
				// Tampilkan pesan sukses atau arahkan pengguna ke halaman verifikasi
				// Misalnya:
				echo "<script>alert('Registrasi berhasil. Silakan periksa email Anda untuk verifikasi akun'); window.location.href = '/verify';</script>";
			} else {
				// Tampilkan pesan gagal mengirim email
				echo "<script>alert('Gagal mengirim email verifikasi. Silakan coba lagi'); window.location.href = '/register';</script>";
			}
		}
	}


	// menggunakan SmtpTransport(); tapi belum berhasil

	// public function indexAction()
// {
//     if ($this->getRequest()->isPost()) {
//         $username = $this->getRequest()->getParam('usernamex');
//         $email = $this->getRequest()->getParam('email');
//         $password = $this->getRequest()->getParam('password');
//         $nama = $this->getRequest()->getParam('nama');
//         $identitas = $this->getRequest()->getParam('identitas');
//         $tanggal_lahir = $this->getRequest()->getParam('tanggal_lahir');
//         $tempatlahir = $this->getRequest()->getParam('tempatlahir');
//         $jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
//         $pekerjaan = $this->getRequest()->getParam('pekerjaan');
//         $kewarganegaraan = $this->getRequest()->getParam('kewarganegaraan');
//         $telepon = $this->getRequest()->getParam('telepon');
//         $file_name = "";

	//         // Simpan pengguna ke dalam database dan dapatkan ID terakhir
//         $last_id = $this->PesertaService->registerNonAsn($nama, $email, $identitas, $tempatlahir, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $kewarganegaraan, $telepon,$file_name);

	//         // Generate unique verification token
//         $random_bytes = openssl_random_pseudo_bytes(32);
//         $verification_token = bin2hex($random_bytes);

	//         $id = 1000000+$last_id;

	//         // Simpan token verifikasi dan informasi pengguna lainnya ke dalam database
//         $this->UserService->registerWithToken($id, $username, $password, $nama, $email, $telepon,$last_id, $verification_token);

	// 	// Kirim email verifikasi menggunakan SMTP Gmail
// 		$transportOptions = [
// 			'host' => 'smtp.gmail.com',
// 			'port' => 587,
// 			'connection_class' => 'login',
// 			'connection_config' => [
// 				'ssl' => 'tls',
// 				'username' => 'hatta1996@gmail.com', // Ganti dengan alamat email Gmail Anda
// 				'password' => 'mareta96', // Ganti dengan kata sandi Gmail Anda
// 			],
// 		];

	// 		$transport = new SmtpTransport();
// 		$options = new SmtpOptions($transportOptions);
// 		$transport->setOptions($options);

	// $verification_link = "https://belajar.dpr.go.id/verify.php?token=" . $verification_token;
//         $to = $email;
//         $subject = "Verifikasi Akun Anda";
//         $message = "Halo " . $nama . ",\n\nTerima kasih telah mendaftar. Silakan klik tautan berikut untuk verifikasi akun Anda:\n" . $verification_link;
//         $headers = "From: no-reply@dpr.go.id"; // Ganti dengan alamat email Anda





	// 		$mailer = new Message();
// 		$mailer->setFrom('no-reply@dpr.go.id', 'DPR'); // Ganti dengan alamat email Anda
// 		$mailer->addTo($to);
// 		$mailer->setSubject($subject);
// 		$mailer->setBody($message);

	// 		$result = $transport->send($mailer);





	//         if ($result) {
//             $this->_redirect('/verify');
//             // Tampilkan pesan sukses atau arahkan pengguna ke halaman verifikasi
//             // Misalnya:
//             echo "<script>alert('Registrasi berhasil. Silakan periksa email Anda untuk verifikasi akun'); window.location.href = '/verify';</script>";
//         } else {
//             // Tampilkan pesan gagal mengirim email
//             echo "<script>alert('Gagal mengirim email verifikasi. Silakan coba lagi'); window.location.href = '/register';</script>";
//         }
//     }
// }

	// public function testIndexAction()
	// {
	// 	// Create a mock of the request object
	// 	$request = $this->getMockBuilder(Request::class)
	// 		->getMock();

	// 	// Set the request method to POST
	// 	$request->method('isPost')
	// 		->willReturn(true);

	// 	// Set the request parameters
	// 	$request->method('getParam')
	// 		->will($this->returnValueMap([
	// 			['usernamex', 'test_username'],
	// 			['email', 'test_email@example.com'],
	// 			['password', 'test_password'],
	// 			['nama', 'test_nama'],
	// 			['identitas', 'test_identitas'],
	// 			['tanggal_lahir', 'test_tanggal_lahir'],
	// 			['tempatlahir', 'test_tempatlahir'],
	// 			['jenis_kelamin', 'test_jenis_kelamin'],
	// 			['pekerjaan', 'test_pekerjaan'],
	// 			['kewarganegaraan', 'test_kewarganegaraan'],
	// 			['telepon', 'test_telepon'],
	// 		]));

	// 	// Create a mock of the PesertaService object
	// 	$pesertaService = $this->getMockBuilder(PesertaService::class)
	// 		->disableOriginalConstructor()
	// 		->getMock();

	// 	// Set expectations for the registerNonAsn method
	// 	$pesertaService->expects($this->once())
	// 		->method('registerNonAsn')
	// 		->with(
	// 			'test_nama',
	// 			'test_email@example.com',
	// 			'test_identitas',
	// 			'test_tempatlahir',
	// 			'test_tanggal_lahir',
	// 			'test_jenis_kelamin',
	// 			'test_pekerjaan',
	// 			'test_kewarganegaraan',
	// 			'test_telepon'
	// 		)
	// 		->willReturn(123); // Set the return value to the last_id

	// 	// Create a mock of the UserService object
	// 	$userService = $this->getMockBuilder(UserService::class)
	// 		->disableOriginalConstructor()
	// 		->getMock();

	// 	// Set expectations for the register method
	// 	$userService->expects($this->once())
	// 		->method('register')
	// 		->with(
	// 			1000123, // Set the expected value of the $id parameter
	// 			'test_username',
	// 			'test_password',
	// 			'test_nama',
	// 			'test_email@example.com',
	// 			'test_telepon',
	// 			123 // Set the expected value of the $last_id parameter
	// 		);

	// 	// Create a mock of the controller object
	// 	$controller = $this->getMockBuilder(YourController::class)
	// 		->setMethods(['_redirect'])
	// 		->getMock();

	// 	// Set expectations for the _redirect method
	// 	$controller->expects($this->once())
	// 		->method('_redirect')
	// 		->with('/');

	// 	// Set the request and service objects to the controller
	// 	$controller->setRequest($request);
	// 	$controller->setPesertaService($pesertaService);
	// 	$controller->setUserService($userService);

	// 	// Call the indexAction method
	// 	$controller->indexAction();
	// }





	// 	public function indexxAction()
// {
//     if ($this->getRequest()->isPost()) {
//         $username = $this->getRequest()->getParam('usernamex');
//         $email = $this->getRequest()->getParam('email');
//         $password = $this->getRequest()->getParam('password');
//         $nama = $this->getRequest()->getParam('nama');
//         $identitas = $this->getRequest()->getParam('identitas');
//         $tanggal_lahir = $this->getRequest()->getParam('tanggal_lahir');
//         $tempatlahir = $this->getRequest()->getParam('tempatlahir');
//         $jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
//         $pekerjaan = $this->getRequest()->getParam('pekerjaan');
//         $kewarganegaraan = $this->getRequest()->getParam('kewarganegaraan');
//         $telepon = $this->getRequest()->getParam('telepon');

	//         // Simpan pengguna ke dalam database dan dapatkan ID terakhir
//         $last_id = $this->PesertaService->registerNonAsn($nama, $email, $identitas, $tempatlahir, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $kewarganegaraan, $telepon);

	//         // Generate unique verification token
// 		$random_bytes = openssl_random_pseudo_bytes(32);
// 		$verification_token = bin2hex($random_bytes);
// // var_dump($verification_token);die();

	// 		$id = 1000000+$last_id;

	//         // Simpan token verifikasi dan informasi pengguna lainnya ke dalam database
//         $this->UserService->register($id, $username, $password, $nama, $email, $telepon,$last_id, $verification_token);
//         // Kirim email verifikasi
//         $verification_link = "https://belajar.dpr.go.id/verify.php?token=" . $verification_token;
//         $to = $email;
//         $subject = "Verifikasi Akun Anda";
//         $message = "Halo " . $nama . ",\n\nTerima kasih telah mendaftar. Silakan klik tautan berikut untuk verifikasi akun Anda:\n" . $verification_link;
//         $headers = "From: no-reply@dpr.go.id"; // Ganti dengan alamat email Anda

	//         mail($to, $subject, $message, $headers);

	// 		$this->_redirect('/verify');
//         // Tampilkan pesan sukses atau arahkan pengguna ke halaman verifikasi
//         // Misalnya:
//         echo "<script>alert('Registrasi berhasil. Silakan periksa email Anda untuk verifikasi akun'); window.location.href = '/verify';</script>";

	//     }
// }

}