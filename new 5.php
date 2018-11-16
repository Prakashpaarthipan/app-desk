// quotations
				for($i1=0; $i1<count($assign5); $i1++)
				{
					if($assign1[$i1] != '')
					{
						$qutat1i = '-'; $complogos2 = '-';
						if($_FILES['txtdynamic_deskprocedure']['type'][$i1] == "image/jpeg" or $_FILES['txtdynamic_deskprocedure']['type'][$i1] == "image/gif" or $_FILES['txtdynamic_deskprocedure']['type'][$i1] == "image/png" or $_FILES['txtdynamic_deskprocedure']['type'][$i1] == "application/pdf") {
							$qutat1i = find_indicator( $_FILES['txtdynamic_deskprocedure']['type'][$i1] );

							$imgfile2 = $_FILES['txtdynamic_deskprocedure']['tmp_name'][$i1];
							if($qutat1i == 'i')
							{
								$info = getimagesize($imgfile2);
								$image2 = imagecreatefromjpeg($imgfile2);
								if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile2);
								elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile2);
								elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile2);
								//save it
								imagejpeg($image, $imgfile2, 20);
							}

							switch($_FILES['txtdynamic_deskprocedure']['type'][$i1]) {
								case 'image/jpeg':
								case 'image/jpg':
								case 'image/gif':
								case 'image/png':
										$extn2 = 'jpg';
										break;
								case 'application/pdf':
										$extn2 = 'pdf';
										break;
							}

							// $upload_img1 = $_FILES['txt_submission_fieldimpl']['name'];
							$expl = explode(".", $_FILES['txtdynamic_deskprocedure']['name'][$i1]);

							$upload_img2 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_quotations_".$qutat1i."_".$i1.".".$extn2;
							$source2 = $imgfile2;
							$complogos2 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img2); //str_replace(" ", "_", $upload_img2));
							$complogos2 = str_replace(" ", "-", $upload_img2);
							$complogos2 = strtolower($complogos2);

							//// Thumb start
							if($qutat1i == 'i')
							{
								$upload_img2_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_quotations_".$qutat1i."_".$i1.".jpg";
								$source2_tmp = $imgfile2;
								$complogos2_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img2_tmp); //str_replace(" ", "_", $upload_img1));
								$complogos2_tmp = str_replace(" ", "-", $upload_img2_tmp);
								$complogos2_tmp = strtolower($complogos2_tmp);

								$width = $info[0];
								$height = $info[1];
								$newwidth1=200;
								$newheight1=200;
								$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
								imagecopyresampled($tmp1,$image2,0,0,0,0,$newwidth1,$newheight1,$width,$height);

								$resized_file = "../uploaded_files/". $complogos2_tmp;
								$dest_thumbfile = "approval_desk/request_entry/quotations/thumb_images/".$complogos2_tmp;
								imagejpeg($tmp1, $resized_file, 50);
								imagedestroy($image2);
								imagedestroy($tmp1);
								//echo "^^^".$complogos2_tmp.'<br>';
								move_uploaded_file($source2_tmp, $dest_thumbfile);
								$local_file = "../uploaded_files/".$complogos2_tmp;
								$server_file = 'approval_desk/request_entry/quotations/thumb_images/'.$complogos2_tmp;

								if ((!$conn_id) || (!$login_result)) {
									$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
												//echo "tmp Succes";
									unlink($local_file);
								}
							}
							//// Thumb end

							$original_complogos1 = "../uploads/request_entry/quotations/".$complogos2;
							//echo '!!!'.$complogos2.'<br>';
							move_uploaded_file($source2, $original_complogos1);

							/* Upload into FTP */
							$local_file = "../uploads/request_entry/quotations/".$complogos2;
							$server_file = 'approval_desk/request_entry/quotations/'.$complogos2;

							// Approval Documents
							$attch++;
							$tbl_docs = "APPROVAL_REQUEST_DOCS";
							$field_docs['APRNUMB'] = $apprno;
							$field_docs['APDCSRN'] = $attch;
							$field_docs['APRDOCS'] = $complogos2;
							$field_docs['APRHEAD'] = 'quotations';
							// $insert_docs = insert_dbquery($field_docs, $tbl_docs);
							// print_r($field_docs);
							// Approval Documents

							if ((!$conn_id) || (!$login_result)) {
								$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
											//echo "lar Succes";
								unlink($local_file);
							}
							if ($upload) {
								$insert_docs = insert_dbquery($field_docs, $tbl_docs);
							}
							/* Upload into FTP */
						}
					}
				}
				// quotations
