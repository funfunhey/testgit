<?php
/*
'183.232.33.171/IntelligentBusService.asmx/GetStationLicense?lineId=150819093028190&upDown=1&siteId=141127042208126';
'183.232.33.171/IntelligentBusService.asmx/GetLines?lineName=32';*/


//处理...
/**
*
*/
class Bus
{
  function test(){
    
  }

    function __construct($preurl,$type,$tailurl)
    {
         $this->valid_type =array('GetStationLicense','GetLines','GetLineById','GetSiteLines','location/ip','GetDistance','GetSites');
         $this->type = $type;
         //$this->data = $data;
         $this->preurl = $preurl;
         $this->tailurl = $tailurl;
         $result = array();

         //检查是否正确
         if($this->check_url()){
            $result['data'] = $this->post_way($this->get_total_url());
            $result['state'] = 1;
         }else{
            $result['tips'] = "访问的url不存在！";
            $result['state'] = 0;
         }

         echo json_encode($result);
    }

    // post方法
    function post_way($url){

        $data = file_get_contents($url);//目的页面内容获取

        return $data;
    }

    function get_total_url(){
        return ($this->preurl)."/".($this->type)."?".($this->tailurl);
    }



    function check_url(){
        if(in_array($this->type, $this->valid_type)){
            return true;
        }else{
            return false;
        }
    }


}

//$url = $_POST['url'];
$pre_url = "http://183.232.33.171/IntelligentBusService.asmx";


$type = $tail_url ='';
switch(@$_GET['type']){
    case 1://细节，查看某点某路的站点公车状况
        $type = "GetStationLicense";
        $tail_url = "lineId=".$_GET['lineId']."&upDown=".$_GET['upDown']."&siteId=".$_GET['siteId'];
        break;
    case 2://获取类似1路的所有路线
        $type = "GetLines";
        $tail_url = "lineName=".$_GET['lineName'];
        break;
    case 3://由一路id查出基本信息
        $type = "GetLineById";
        $tail_url = "lineId=".$_GET['lineId'];
        break;
    case 4://由站点查出所有经过的路线
        $type = "GetSiteLines";
        $tail_url = "siteId=".$_GET['siteId'];
        break;
    case 5://获取当前的位置,http://api.map.baidu.com/location/ip?ak=7IZ6fgGEGohCrRKUE9Rj4TSQ&ip=183.234.99.156&coor=bd09ll
        $pre_url = "http://api.map.baidu.com";
        $type = "location/ip";

        $remoteip = $_SERVER['REMOTE_ADDR'];
        $remoteip = '183.234.99.156';
        $tail_url = "ak=7IZ6fgGEGohCrRKUE9Rj4TSQ&ip=".$remoteip."&coor=bd09ll";
        break;
    case 6://附近站点
        $type = "GetDistance";
        $tail_url = "lng=".$_GET['latx']."&lat=".$_GET['laty'];
        break;
    case 7:
        //http://183.232.33.171/IntelligentBusService.asmx/GetSites?siteName=%E9%87%91，获取金字的所有站点
        $type = 'GetSites';
        $tail_url = 'siteName='.$_GET['siteName'];
        break;

    default:
        break;
}

if($type && $tail_url)
    $bus = new Bus($pre_url,$type,$tail_url);

?>
