<?php
	require_once 'class.phpmailer.php';
	require_once 'config.php';
	
	/**
	 * GPS车辆管理系统__电子邮件类
	 *
	 * @author QYH_刘欢
	 * @time   2010年8月4日
	 */
	class Email{
		private $title;
		private $content;
		private $fromName;
		private $mailer;  
		private $mail_config;

		private $error_info = "no error";

		function __construct($mail_config)
		{
			$this->mail_config = $mail_config;
			$this->mailer= new PHPMailer();
		}
		
		//错误信息
		public function getErrorInfo()
		{
			return $this->error_info;
		}
		//发邮件方法
		public function sendMail($address,$title,$content,$fromName)
		{
			$this->title=$title;
			$this->content=$content;
			$this->fromName=$fromName;

			if(is_string($address))
			{
				$addressArray=explode(",",$address);
				$this->addAddress($addressArray);
			}
			else if(is_array($address))
			{
				$this->addAddress($address);
			}
			else
			{
				$this->error_info = "邮件地址只能包含在Array或String中";
				return false; 
			}			

			$this->mailer->IsSMTP();									// 设置发送邮件的协议：SMTP
			$this->mailer->Host=$this->mail_config['Host'];				// 发送邮件的服务器   
			$this->mailer->SMTPAuth=$this->mail_config['SMTPAuth'];		// 打开SMTP 
			$this->mailer->Port=$this->mail_config['Port'];				// 设置SMTP的端口号
			$this->mailer->Username=$this->mail_config['Username'];		// SMTP账户   
			$this->mailer->Password=$this->mail_config['Password'];		// SMTP密码   
			$this->mailer->From=$this->mail_config['From'];				// 发件人E-mail地址
			$this->mailer->CharSet=$this->mail_config['CharSet'];		// 设置字符集编码   
			$this->mailer->SMTPSecure=$this->mail_config['SMTPSecure'];	// 设置连接的前缀。

			$this->mailer->FromName = $this->fromName;					// 发件人称呼
			$this->mailer->Subject = $this->title;						// 邮件标题
			$this->mailer->Body = $this->content;						// 邮件内容

			if(!$this->mailer->Send())   
			{   
				$this->error_info = $this->mailer->ErrorInfo;
				return false;			//发送失败
			}  
			else{
				return true;            //发送成功
			}
		}
		//增加收件人
		protected function addAddress($Add)
		{
			for($i=0;$i<count($Add);$i++)
				{ 
					if($this->checkEmail($Add[$i])==false) 
					{
						$this->error_info = "无效的邮件地址";
						return false; 
					}
					else{
						$this->mailer->AddAddress($Add[$i]);	// 添加到收件人
					}
				}
		}
		//验证邮箱格式
		protected function checkEmail($inAddress){
			return (ereg( "^[^@ ]+@([a-zA-Z0-9-]+.)+([a-zA-Z0-9-]{2}|net|com|gov|mil|org|edu|int)$",$inAddress));
		}

	}

?>