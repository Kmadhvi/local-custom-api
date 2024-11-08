<?php if ( ! defined( 'ABSPATH' ) ) { die; }

// Read Text File
function readTxtFile( $filePath ){
	$fileText	=	"";
	$handle 	=	fopen( $filePath, "r");
	if ($handle) {		
		while (($line = fgets($handle)) !== false) {
			if( !empty( $line ) )
			$fileText	.=	"<p>".$line."</p>";
		}		
		fclose($handle);
	}
	return str_replace( array( "\r", "\n", "<p><\/p>" ), "", $fileText );
	return $fileText;
}

// Read PDF File
function readPDFFile( $filePath ){
	 $parser	=	new \Smalot\PdfParser\Parser(); 
	 $pdf 		=	$parser->parseFile($filePath); 
     $pdfText 	=	$pdf->getText(); 
	 $pdfText  	=   nl2br($pdfText);
	 $pdfText 	= 	preg_replace('#(?:<br\s*/?>\s*?){2,}#', '</p><p>', $pdfText);
	 $pdfConvert =  explode( "</p>", $pdfText );
	 $pdfText	=	"";
	 foreach( $pdfConvert as $text ) {
		 $text		=	str_replace( array( "\r", "\n", "<p><\/p>", "<br />", "<p>" ), "", $text );
		 if( !empty( $text ) )
		 $pdfText .= "<p>".$text."</p>";
	 }
	 return $pdfText;
}

// Read Docx File
function readDocFile( $filePath ){
	$docObj 	=	new DocxConversion( $filePath );
	$docText	=	$docObj->convertToText(1);
	return nl2br($docText);
}

function readAndConvertAi( $text ){
	//echo '<pre>'; print_r($text); echo '</pre>'; exit();
	$characterLimit = 170000;
	$aiConvertText	=	"";
	if( strlen( $text ) > $characterLimit ) {
		$textWrap	=	wordwrap( $text, $characterLimit, "<br>" );		
		$partText	=	explode( "<br>", $textWrap );		
		foreach( $partText as $tranText ){
			if( !empty( $tranText ) ) {	
				$convertedTextResponse	=	convertTextToAI( $tranText );
				if( !empty( $convertedTextResponse['plan']['text'] ) &&  !empty( $convertedTextResponse['contact_info']['contactinfo'] ) ) {
					//$aiConvertText	.=	$convertedTextResponse['text'];

					$aiConvertText	.=	$convertedTextResponse['plan']['text'];
					$aiConvertText	.=	$convertedTextResponse['contact_info']['contactinfo'];
				}
			}
		}
	} else {
		$convertedTextResponse	=	convertTextToAI( $text );
		if( !empty( $convertedTextResponse['plan']['text'] ) &&  !empty( $convertedTextResponse['contact_info']['contactinfo'] ) ) {
			//$aiConvertText	=	$convertedTextResponse['text'];

			$aiConvertText	.=	$convertedTextResponse['plan']['text'];
			$aiConvertText	.=	$convertedTextResponse['contact_info']['contactinfo'];
		}
	}
	return $aiConvertText;
}

