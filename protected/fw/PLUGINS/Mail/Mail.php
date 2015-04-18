<?php
/**
 * Class that deals mainly with email sending, aiming to provide a small API
 * to PEAR's SASL/SMTP monster classes
 * @author Victor Nitu
 * @desc Class that deals mainly with email sending, aiming to provide a small
 * API to PEAR's SASL/SMTP monster classes
 *
 */

class Mail
{
    const CRLF = "\r\n";

      private
        $_server,
        $_port,
        //$_localhost,      // Not sure why I created it, so I commented it out
        $_skt;

      public
        $username,
        $password,
        $connectTimeout,
        $responseTimeout,
        $headers,
        $contentType,
        $from,
        $mailTo,
        $mailCc,
        $subject,
        $message,
        $log;

      function __construct($server = "127.0.0.1", $port = 25)
      {
        $this->_server = $server;
        $this->_port = $port;
        //$this->_localhost = "localhost";
        $this->connectTimeout = 30;
        $this->responseTimeout = 8;
        $this->from = array();
        $this->mailTo = array();
        $this->mailCc = array();
        $this->log = array();
        $this->headers['MIME-Version'] = "1.0";
        //$this->headers['Content-type'] = "text/plain; charset=utf-8";
      }

      private function GetResponse()
      {
        stream_set_timeout($this->_skt, $this->responseTimeout);
        $response = '';
        while (($line = fgets($this->_skt, 515)) != false) {
            $response .= trim($line) . "\n";
            if (substr($line, 3, 1)==' ') break;
        }
        return trim($response);
      }

      private function SendCMD($cmd)
      {
        fputs($this->_skt, $cmd . self::CRLF);

        return $this->GetResponse();
      }

      function addAttachment($file)
      {
        $filePath = $file['tmp_name'];
        $fileType = $file['type'];
        $fileName = $file['name'];

        // File
        $file = fopen($filePath, 'rb');
        $data = fread($file, filesize($filePath));
        fclose($file);

        // This attaches the file
        $semiRand = md5(time());
        $mimeBoundary = "==Multipart_Boundary_x{$semiRand}x";
        $this->headers .= "\nMIME-Version: 1.0\n" .
        "Content-Type: multipart/mixed;\n" .
        " boundary=\"{$mimeBoundary}\"";
        $this->message = "-{$mimeBoundary}\n" .
        "Content-Type: text/plain; charset=\"iso-8859-1\n" .
        "Content-Transfer-Encoding: 7bit\n\n" .
        $this->message .= "\n\n";

        $data = chunk_split(base64_encode($data));
        $this->message .= "--{$mimeBoundary}\n" .
        "Content-Type: {$fileType};\n" .
        " name=\"{$fileName}\"\n" .
        "Content-Disposition: attachment;\n" .
        " filename=\"{$fileName}\"\n" .
        "Content-Transfer-Encoding: base64\n\n" .
        $data . "\n\n" .
        "-{$mimeBoundary}-\n";
      }

      private function FmtAddr(&$addr)
      {
          if ($addr[1] == "")
              return $addr[0];
          else
              return "\"{$addr[1]}\" <{$addr[0]}>";
      }

      private function FmtAddrList(&$addrs)
      {
        $list = "";
        foreach ($addrs as $addr) {
            if ($list) $list .= ", ".self::CRLF."\t";
            $list .= $this->FmtAddr($addr);
        }
        return $list;
      }

      function AddTo($addr,$name = "")
      {
        $this->mailTo[] = array($addr,$name);
      }

      function AddCc($addr,$name = "")
      {
        $this->mailCc[] = array($addr,$name);
      }

      function SetFrom($addr,$name = "")
      {
        $this->from = array($addr,$name);
      }

      function Send()
      {
        $newLine = self::CRLF;

        $errno = $errstr = null;

        //Connect to the host on the specified port
        $this->_skt = fsockopen(
            $this->_server, $this->_port,
            $errno, $errstr, $this->connectTimeout
        );

        if (empty($this->_skt)) {
            return false
                && error_log("[ ivy ] ".'Cannot open SMTP socket', E_USER_WARNING);
        }

        $this->log['connection'] = $this->GetResponse();

        //Say Hello to SMTP
        $this->log['helo']     = $this->SendCMD("EHLO {$this->_server}");

        //Request Auth Login
        $this->log['auth']     = $this->SendCMD("AUTH LOGIN");
        $this->log['username'] = $this->SendCMD(base64_encode($this->username));
        $this->log['password'] = $this->SendCMD(base64_encode($this->password));

        //Email from
        $this->log['mailfrom'] = $this->SendCMD("MAIL FROM:<{$this->from[0]}>");

        //Email to
        $cnt = 1;
        foreach (array_merge($this->mailTo, $this->mailCc) as $addr) {
            $this->log['rcptto'.$cnt++] = $this->SendCMD("RCPT TO:<{$addr[0]}>");
        }

        //The Email
        $this->log['data1'] = $this->SendCMD("DATA");

        //Construct headers
        if (!empty($this->contentType)) {
            $this->headers['Content-type'] = $this->contentType;
        }
        $this->headers['From'] = $this->FmtAddr($this->from);
        $this->headers['To'] = $this->FmtAddrList($this->mailTo);

        if (!empty($this->mailCc)) {
            $this->headers['Cc'] = $this->FmtAddrList($this->mailCc);
        }

        $this->headers['Subject'] = $this->subject;
        $this->headers['Date'] = date('r');

        $headers = '';
        foreach ($this->headers as $key => $val) {
            $headers .= $key . ': ' . $val . self::CRLF;
        }

        $this->log['data2'] = $this->SendCMD(
            "{$headers}{$newLine}{$this->message}{$newLine}."
        );

        // Say Bye to SMTP
        $this->log['quit']  = $this->SendCMD("QUIT");

        fclose($this->_skt);

      }
}
