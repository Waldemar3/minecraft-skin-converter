<?php


namespace App;


class Skin
{
    private $bodySize = 180;
    private $avatarSize = 55;

    public function create($detail, $act = ''){
        $dir = '../public/img/session_skins/';
        $nick = UserVerification()->getUser()['login'];

        if($act == 'auth'){
            $nick = request()->login;
        }

        $skin['skin'] = '../public/img/skins/'.$nick.'.png';

        if(!file_exists($skin['skin'])){
            $skin['skin'] = '../public/img/skins/default.png';
        }

        if($detail == 'avatar'){
            $skin['size'] = $this->avatarSize;
        }else{
            $skin['size'] = $this->bodySize;
        }

        $skin['proportions'] = getimagesize($skin['skin']);

        $skin['height'] = $skin['proportions'][0];
        $skin['width'] = $skin['proportions'][1];

        $skin['r'] = $skin['height']/64;

        header('Content-Type: image/png');

        $skin['src'] = imagecreatefrompng($skin['skin']);

        if($detail == 'avatar'){
            $skin['dest'] = imagecreatetruecolor(16*$skin['r'], 16*$skin['r']);
        }else{
            $skin['dest'] = imagecreatetruecolor(16*$skin['r'], 32*$skin['r']);
        }

        $skin['color'] = imagecolorallocatealpha($skin['dest'], 255, 255, 255, 127);
        imagefill($skin['dest'], 0, 0, $skin['color']);

        if($detail == 'full'){

            //лицо
            imagecopy($skin['dest'], $skin['src'], 4*$skin['r'], 0*$skin['r'], 8*$skin['r'], 8*$skin['r'], 8*$skin['r'], 8*$skin['r']);

            //руки
            imagecopy($skin['dest'], $skin['src'], 0*$skin['r'], 8*$skin['r'], 44*$skin['r'], 20*$skin['r'], 4*$skin['r'], 12*$skin['r']);
            imagecopy($skin['dest'], $skin['src'], 12*$skin['r'], 8*$skin['r'], 44*$skin['r'], 20*$skin['r'], 4*$skin['r'], 12*$skin['r']);

            //тело
            imagecopy($skin['dest'], $skin['src'], 4*$skin['r'], 8*$skin['r'], 20*$skin['r'], 20*$skin['r'], 8*$skin['r'], 12*$skin['r']);

            //ноги
            imagecopy($skin['dest'], $skin['src'], 4*$skin['r'], 20*$skin['r'], 4*$skin['r'], 20*$skin['r'], 4*$skin['r'], 12*$skin['r']);
            imagecopy($skin['dest'], $skin['src'], 8*$skin['r'], 20*$skin['r'], 4*$skin['r'], 20*$skin['r'], 4*$skin['r'], 12*$skin['r']);

            //шляпа
            imagecopy($skin['dest'], $skin['src'], 4*$skin['r'], 0*$skin['r'], 40*$skin['r'], 8*$skin['r'], 8*$skin['r'], 8*$skin['r']);

        }elseif($detail == 'avatar'){
            imagecopy($skin['dest'], $skin['src'], 4*$skin['r'], 0*$skin['r'], 8*$skin['r'], 8*$skin['r'], 8*$skin['r'], 8*$skin['r']);
            imagecopy($skin['dest'], $skin['src'], 4*$skin['r'], 0*$skin['r'], 40*$skin['r'], 8*$skin['r'], 8*$skin['r'], 8*$skin['r']);
        }elseif($detail == 'back'){

            //тело сзади
            imagecopy($skin['dest'], $skin['src'], 4*$skin['r'], 8*$skin['r'], 32*$skin['r'], 20*$skin['r'], 8*$skin['r'], 12*$skin['r']);

            //голова сзади
            imagecopy($skin['dest'], $skin['src'], 4*$skin['r'], 0*$skin['r'], 24*$skin['r'], 8*$skin['r'], 8*$skin['r'], 8*$skin['r']);

            //руки cзади
            imagecopy($skin['dest'], $skin['src'], 0*$skin['r'], 8*$skin['r'], 44*$skin['r'], 20*$skin['r'], 4*$skin['r'], 12*$skin['r']);
            imagecopy($skin['dest'], $skin['src'], 12*$skin['r'], 8*$skin['r'], 44*$skin['r'], 20*$skin['r'], 4*$skin['r'], 12*$skin['r']);

            //ноги сзади
            imagecopy($skin['dest'], $skin['src'], 4*$skin['r'], 20*$skin['r'], 12*$skin['r'], 20*$skin['r'], 4*$skin['r'], 12*$skin['r']);
            imagecopy($skin['dest'], $skin['src'], 8*$skin['r'], 20*$skin['r'], 12*$skin['r'], 20*$skin['r'], 4*$skin['r'], 12*$skin['r']);

            //шляпа сзади
            imagecopy($skin['dest'], $skin['src'], 4*$skin['r'], 0*$skin['r'], 56*$skin['r'], 8*$skin['r'], 8*$skin['r'], 8*$skin['r']);
        }

        if($detail == 'avatar'){
            $skin['result'] = imagecreatetruecolor($skin['size'],$skin['size']);
        }else{
            $skin['result'] = imagecreatetruecolor($skin['size'],$skin['size']*2);
        }

        imagesavealpha($skin['result'], true);
        $skin['color'] = imagecolorallocatealpha($skin['result'], 255, 255, 255, 127);
        imagefill($skin['result'], 0, 0, $skin['color']);

        if($detail == 'avatar'){
            imagecopyresized($skin['result'], $skin['dest'], -$skin['size']/2, 0, 0, 0, imagesx($skin['result'])+$skin['size'], imagesy($skin['result'])+$skin['size'], imagesx($skin['dest']), imagesy($skin['dest']));
        }else{
            imagecopyresized($skin['result'], $skin['dest'], 0, 0, 0, 0, imagesx($skin['result']), imagesy($skin['result']), imagesx($skin['dest']), imagesy($skin['dest']));
        }

        if($detail == 'full'){
            imagepng($skin['result'],"$dir/$detail/$nick.png");
        }elseif($detail == 'back'){
            imagepng($skin['result'],"$dir/$detail/$nick.png");
        }elseif($detail == 'avatar'){
            imagepng($skin['result'],"$dir/$detail/$nick.png");
        }

        imagedestroy($skin['result']);
        imagedestroy($skin['src']);
        imagedestroy($skin['dest']);
    }

    public function delete(){
        $dir = '../public/img/session_skins/';
        $nick = UserVerification()->getUser()['login'];

        unlink("$dir/full/$nick.png");
        unlink("$dir/back/$nick.png");
        unlink("$dir/avatar/$nick.png");
    }
}