function convertTextToAI( $text ) {
	$text = str_replace( array( '“','”','\'', '"',',' , ';', '<', '>', '\\',';', '(', ')','/' ), '', $text);
	$api_key = AISBCP_SECRET_KEY;
	$api_url = 'https://api.openai.com/v1/chat/completions'; 
	$api_url_contact = 'https://api.openai.com/v1/completions';
	$return	= array();

	/*----- use gpt-4-turbo-preview model for generate plan start -----*/
	$post_data = array(
        				"model" => "gpt-4-turbo-preview", 
						"messages" => [
							  	array( "role" => "user", "content" => "Can we have some business continuity plan or something what do we need to do and where do we need to contact for this \n". strip_tags($text) ),
							  	array( "role" => "assistant", "content" => "" ),
							],
						"temperature" => 0,
						"max_tokens" => 2048,
						"top_p" => 1,
						"frequency_penalty"=> 0,
						"presence_penalty"=> 0
					);
        	
	$post_data_string = json_encode($post_data);
	
	$headers = array(                                                                          
				'accept: application/json',
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json',             
              );

	// START CURL Execution
    $curl = curl_init();
    curl_setopt_array($curl, array(
    	CURLOPT_URL => $api_url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => '',
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 0,
    	CURLOPT_FOLLOWLOCATION => true,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => 'POST',
    	CURLOPT_POSTFIELDS =>$post_data_string,
    	CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $response_arr = (array)json_decode($response);
    
    
    $return['plan'] = array(
    					'text' => isset( $response_arr['choices'][0]->message->content ) ? $response_arr['choices'][0]->message->content : "",
    					'status_code' => isset( $response_arr['status_code'] ) ? $response_arr['status_code'] : "",
    					'ai_response' => $response_arr,
    				);
    /*----- use gpt-4-turbo-preview model for generate plan end -----*/

    /*----- use gpt-3.5-turbo-instruct model for generate contact information start -----*/
	$post_data_contact = array(
        				"model" => "gpt-3.5-turbo-instruct",
						"prompt" => "Please Provide All Possible Helpline Contact Details for this.\n\n".strip_tags( $text ),
						"temperature" => 1,
						"max_tokens" => 256,
						"top_p" => 1,
						"frequency_penalty"=> 0,
						"presence_penalty"=> 0,
						"best_of" => 1,
        			);
        	
	$post_data_string_contact = json_encode($post_data_contact);

	$headers_contact = array(                                                                          
				'accept: application/json',
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json',             
              );

	// START CURL Execution
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url_contact);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_string_contact);           
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_contact);
    $response_contact = curl_exec ($ch);
    $status_code_contact = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $response_arr_contact = (array)json_decode($response_contact);
   
    $return['contact_info'] = array(
    								'contactinfo' => isset( $response_arr_contact['choices'][0]->text ) ? trim( $response_arr_contact['choices'][0]->text ) : "",
    								'status_code_contact' => isset( $response_arr_contact['status_code_contact'] ) ? $response_arr_contact['status_code_contact'] : "",
    								'ai_response_contact' => $response_arr_contact,
    							);

    /*----- use gpt-3.5-turbo-instruct model for generate contact information end -----*/
    return $return;
}

function convertTextToPDF( $textData, $rendomString ){
	
	require_once( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PUBLIC_DIR . "/vendor/tcpdf/tcpdf.php");

	// Create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// Set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('continuity');
	$pdf->SetTitle('Text to PDF');
	$pdf->SetSubject('Converting Text to PDF');
	$pdf->SetKeywords('PHP, PDF, Text');

	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'AI Software for Business Continuity Plans', 'It is a AI Generated Report');

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// Set default font
	$pdf->SetFont('dejavusans', '', 12);

	// Add a page
	$pdf->AddPage();

	// Your text content
	$text = $textData;

	$html = '<div style="">'. $textData .'</div>';

	// reset pointer to the last page
	$pdf->lastPage();

	// output the HTML content
	$pdf->writeHTML($html, true, false, true, false, '');

	$rendomString = preg_replace('/\s+/', '', $rendomString);

	// Close and output PDF document
	$pdf->Output(AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR.'/generated-pdf/aisbcp_pdf_'.$rendomString.'.pdf', 'F');
	$pdf->Output(AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR.'/generated-pdf/aisbcp_pdf_'.$rendomString.'.pdf', 'I');

	return true;
}

