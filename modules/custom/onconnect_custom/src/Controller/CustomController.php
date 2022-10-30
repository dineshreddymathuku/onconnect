<?php

namespace Drupal\onconnect_custom\Controller;

use Dompdf\Dompdf;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Laminas\Diactoros\Response\JsonResponse;

// Use Aws\Credentials\Credentials;.

/**
 * Provides custom controller functionalities.
 */
class CustomController extends ControllerBase {

  /**
   * Implements empty build for homepage.
   */
  public function home() {
    $build = [];
    return $build;
  }

  /**
   * Implements download file.
   */
  public function filedownloadS3($nid = NULL) {
    $nodeLoad = Node::load($nid);
    if ($nodeLoad) {
      $fileUrl = $nodeLoad->get("field_platform_file")->uri;
      $fileName = array_slice(explode('/', explode('?', $fileUrl)[0]), -1)[0];
      $p = parse_url($fileUrl);
      $this->downloadFile("s3:/" . $p['path'], $fileName);
    }
    else {
      return new JsonResponse("Data not found");
    }
  }

  /**
   * Implements Download file.
   */
  public function downloadS3($fid = NULL) {
    $fileLoad = File::load($fid);
    if ($fileLoad) {
      $fileUrl = $fileLoad->getFileUri();
      $fileName = $fileLoad->getFilename();
      $this->downloadFile($fileUrl, $fileName);
    }
    else {
      return new JsonResponse("File not found");
    }
  }

  /**
   * Implements Download file from paragraph.
   */
  public function downloadParagraphS3($pid = NULL) {
    $fileLoad = Paragraph::load($pid);
    if ($fileLoad) {
      $fileUrl = $fileLoad->get("field_pdf_url")->uri;
      $fileName = array_slice(explode('/', explode('?', $fileUrl)[0]), -1)[0];
      $p = parse_url($fileUrl);
      $this->downloadFile("s3:/" . $p['path'], $fileName);
    }
    else {
      return new JsonResponse("File not found");
    }
  }

  /**
   * Implements view file.
   */
  public function viewDocumentS3($nid = NULL) {
    $nodeLoad = Node::load($nid);
    if ($nodeLoad) {
      $fileUpload = $nodeLoad->get("field_upload_file")->target_id;
      if ($fileUpload) {
        $fileLoad = File::load($fileUpload);
        $resourceLink = $fileLoad->getFileUri();
        $fileName = $fileLoad->getFilename();
        $this->viewFile($resourceLink, $fileName);
      }
      else {
        $resourceLink = $nodeLoad->get("field_resource_link")->uri;
        $fileName = array_slice(explode('/', explode('?', $resourceLink)[0]), -1)[0];
        $p = parse_url($resourceLink);
        $this->viewFile("s3:/" . $p['path'], $fileName);
      }
    }
    else {
      return new JsonResponse("File not found");
    }
  }

  /**
   * Implements view file.
   */
  public function viewImageS3($fid = NULL) {
    $fileLoad = File::load($fid);
    if ($fileLoad) {
      $fileUrl = $fileLoad->getFileUri();
      $fileName = $fileLoad->getFilename();
      $this->viewFile($fileUrl, $fileName);
    }
    else {
      return new JsonResponse("File not found");
    }
  }

  /**
   * View File based on S3 URL.
   */
  public function viewFile($url = NULL, $fileName = NULL) {
    $keyPath = str_replace("s3://", "", $url);
    $s3config = \Drupal::config('s3fs.settings');
    $keys = \Drupal::service('key.repository');
    $iam_secret = $keys->getKey("aws_s3_secret_key")->getKeyValue();
    $iam_key = $keys->getKey("aws_s3_access_key")->getKeyValue();
    $bucket_name = $s3config->get("bucket");
    $aws_region = $s3config->get("region");
    try {
      $s3 = new S3Client(
        [
          'credentials' => [
            'key' => $iam_key,
            'secret' => $iam_secret,
          ],
          'version' => 'latest',
          'region' => $aws_region,
        ]
      );
      $result = $s3->getObject([
        'Bucket' => $bucket_name,
        'Key' => $keyPath,
      ]);
      header("Content-Type: {$result['ContentType']}");
      header('Content-Disposition: filename="' . basename($keyPath) . '"');
      echo $result['Body'];
      exit;
    } catch (S3Exception $e) {
      die("Error: " . $e->getMessage());
    }
    return [];
  }

