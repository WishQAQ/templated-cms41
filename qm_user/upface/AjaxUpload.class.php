<?php
/*
 * Ajax �ϴ����
 * @author Lee.
 * @date 2013/4/17
 */
require_once(dirname(__FILE__)."/../config.php");

class AjaxUpload {
    private $form_name;  //�ļ�form����
    private $ext_arr;    //�����ϴ����ļ���׺
    private $upload_dir; //�ϴ�Ŀ¼
    private $file_size;  //�ļ���С

    public function __construct($form_name, $file_size) {
        //��ʼ������
        $this->form_name = $form_name;
        $this->ext_arr = array(
            '.jpg',
            '.png',
            '.gif',
            '.jpeg'
        );
        $this->upload_dir = dirname(__FILE__)."/../../uploads/userup/face/";
        $this->file_size = $file_size;
        $this->upload();
    }

    public function __set($key, $val) {
        $this->$key = $val;
    }

    /**
     * Ajax ��ˢ���ϴ�ͼƬ��jpg|gif|png��
     * @param bool $return_arr �Ƿ񷵻����飬ǰ�����ϴ��ɹ�
     * @return (array)? || output
     */
    public function upload($return_arr=false) {
        if (!is_dir($this->upload_dir)) mkdir($this->upload_dir, 0777); //�ϴ�Ŀ¼�������򴴽�
        $file = $_FILES[$this->form_name];
        if ($file['error']==1 || $file['size']>($this->file_size*1024)) exit('1'); //�ϴ�ʧ�ܣ�ͼƬ���ܴ��� $this->file_size k��
        switch ($file['error']) {
            case 3:
                exit('3'); //ͼƬֻ�в����ļ����ϴ����������ϴ���
                break;
            case 4:
                exit('4'); //û���κ��ļ����ϴ���
                break;
        }
        $ext = $this->getExt($file['name']);
        if (!in_array($ext, $this->ext_arr)) exit('5'); //��ͼƬ���ͣ����ϴ�jpg|png|gifͼƬ��
        $fname = time(). $ext; //ͼƬ����
        $filename = $this->upload_dir . '/' . $fname;
        if (!move_uploaded_file($file['tmp_name'], $filename)) { //ִ���ϴ�
            exit('upload error!'); //�ϴ�ʧ�ܣ�����δ֪
        } else {
            $arr = array('ok'=>1, 'filename'=> '/uploads/userup/face/'.$userid . $fname, 'size'=>$file['size']);
            if ($return_arr) return $arr;
            else {
                echo json_encode(array('ok'=>$arr['ok'], 'filename'=>$arr['filename']));
				exit;
            }
        }
    }

    /**
     * ��ȡ�ļ���׺��
     * @param string $file_name �ļ�����
     * @return string
     */
    private function getExt($file_name) {
        $ext = strtolower(strrchr($file_name, "."));
        return $ext;
    }
}
//$dsql->ExecuteNoneQuery2("update #@__member set demo='$filename' where mid='$mid'");
//echo $arr;
?>