function convertTextToImmediateAction( $message ){


	$message = str_replace( array( '“','”','\'', '"',',' , ';', '<', '>', '\\',';', '(', ')','/' ), '', $message);
	$api_key = AISBCP_SECRET_KEY;
	$api_url = 'https://api.openai.com/v1/chat/completions'; 

	$headers = array(                                                                          
				'accept: application/json',
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json',             
              ); 

	/*----- use gpt-4-turbo-preview model for generate plan start -----*/
	$post_data = array(
        				"model" => "gpt-3.5-turbo", 
						"messages" => [
							  	array( "role" => "user", "content" => "Immediate Action plan only checklist without numbers   \n". strip_tags($message) ),
							  	array( "role" => "assistant", "content" => "" ),
							],
						"temperature" => 0.5,
						"max_tokens" => 256,
						"top_p" => 1,
						"frequency_penalty"=> 0,
						"presence_penalty"=> 0
					);
        	
	$post_data_string = json_encode($post_data);

	// START CURL Execution
    $curl = curl_init();
    curl_setopt_array($curl, array(
    	CURLOPT_URL => $api_url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => '',
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 0,
    	CURLOPT_FOLLOWLOCATION => true,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => 'POST',
    	CURLOPT_POSTFIELDS =>$post_data_string,
    	CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $response_arr = (array)json_decode($response);

    $return['choices'] = array(
    					'text' => isset( $response_arr['choices'][0]->message->content ) ? $response_arr['choices'][0]->message->content : "",
    					'status_code' => isset( $response_arr['status_code'] ) ? $response_arr['status_code'] : "",
    					'ai_response' => $response_arr,
    				);

    return $return;

}
function convertTextToSafetyMeasures( $message ){
	$message = str_replace( array( '“','”','\'', '"',',' , ';', '<', '>', '\\',';', '(', ')','/' ), '', $message);
	$api_key = AISBCP_SECRET_KEY;
	$api_url = 'https://api.openai.com/v1/chat/completions'; 

	$headers = array(                                                                          
				'accept: application/json',
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json',             
              ); 

	/*----- use gpt-4-turbo-preview model for generate plan start -----*/
	$post_data = array(
        				"model" => "gpt-3.5-turbo", 
						"messages" => [
							  	array( "role" => "user", "content" => "Can we have detailed Safety measures ? for this \n". strip_tags($message) ),
							  	array( "role" => "assistant", "content" => "" ),
							],
						"temperature" => 0,
						"max_tokens" => 2048,
						"top_p" => 1,
						"frequency_penalty"=> 0,
						"presence_penalty"=> 0
					);
        	
	$post_data_string = json_encode($post_data);

	// START CURL Execution
    $curl = curl_init();
    curl_setopt_array($curl, array(
    	CURLOPT_URL => $api_url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => '',
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 0,
    	CURLOPT_FOLLOWLOCATION => true,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => 'POST',
    	CURLOPT_POSTFIELDS =>$post_data_string,
    	CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $response_arr = (array)json_decode($response);

    $return['choices'] = array(
    					'text' => isset( $response_arr['choices'][0]->message->content ) ? $response_arr['choices'][0]->message->content : "",
    					'status_code' => isset( $response_arr['status_code'] ) ? $response_arr['status_code'] : "",
    					'ai_response' => $response_arr,
    				);
    return $return;
}

function readAndConvertAiToSafetyMeasures( $text ){
	//echo '<pre>'; print_r($text); echo '</pre>'; exit();
	$characterLimit = 170000;
	$aiConvertText	=	"";
	if( strlen( $text ) > $characterLimit ) {
		$textWrap	=	wordwrap( $text, $characterLimit, "<br>" );		
		$partText	=	explode( "<br>", $textWrap );		
		foreach( $partText as $tranText ){
			if( !empty( $tranText ) ) {	
				$convertedTextResponse	=	convertTextToSafetyMeasures( $tranText );
				if( !empty( $convertedTextResponse['choices'] ) ) {
					$aiConvertText	.=	$convertedTextResponse['choices']['text'];
				}
			}
		}
	} else {
		$convertedTextResponse	=	convertTextToSafetyMeasures( $text );
		if(   !empty( $convertedTextResponse['choices'] ) ) { 
			$aiConvertText	.=	$convertedTextResponse['choices']['text'];
		}
	}
	return $aiConvertText;
}

function readAndConvertAiToCheckbox( $text ){
	//echo '<pre>'; print_r($text); echo '</pre>'; exit();
	$characterLimit = 170000;
	$aiConvertText	=	"";
	if( strlen( $text ) > $characterLimit ) {
		$textWrap	=	wordwrap( $text, $characterLimit, "<br>" );		
		$partText	=	explode( "<br>", $textWrap );		
		foreach( $partText as $tranText ){
			if( !empty( $tranText ) ) {	
				$convertedTextResponse	=	convertTextToImmediateAction( $tranText );
				if( !empty( $convertedTextResponse['choices'] ) ) {
					$aiConvertText	.=	$convertedTextResponse['choices']['text'];
				}
			}
		}
	} else {
		$convertedTextResponse	=	convertTextToImmediateAction( $text );
		if(   !empty( $convertedTextResponse['choices'] ) ) { 
			$aiConvertText	.=	$convertedTextResponse['choices']['text'];
		}
	}
	return $aiConvertText;
}


function convertTextToAssestsProtection( $message ){
	$message = str_replace( array( '“','”','\'', '"',',' , ';', '<', '>', '\\',';', '(', ')','/' ), '', $message);
	$api_key = AISBCP_SECRET_KEY;
	$api_url = 'https://api.openai.com/v1/chat/completions'; 

	$headers = array(                                                                          
				'accept: application/json',
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json',             
              ); 

	/*----- use gpt-3.5-turbo model for generate plan start -----*/
	$post_data = array(
        				"model" => "gpt-3.5-turbo", 
						"messages" => [
							  	array( "role" => "user", "content" => "Can we have detailed Assest protection plans for this \n". strip_tags($message) ),
							  	array( "role" => "assistant", "content" => "" ),
							],
						"temperature" => 0,
						"max_tokens" => 2048,
						"top_p" => 1,
						"frequency_penalty"=> 0,
						"presence_penalty"=> 0
					);
        	
	$post_data_string = json_encode($post_data);

	// START CURL Execution
    $curl = curl_init();
    curl_setopt_array($curl, array(
    	CURLOPT_URL => $api_url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => '',
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 0,
    	CURLOPT_FOLLOWLOCATION => true,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => 'POST',
    	CURLOPT_POSTFIELDS =>$post_data_string,
    	CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $response_arr = (array)json_decode($response);

    $return['choices'] = array(
    					'text' => isset( $response_arr['choices'][0]->message->content ) ? $response_arr['choices'][0]->message->content : "",
    					'status_code' => isset( $response_arr['status_code'] ) ? $response_arr['status_code'] : "",
    					'ai_response' => $response_arr,
    				);
    return $return;
}

function readAndConvertAiToAssestsProtection( $text ){
	//echo '<pre>'; print_r($text); echo '</pre>'; exit();
	$characterLimit = 170000;
	$aiConvertText	=	"";
	if( strlen( $text ) > $characterLimit ) {
		$textWrap	=	wordwrap( $text, $characterLimit, "<br>" );		
		$partText	=	explode( "<br>", $textWrap );		
		foreach( $partText as $tranText ){
			if( !empty( $tranText ) ) {	
				$convertedTextResponse	=	convertTextToAssestsProtection( $tranText );
				if( !empty( $convertedTextResponse['choices'] ) ) {
					$aiConvertText	.=	$convertedTextResponse['choices']['text'];
				}
			}
		}
	} else {
		$convertedTextResponse	=	convertTextToAssestsProtection( $text );
		if(   !empty( $convertedTextResponse['choices'] ) ) { 
			$aiConvertText	.=	$convertedTextResponse['choices']['text'];
		}
	}
	return $aiConvertText;
}


function convertTextToProcessBusinessContinuty( $message ){
	$message = str_replace( array( '“','”','\'', '"',',' , ';', '<', '>', '\\',';', '(', ')','/' ), '', $message);
	$api_key = AISBCP_SECRET_KEY;
	$api_url = 'https://api.openai.com/v1/chat/completions'; 

	$headers = array(                                                                          
				'accept: application/json',
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json',             
              ); 

	/*----- use gpt-4-turbo-preview model for generate plan start -----*/
	$post_data = array(
        				"model" => "gpt-3.5-turbo", 
						"messages" => [
							  	array( "role" => "user", "content" => "Can we have detailed Business Continuity Plan for this. \n". strip_tags($message) ),
							  	array( "role" => "assistant", "content" => "" ),
							],
						"temperature" => 0,
						"max_tokens" => 2048,
						"top_p" => 1,
						"frequency_penalty"=> 0,
						"presence_penalty"=> 0
					);
        	
	$post_data_string = json_encode($post_data);

	// START CURL Execution
    $curl = curl_init();
    curl_setopt_array($curl, array(
    	CURLOPT_URL => $api_url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => '',
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 0,
    	CURLOPT_FOLLOWLOCATION => true,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => 'POST',
    	CURLOPT_POSTFIELDS =>$post_data_string,
    	CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $response_arr = (array)json_decode($response);

    $return['choices'] = array(
    					'text' => isset( $response_arr['choices'][0]->message->content ) ? $response_arr['choices'][0]->message->content : "",
    					'status_code' => isset( $response_arr['status_code'] ) ? $response_arr['status_code'] : "",
    					'ai_response' => $response_arr,
    				);
    return $return;
}

function readAndConvertAiToProcessBusinessContinuty( $text ){
	$characterLimit = 170000;
	$aiConvertText	=	"";
	if( strlen( $text ) > $characterLimit ) {
		$textWrap	=	wordwrap( $text, $characterLimit, "<br>" );		
		$partText	=	explode( "<br>", $textWrap );		
		foreach( $partText as $tranText ){
			if( !empty( $tranText ) ) {	
				$convertedTextResponse	=	convertTextToProcessBusinessContinuty( $tranText );
				if( !empty( $convertedTextResponse['choices'] ) ) {
					$aiConvertText	.=	$convertedTextResponse['choices']['text'];
				}
			}
		}
	} else {
		$convertedTextResponse	=	convertTextToProcessBusinessContinuty( $text );
		if(   !empty( $convertedTextResponse['choices'] ) ) { 
			$aiConvertText	.=	$convertedTextResponse['choices']['text'];
		}
	}
	return $aiConvertText;
}

function convertAiTextToContactInfo ( $text ){
	$message = str_replace( array( '“','”','\'', '"',',' , ';', '<', '>', '\\',';', '(', ')','/' ), '', $text);

	  /*----- use gpt-3.5-turbo-instruct model for generate contact information start -----*/
	$api_key = AISBCP_SECRET_KEY;
	$api_url_contact = 'https://api.openai.com/v1/completions';

	$post_data_contact = array(
        				"model" => "gpt-3.5-turbo-instruct",
						"prompt" => "Please Provide All Possible Helpline Contact Details for this.\n\n".strip_tags( $message ),
						"temperature" => 1,
						"max_tokens" => 256,
						"top_p" => 1,
						"frequency_penalty"=> 0,
						"presence_penalty"=> 0,
						"best_of" => 1,
        			);
        	
	$post_data_string_contact = json_encode($post_data_contact);

	$headers_contact = array(                                                                          
				'accept: application/json',
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json',             
              );

	// START CURL Execution
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url_contact);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_string_contact);           
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_contact);
    $response_contact = curl_exec ($ch);
    $status_code_contact = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $response_arr_contact = (array)json_decode($response_contact);
   
    $return['contact_info'] = array(
    								'contactinfo' => isset( $response_arr_contact['choices'][0]->text ) ? trim( $response_arr_contact['choices'][0]->text ) : "",
    								'status_code_contact' => isset( $response_arr_contact['status_code_contact'] ) ? $response_arr_contact['status_code_contact'] : "",
    								'ai_response_contact' => $response_arr_contact,
    							);

    /*----- use gpt-3.5-turbo-instruct model for generate contact information end -----*/
    return $return;

}

function readAndConvertAiTocommunicationdrafts( $text ){

	$characterLimit = 170000;
	$aiConvertText	=	"";
	if( strlen( $text ) > $characterLimit ) {
		$textWrap	=	wordwrap( $text, $characterLimit, "<br>" );		
		$partText	=	explode( "<br>", $textWrap );		
		foreach( $partText as $tranText ){
			if( !empty( $tranText ) ) {	
				$convertedTextResponse	=	convertAiTextToContactInfo( $tranText );
				if(!empty( $convertedTextResponse['contact_info']['contactinfo'] )) {
					$aiConvertText	.=	$convertedTextResponse['contact_info']['contactinfo'] ;
				}
			}
		}
	} else {
		$convertedTextResponse	=	convertAiTextToContactInfo( $text );
		if(!empty( $convertedTextResponse['contact_info']['contactinfo'] ) ) {
			$aiConvertText	.=	$convertedTextResponse['contact_info']['contactinfo'];
		}
	}
	return $aiConvertText;


}

function convertTextToshutdownProcess( $message ){
	$message = str_replace( array( '“','”','\'', '"',',' , ';', '<', '>', '\\',';', '(', ')','/' ), '', $message);
	$api_key = AISBCP_SECRET_KEY;
	$api_url = 'https://api.openai.com/v1/chat/completions'; 

	$headers = array(                                                                          
				'accept: application/json',
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json',             
              ); 

	/*----- use gpt-4-turbo-preview model for generate plan start -----*/
	$post_data = array(
        				"model" => "gpt-3.5-turbo-0125", 
						"messages" => [
							  	array( "role" => "user", "content" => "Can we have detailed Shutdown process plans \n". strip_tags($message) ),
							  	array( "role" => "assistant", "content" => "" ),
							],
						"temperature" => 0,
						"max_tokens" => 2048,
						"top_p" => 1,
						"frequency_penalty"=> 0,
						"presence_penalty"=> 0
					);
        	
	$post_data_string = json_encode($post_data);

	// START CURL Execution
    $curl = curl_init();
    curl_setopt_array($curl, array(
    	CURLOPT_URL => $api_url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => '',
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 0,
    	CURLOPT_FOLLOWLOCATION => true,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => 'POST',
    	CURLOPT_POSTFIELDS =>$post_data_string,
    	CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $response_arr = (array)json_decode($response);

    $return['choices'] = array(
    					'text' => isset( $response_arr['choices'][0]->message->content ) ? $response_arr['choices'][0]->message->content : "",
    					'status_code' => isset( $response_arr['status_code'] ) ? $response_arr['status_code'] : "",
    					'ai_response' => $response_arr,
    				);
    return $return;
}

function readAndConvertAiToShutdownProcess( $text ){
	$characterLimit = 170000;
	$aiConvertText	=	"";
	if( strlen( $text ) > $characterLimit ) {
		$textWrap	=	wordwrap( $text, $characterLimit, "<br>" );		
		$partText	=	explode( "<br>", $textWrap );		
		foreach( $partText as $tranText ){
			if( !empty( $tranText ) ) {	
				$convertedTextResponse	=	convertTextToshutdownProcess( $tranText );
				if( !empty( $convertedTextResponse['choices'] ) ) {
					$aiConvertText	.=	$convertedTextResponse['choices']['text'];
				}
			}
		}
	} else {
		$convertedTextResponse	=	convertTextToshutdownProcess( $text );
		if(   !empty( $convertedTextResponse['choices'] ) ) { 
			$aiConvertText	.=	$convertedTextResponse['choices']['text'];
		}
	}
	return $aiConvertText;
}

function convertTextToRecoveryMeasures( $message ){
	$message = str_replace( array( '“','”','\'', '"',',' , ';', '<', '>', '\\',';', '(', ')','/' ), '', $message);
	$api_key = AISBCP_SECRET_KEY;
	$api_url = 'https://api.openai.com/v1/chat/completions'; 

	$headers = array(                                                                          
				'accept: application/json',
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json',             
              ); 

	/*----- use gpt-4-turbo-preview model for generate plan start -----*/
	$post_data = array(
        				"model" => "gpt-3.5-turbo-0125", 
						"messages" => [
							  	array( "role" => "user", "content" => "Can we have recovery plan and updates message for this  \n". strip_tags($message) ),
							  	array( "role" => "assistant", "content" => "" ),
							],
						"temperature" => 0,
						"max_tokens" => 2048,
						"top_p" => 1,
						"frequency_penalty"=> 0,
						"presence_penalty"=> 0
					);
        	
	$post_data_string = json_encode($post_data);

	// START CURL Execution
    $curl = curl_init();
    curl_setopt_array($curl, array(
    	CURLOPT_URL => $api_url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => '',
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 0,
    	CURLOPT_FOLLOWLOCATION => true,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => 'POST',
    	CURLOPT_POSTFIELDS =>$post_data_string,
    	CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $response_arr = (array)json_decode($response);

    $return['choices'] = array(
    					'text' => isset( $response_arr['choices'][0]->message->content ) ? $response_arr['choices'][0]->message->content : "",
    					'status_code' => isset( $response_arr['status_code'] ) ? $response_arr['status_code'] : "",
    					'ai_response' => $response_arr,
    				);
    return $return;
}

function readAndConvertAiToRecoveryMeasures( $text ){
	$characterLimit = 170000;
	$aiConvertText	=	"";
	if( strlen( $text ) > $characterLimit ) {
		$textWrap	=	wordwrap( $text, $characterLimit, "<br>" );		
		$partText	=	explode( "<br>", $textWrap );		
		foreach( $partText as $tranText ){
			if( !empty( $tranText ) ) {	
				$convertedTextResponse	=	convertTextToshutdownProcess( $tranText );
				if( !empty( $convertedTextResponse['choices'] ) ) {
					$aiConvertText	.=	$convertedTextResponse['choices']['text'];
				}
			}
		}
	} else {
		$convertedTextResponse	=	convertTextToshutdownProcess( $text );
		if(   !empty( $convertedTextResponse['choices'] ) ) { 
			$aiConvertText	.=	$convertedTextResponse['choices']['text'];
		}
	}
	return $aiConvertText;
}