  /**
   * Download File based on S3 URL.
   */
  public function downloadFile($url = NULL, $fileName = NULL) {
    $keyPath = str_replace("s3://", "", $url);
    // $keyPath = '2022-01/sub-banner.png';
    $s3config = \Drupal::config('s3fs.settings');
    $keys = \Drupal::service('key.repository');
    $iam_secret = $keys->getKey("aws_s3_secret_key")->getKeyValue();
    $iam_key = $keys->getKey("aws_s3_access_key")->getKeyValue();
    $bucket_name = $s3config->get("bucket");
    $aws_region = $s3config->get("region");
    try {
      $s3 = new S3Client(
        [
          'credentials' => [
            'key' => $iam_key,
            'secret' => $iam_secret,
          ],
          'version' => 'latest',
          'region' => $aws_region,
        ]
      );

      $result = $s3->getObject([
        'Bucket' => $bucket_name,
        'Key' => $keyPath,
      ]);
      header("Content-Type: {$result['ContentType']}");
      header('Content-Disposition:attachment; filename="' . basename($keyPath) . '"');
      echo $result['Body'];
      exit;
    } catch (S3Exception $e) {
      die("Error: " . $e->getMessage());
    }
    return [];
  }

  /**
   * Download Pillar File.
   */
  public function pillarDownload($nid = NULL, $pid = NULL) {
    include_once \Drupal::root() . '/core/themes/engines/twig/twig.engine';
    if ($pid) {
      $pillarLoad = Paragraph::load($pid);
    }
    $pillarPrint = [];
    $pillarDataPrint = [];
    if ($pillarLoad) {
      $pillarPrint['title'] = $pillarLoad->get("field_title")->value;
      $pillarPrint['description'] = $pillarLoad->get("field_description")->value;
      $pillarPrint['references'] = $pillarLoad->get("field_references")->value;
      $pillarDatas = $pillarLoad->get("field_data")->getValue();
      if ($pillarDatas) {
        foreach ($pillarDatas as $pillarData) {
          $pillarDataload = Paragraph::load($pillarData['target_id']);
          if ($pillarDataload) {
            $innerPillars = $pillarDataload->get("field_data")->getValue();
            if ($innerPillars) {
              foreach ($innerPillars as $innerPillar) {
                $innerPillarDataload = Paragraph::load($innerPillar['target_id']);
                if ($innerPillarDataload) {
                  $pillarDataPrint[$innerPillar['target_id']] = [
                    'title' => $innerPillarDataload->get("field_title_accordion")->value,
                    'description' => $innerPillarDataload->get("field_description")->value,
                  ];
                }
              }
            }
          }
        }
      }
      $pillarPrint['data'] = $pillarDataPrint;
      $dompdf = new Dompdf();
      $markup = twig_render_template(\Drupal::service('extension.list.module')
          ->getPath('onconnect_custom') . '/templates/pillar.html.twig', [
        'pillarData' => $pillarPrint,
      ]);

      $html = (string) $markup;
      $htmlDom = new \DOMDocument();
      $htmlDom->loadHTML($html);
      $imageTags = $htmlDom->getElementsByTagName('img');
      foreach ($imageTags as $imageTag) {
        $typeFile = explode(".", $imageTag->getAttribute('src'));
        $base64 = $this->base64Encodeimage(DRUPAL_ROOT . $imageTag->getAttribute('src'), $typeFile[1]);
        $html = str_replace($imageTag->getAttribute('src'), $base64, $html);
      }
      $dompdf->loadHtml($html);
      $dompdf->setPaper('A4');
      $dompdf->render();
      $dompdf->getCanvas()
        ->page_text(505, 790, "{PAGE_NUM}/{PAGE_COUNT}", '', 10, [
          0,
          0,
          0,
        ]);
      $dompdf->getCanvas()
        ->page_text(10, 10, date("d/m/Y, h:i", strtotime("now")), '', 10, [
          0,
          0,
          0,
        ]);
      $output = $dompdf->output();
      $time = time();
      $directory = 'public://pillars/';
      \Drupal::service('file_system')
        ->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);
      // Delete pillars created before.
      $files = glob(DRUPAL_ROOT . '/public-files/pillars/*');
      foreach ($files as $file) {
        if (is_file($file)) {
          unlink($file);
        }
      }
      $fileURI = "public://pillars/$time.pdf";
      file_put_contents($fileURI, $output);
      $file_name = $pillarPrint['title'] . ".pdf";
      header('Content-Type: application/octet-stream');
      header("Content-Transfer-Encoding: Binary");
      header("Content-Length: " . filesize($fileURI) . ";");
      header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      readfile($fileURI);
      exit;
    }
    $data['#cache']['max-age'] = 0;
    $data = [
      '#content' => $this->t('Unable to download.'),
    ];
    return $data;
  }

  /**
   * Download platform File.
   */
  public function platformDownload($nid = NULL) {
    include_once \Drupal::root() . '/core/themes/engines/twig/twig.engine';
    if ($nid) {
      $nodeLoad = Node::load($nid);
    }
    $pillarPrint = [];
    if ($nodeLoad) {
      $nodePlatforms = $nodeLoad->get("field_data")->getValue();
      foreach ($nodePlatforms as $nodePlatform) {
        $pid = $nodePlatform['target_id'];
        $platformLoad = Paragraph::load($pid);
        $pillarDataPrint = [];
        if ($platformLoad) {
          $pillarPrint[$pid]['title'] = $platformLoad->get("field_title")->value;
          $pillarPrint[$pid]['description'] = $platformLoad->get("field_description")->value;
          $pillarPrint[$pid]['references'] = $platformLoad->get("field_references")->value;
          $pillarDatas = $platformLoad->get("field_data")->getValue();
          if ($pillarDatas) {
            foreach ($pillarDatas as $pillarData) {
              $pillarDataload = Paragraph::load($pillarData['target_id']);
              if ($pillarDataload) {
                $innerPillars = $pillarDataload->get("field_data")
                  ->getValue();
                if ($innerPillars) {
                  foreach ($innerPillars as $innerPillar) {
                    $innerPillarDataload = Paragraph::load($innerPillar['target_id']);
                    if ($innerPillarDataload) {
                      $pillarDataPrint[$innerPillar['target_id']] = [
                        'title' => $innerPillarDataload->get("field_title_accordion")->value,
                        'description' => $innerPillarDataload->get("field_description")->value,
                      ];
                    }
                  }
                }
              }
            }
          }
          $pillarPrint[$pid]['data'] = $pillarDataPrint;
        }
      }
      $dompdf = new Dompdf();
      $markup = twig_render_template(\Drupal::service('extension.list.module')
          ->getPath('onconnect_custom') . '/templates/platform.html.twig', [
        'pillarDatas' => $pillarPrint,
      ]);

      $html = (string) $markup;
      $htmlDom = new \DOMDocument();
      $htmlDom->loadHTML($html);
      $imageTags = $htmlDom->getElementsByTagName('img');
      foreach ($imageTags as $imageTag) {
        $typeFile = explode(".", $imageTag->getAttribute('src'));
        $base64 = $this->base64Encodeimage(DRUPAL_ROOT . $imageTag->getAttribute('src'), $typeFile[1]);
        $html = str_replace($imageTag->getAttribute('src'), $base64, $html);
      }
      $dompdf->loadHtml($html);
      $dompdf->setPaper('A4');
      $dompdf->render();
      $dompdf->getCanvas()
        ->page_text(505, 790, "{PAGE_NUM}/{PAGE_COUNT}", '', 10, [
          0,
          0,
          0,
        ]);
      $dompdf->getCanvas()
        ->page_text(10, 10, date("d/m/Y, h:i", strtotime("now")), '', 10, [
          0,
          0,
          0,
        ]);
      $output = $dompdf->output();
      $time = time();
      $directory = 'public://platforms/';
      \Drupal::service('file_system')
        ->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);
      // Delete platforms created before.
      $files = glob(DRUPAL_ROOT . '/public-files/platforms/*');
      foreach ($files as $file) {
        if (is_file($file)) {
          unlink($file);
        }
      }
      $fileURI = "public://pillars/$time.pdf";
      file_put_contents($fileURI, $output);
      $file_name = $nodeLoad->get('title')->value . ".pdf";
      header('Content-Type: application/octet-stream');
      header("Content-Transfer-Encoding: Binary");
      header("Content-Length: " . filesize($fileURI) . ";");
      header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      readfile($fileURI);
      exit;
    }
    $data['#cache']['max-age'] = 0;
    $data = ['#content' => $this->t('Unable to download.')];
    return $data;
  }

  /**
   * Download Pillar File.
   */
  public function base64Encodeimage($filename = NULL, $filetype = NULL) {
    if ($filename) {
      $type = pathinfo($filename, PATHINFO_EXTENSION);
      $data = file_get_contents(urldecode($filename));
      $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
      return $base64;
    }
  }

}
