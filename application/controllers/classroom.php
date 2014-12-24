<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classroom extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('class_model');
        date_default_timezone_set('PRC');
    }

    public function index() {
        $data['classlog'] = $this->class_model->getClassLog();
        if (empty($data['classlog'])) {
            $this->update();
            return;
        }
        $data['classdata'] = $this->class_model->getClassData($data['classlog']['classlogid']);

        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = '东九教室';
        $header ['act'] = 'hall';


        $this->load->view('header', $header);
        $this->load->view('class', $data);
        $this->load->view('footer');
    }

    private function _getData() {
        $pattern = '/<li>([A-D]\d{3})<\/li>/';
        $base = 'http://wap.hustonline.net/classroom/info?cr-building-select=9&cr-class-check%5B%5D=class';
        $classroom = array();
        for ($i = 1; $i <= 5; $i++) {
            $str = file_get_contents($base . $i);
            $matches = array();
            preg_match_all($pattern, $str, $matches);

            foreach ($matches[1] as $key) {
                if (!array_key_exists($key, $classroom)) {
                    $classroom[$key] = 0;
                }
                $j = 0x10;
                $classroom[$key] |= ($j >> (5 - $i));
            }
        }
        //var_dump(array_map('decbin', $classroom));
        return $classroom;
    }

    public function update() {
        $classdata = $this->_getData();
        if (!empty($classdata)) {
            $logid = $this->class_model->setClassLog(time());
            $this->class_model->setClassData($classdata, $logid);
            redirect('classroom/index');
        } else {
            echo "error";
        }
    }

    public function update_jwc($building = null) {
        if ($building != 'd9' && $building != 'd12') {
            echo "error building";
            return;
        }
        $f = "jwc$building";
        $data['classdata'] = $this->$f();
        $logid = $this->class_model->setClassLog(time(), $building);
        $this->class_model->setClassData_JWC($building, $data['classdata'], $logid);
        redirect('classroom/jwc/' . $building);
    }

    public function jwc($building = 'd9') {
        $data['classlog'] = $this->class_model->getClassLog($building);
        if (empty($data['classlog'])) {
            $this->update_jwc($building);
            return;
        }
        $data['classdata'] = $this->class_model->getClassData_JWC($data['classlog']['classlogid']);
        
        $header ['userinfo'] = $this->userinfo;
        $header ['title'] = $building . '教室';
        $header ['act'] = 'hall';
        $this->load->view('header', $header);
        $this->load->view('class_jwc', $data);
        $this->load->view('footer');
    }

    public function jwcd9() {
        $base_url = 'http://202.114.5.131/index.aspx';
        $curl_data = array(
            '__EVENTTARGET' => 'Pager',
            '__EVENTARGUMENT' => 1,
            '__LASTFOCUS' => '',
            '__VIEWSTATE' => 'QjtS+fgnpu5d0DQmBgDBB/M+47nASYISFs9q7sboYvfiMwpUm2+SHLbOzcPh4f+IGlEDCI5HenW2AiFMxYhpOqJw357ULyP6okPkMslXZvJURzVcKYPVDLkKl7kXUE6/8zwaJKRlpVptLzbZ1mTsJX9R6YJMT8qTjXf+PZqUSuU7dI9OIuT03YUuwLRHEuVId9te9NGj+3jvEUAh9cVXIa4pYSe+AeFQ8imI4qipyWR+eypDtSUgU0XLxHEae7vbSi8hxD4Zam8q4cMC1czmywojBKXrj64jlRWumzU4+cgmEF2fKhVvsebElmlqARsSXLEgyvNBE8LRPvSC6SyalugiyphmhGOxTqHLUH1Cx4Y+iZE8YR2jCbT486wP8fQjWylyP+Xsd5QJwEnxfVYxkndSerjU0A+Nm4PbOc20ntrF0Z94bDngtha77hjjWYIKY4a7gio7h0I5jmNwCQVilxO6q+b9faJOSm2iU+tFSXYNGWLESyy1qa+hx5A5XYfgftOi5pn4Ap22B8yQO+ptzUXVXTk/aGI6E1JfLALpjjsBHov7UOV9PMD5JqVxJ0uGwSsD1fgzs/LIzKZRwfW4nPynXWORoVZIiQTT0oBsZ/4yRf6pKSuUXOBgHfu49yUchGgcdKYNV+uovFv8BuB83i2w89oC0z68t09maiCYWk/U0femY+k44CUMLK0bGLD8r5A+XEN0cdYHsGXZwFqzkH+kKM1t+/HO+eIiR3SA/88Ga8XVCYAO3LQ2Ky6UZvx/Cj7qa4QKNxQOXYKUgdx7SNMwCbfcC0A4qmoxXlTZAd07GMy4GyG5coBO/CBH9DP5gXNNq8+rhG3z3X9z1e1mLWnbDHoSjIjOSGAtI41YGFmsAdYU0HMZ8k5dgStow5iVLLgEDSuCd2MCYG3b/Rsb2F147eYvcMOk0CcNFGb220G8LKQ2rairTSKLz2+O+Bbt7zZ2c4+Yk2mUTtXhSxt9HacZMu6Qke5HWrKbxcs09s2WaoVoGvg1CL8b4kUWt3nO2AKGDrbB3Aq6pL30AroVRshtynF8fHWN5z2IbrBXXcOaqLkBNGqrGo39T10JsYMpRmHUyYj8/hnhjEH4+r811etJFQdC7LknEzJagkXfNn2iyOWF8SEfbmoAZibkWWgR9pQd8vtpupvXkEo6yrOsQE1qJ8hVVlwudM8o/jCoYkb4ECOv14c1zaK0nWcZDmAe98WnyyrKB/kl/z6Bhr7nAClsp4C3STVCQId6jOs6CCoIlTYCF9+as4vZpcX9MV60P1JhqnTf2wkbj/4kesnoG8wl5oUP1afJpVUr9aUpcBTMJIwC9MwRBuXYPDAd6Lve/PMagi8jsZemsEAFOqYDEdI7IcjD2hMHeyRGcYlYUqHG3kDjUFU9D31rtxBlXzJJpuD/cXyP4N4jL0uszfGtGs3KH7CSC0Ipe5jIfEkzRySTaSfEs2cwip0ALjH+d99klDlflO8vYseuKpAHGLieXV4p4LA/yvoz5iVW+dHQuKF4tSaU9DJIqsC7aBM3Mg5uc6f0ZHHq9JXiCyKxIVY3yliLFT8RCHimzzpOJXp4AGp1rQ20wNCYs4Ruq0esAkXCoVb43It3Kz7iXK7k5/HxGm34PmD4DGBaDwOehjk/+zYWqf7WfbxKwIEvxX3JLYiw4zDDUWLXvt5IZPgbBkilb4M6JVBG+KRjIKh6h7w6ldyydwcmZ1Hvgyb7K/l7od9JBvF6hKgvYHA4unZrDkBF80B311NQSiqWA9ezNR9/GHsbUK79y+zUhflrrL7Z9rNLWS6uaV3UrRVkUUx/lPQT3raTTWl41dnnTnWU1u0i8P3yrctVkgdIeUdCWyGm0dlsF4s+4GiQhSEuRJZqigOP33SdZIX1dR/RpDizTIU5NL2a6xqymeJ5D7txTrFbCtEWTfYks9d9NBEL4wpwOVeGfX7rpcQG0fATiWywPwVIeGPH6UFYsIM7CX3AsJVo725QyJ39BQVpFoasyo13mxs5ZIekBK/27fTtwXLG5gRnkwY9cm3O7tg9y15mzGY7lKjAfJ004bstsw5SnG1Z1G0myeMzNmJCHtaKb8jW32KAt4hIOP48g9kvlLxJDBhE5l4We87NCc120OcGunwGKmj6rdC5oGnmjikDMR8joJPY6pTuhz/AjI/Wpjq4RGl+sr0Zx8I/vQLfyDTQ7w8U+P0/jTOcviRj+9qwijR+dI1+T0EGvk83wTFoKaCn2GlTHvG8DEN1KKoEmM/pH88FbOrEE2XzZzVRRipkqM7cH45ed63NLQH15gce2KSb05pzeLHWaqPVO/dBnjamjcl0UOet6IORc/TDe1H9y1WPfUh3Y2EUDeesnpLMgb0n4kRbLJ/80kJ8/ishPKyAZgSFDKi8bZnoW/7/7B6jtnvOXCP3/kHTxWQnpMiasgc6llZNmPa1xgdm7MyeeKwJJGrH+15N+psBNP9SQWVWL3rgVyzBum/FluMil6X1k6GHVnjXi9rSHtxAJuAwYVbcM/IGAw0SpwX/UvazM9Bj0X++51tRQ72d+A3VuDTPQks18jl+pg9DPhqVMOvKqS9zLinKCuEMsS2JQ3/6cM6c85siH3cWUp/7tEiXwThwiqeKr/05YSFRVCT44L9DQgdrIop4MKH5I8bQMoa03zyc3qcXhY+85iDg8i42Fu3f+j0ZiXj/ZgemUmQx9QcyQeIIugb9Ric8ZB72dPmB1GIN4pjrAe2krwvr8ZGbVDkRTgeNh3PpcdZVKd0h9wDh+E+m4Q3HzAHbLq2kUDtYnDEln5q6WTenmwR6ptD3d2iEiYZHm3ankLlRgi2BtnSRiQ9zdIx2MZ8ffiGyDIfj1fGcO+adt9P8RFaCSB38M/m5us8sDkfRbYIbDVpsiMHcWFMwtbXfM5l3j/JrDV8a0T9GOi+cK99fJ3em0JRgNnsiD7E8Dc3YaWlO9/2nERiWVZ/zbYDYIvyzfI4nPi7QzRGRqWCHPPQABv/wcPMQ9eMUkhPXEljm+dRVUPVTBKWYGYj70IX6iVnuMOI82wPvI16u6iHvS9j0ttQvMvrBg8yFmJPbNHansm8+FgtOo53SGSR9vFjEaGDHmjVWdI9e5sGI9h5vOo3gWv5DbutSrEQlqka2YfuUBvtaUT4Babc0xLijjO/asC6BAWR+LMvPfe8a7cLIGfD2w2hMSUJADbYUcPotlmji+blt6r68gxiy8lGt7QK9sWsnbnvdiHvZD5kcgbZKki0rOqpAohGG3n6w/VD2MigI4pBC0/gMZY80r4qiiZzEmundX2xGwc+VkQ1htq1N9ZKZT5w286teq8BhPRJBRXf3oG0R3pQDyWmZ6g0LFOpYiqtM74o7R7L8VaVOl4SIF4kfG2k7HclhIGUlKb43eOxRNeJUg7DRRk1QalbVhROz0kjyNLmjDKbwV6jtEotkyWXNkg9Kv+Pw9v8qDfeLPkv0xt1svvL9Z14vrpwx3NTLgt02+CK2Ak7OcvALymrH+V1C4UBWZrdsg1rKvdciRFDIBnAUmXb6vBrADtfjcT1c5lRQP+6i7pMu9y2dFIYtyAmI5zLBSGKRGbsfR5nkR3gDZC6WTMt7p8pt9nN4EZZT44Ly84PqGO/afyMdK5bWeqakV+8fMUNKtRtSrnMDdTqZzCyVByJlNmAjDKF7Wxmxuu8S2Z4P0ACNNlha+vjkRIPqfpRZkZ77v4b1Uxb4XOnCay9aiPkDe6BDJxP6tFmYJbzg5o/i+fVprnZVyB5hPPPS7GkEA2m2pD5eesytgL1vxisEH3FLOm0aONh5gOaJYhZtK/F/uFJ5nPJ+YNLQsopnrSvihmDM1LIu7yRXpMKhyvXbgQa98PpRGfSUiL8PwuNxnDF48YbZCaUjrLMVh1jd1LZhSJmi2zDTAfxPX/HY+YOH0rKQiEJwKoE1iSBRnyfjcMl6/X9ax6BP5nKlwFI2zCsru6MEcg7JstYTGgH3uehfgm9MUI8JdZthdOErj/LQd8haC/4DnkAHbstHKgzzc/idoFSkAwUsrwTt4Yjee83PzOT+Cpv5vewImypkFINPt6KadzJsOjzlsOKO8fxy/MA75ZJma5rdj0R+wViuIOHX2Tp4Tr4bPB/gCq0tOlDjChxbADugGuO6c9YzFsHY2ASVsNn+h4Lrs0ov99VUlsO9aHgFJzmQyWN+a+pCmtWA9FaU/XHzg3MePSqVTHpbD4k08F3OIJXAmHbHj2Mj9Vc8ce3Zjr3P82TNNxZYWnm93LjnfOTGeffoB3m86Q9I0sFvadlEE6itC7N/PAQtTs1UIq+8as8l6RtubPbKnKNEE3kIVG6WEzF1DHXYzt/lOrN1X2goPn/DO3lv/26eYcpXhOIACxdSPHY1etp5Fyyf/YgYM76MpC4EOAFoqISvE8bNeT1BGBW4+WkTCtv7Db5eKoeJc0RXDILsvDL3+7wr5UFElcztesCt92XQEepizozXxfaMXvV1h7ozgxHfRBS9WkTbPd7n7gPGIE7983yRiYqk5aG4wgGlz/DaRagDgU5zUrR6+4FMkQ3ddVXVHalAfmQfSr+P2DLmxv0kS/+Kaupfzsw1BY07oiaIhRVVogSyFLGiYdEsS+qxZND6lViO/pccG/h8PdGI2QfonIIsK8rEZoNnYM9wr6KLbK+l19j7/oTZxxoltKSJNy5AnaiN6AWmdonYfuOtlHm7/G52EK1/Y+h+WvNd50kdsbJtXWVKuNg9ws6lNAE51DsCIWgDSYUs3LKX899kZLYi61ecw1Vp8r8zUeB9vzhC3+8ETiQksQHSzsTfoVj4Lr+V3wIRMWUhhMwfdAx8K93jeUpACypT+klng9MIK7w+E/2A3pfp6UaMGc0BJ7qLK6Hdt46dDYsuc4q4p32Q3QHoLQdQPrSUbew9yS/d4TnlNPxoHSVbmkm//VYqyLyNEi3fY92hFI8aJM1OJy1A3U48gl+oOpsmAThsiXqoRg+FlNZLrFlBtw5tpDVf0etqK441xbM8',
            'Term' => 11,
            'QueryDate' => date('Y-m-d', time()),
            'Build' => 7,
            'Filter' => '',
            '__VIEWSTATEENCRYPTED' => '',
            '__EVENTVALIDATION' => 'GHUG2iPwbuH53vHVmDspSWA/c4nYh8iA42oHx6NBg/bALshqk0o4/JkNZKSFonAp592DMHqh/+f2myMsImw+kYmSeMc3kP65tNEh1zCgKNV3fxA9jR11WI2c4uaQUnZ+zsJTCy2IqnF/H4ozB5S1GIKgQGYBPbgEbhfrGVFbws9PHA1agNWM6iczlhW5kRJ2blsnS2nWcNU1Z2tmdBysSMhQJIP+6Ip/ex68buplRfW9pn/UwaqxCKQrIn+uecrtlCTpmBgKetEaQUrpbYcFHLM+JKoWT4p4wYmK3UGMiaZnJZZq06xxX//RbA4HPdlpFlNZOxt4MfQx+dj62DABG0l+kY8ZjZMUSZqmvGpGSQKbkIMToTTbdeTUlg2uObV8ajQX9UwqrlWRdn4ZFz1CYEqBsuXz93rl3KDSy+HKnp7smhw+lr7Y3oCOzEgrhIXg1kHa+DsWuB2Pwtxp8HdRFQpTE3TuWchYPgbADyDQcUfLD8+MDKbKW2kL93oPRhaJE8zeacDkng+HZ1oIxuuHYPuCrECiYn/K7qizJYZcqoKUl0jIQCxk9v5YCUytEXpD4M9684ZpCz0H7ZEHTRVBYA/ujQpF8w/N/2BoNcS3btd1G45ujWfx6imudMGcwySubtYbLJsZHwdlogGIVrsAQgGnKKjOO5NtqPGmee9mbVBZcfB3zXhn62y77DASbZM3UfoR1LYP1BLOgjRndBNJt9t/bGuOXXTcHSdf8PpE4A1AiPTidbTZJkA/myho8mKD/Ckn5SaSCQgIrclH8BQgWMHTkFx/6gCZbDNYyjK/P0nlH8dQJxisupxJsSyu/KiHYBF7NZttFalZdPw5o1NdK5n0JlKg3THhGMCHu+DzBYOQIChmmp4nyKUlKd3N3vkskTIpZHQlREj8dSsdpjvDmMUldLwEeT8IxyCCce3s8eC24vUJWZQaRmYz5sfS813e1qijsd0opdpovrLMXws7awSzNMgYb5boOPlIcSvFf1FikS1Y86pwMGshQs0EvwlkUmmd+l/ibKuVP3nY/Wk51ji8snXIDACWE7sbLjs6WL7T7t5pOUkQV6v8uBOZyUu4ZdNyf8xjNl66oNuwkeLQR8255UzMtsO/+/g2+aE60S0oKXVswHmKzGLO/zUUKS7A4vN0D30acj+ATvs6/idFxuVHNc1J/WxszQ0sVVA61OdwGHqUfe0G9xYEWyJPObsO9Ut8LnsZ6JFkVTU5LdEQuX5kAvvh2erjHmFqNwYtqnIMofPY06NW5l5L64pzx8e4+poubfMLk+MTijpFWppAjYSJAacwF5ZE66uID9wo4evcyNBYcfaBsHMChYzcIvX78SCpQj0b0wybp8jCvM6ocTbTupluO23SfdQAnvNQvECTvGG+VeW5aemsk+l36Iyx3Kmk04rBw/nzANRpAtAfLw==',
            'Pager_input' => 1,
        );

        $pattern = '/<tr>\s*<td>(\w{3,4})<\/td>(<td>\S*<\/td>){5}\s*<\/tr>/i';
        $classroom = array();
        for ($page = 1; $page <= 15; $page++) {
            $curl_data['__EVENTARGUMENT'] = $page;
            $content = $this->getWebPage($base_url, $curl_data);
            $matches = array();
            preg_match_all($pattern, $content, $matches);
            $classroom = array_merge($classroom, $matches[0]);
        }

        return $classroom;
    }

    public function jwcd12() {
        $base_url = 'http://202.114.5.131/index.aspx';
        $curl_data = array(
            '__EVENTTARGET' => 'Pager',
            '__EVENTARGUMENT' => 1,
            '__LASTFOCUS' => '',
            '__VIEWSTATE' => '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',
            'Term' => 11,
            'QueryDate' => date('Y-m-d', time()),
            'Build' => 1,
            'Filter' => '',
            '__VIEWSTATEENCRYPTED' => '',
            '__EVENTVALIDATION' => 'i8Zp0tWby72UMkd76rYTR2i58YIoWcUvZIvxTvmNlfjiNkjQo9MSdtklJ9f9vVN/fHrjO8q1EneTY0UWQIAJILQfIxOUIUmrf3MgSY3CsZ40PEjIg4/P1UPzZ/heOA0+EU+0DSRax0tPnXV41fulSMa/6J8IyukNuEDUesUowt84v4ktW7bR0dqeWm84fzKtMiJ2fSwketbTix8fJnYLbJhWi4I1OUz7UfJvSZvybFxlVSWhqvRTWlEV+SN2VWiff4XVV75EBFzBtGmy4JZO09dsVZOmGaWiTJ3FWF9eCGv6O9FmFfoi21VxfL0mKYfa1cAihKdaqqYVmS1QJf/SMSFzuFfiluxMKHnGododeexLDAblNRuA6M7jVAAfS3+ReXzQhhbbrGljsYTfV63lk1sZQqZiH8u+ritaBPZ5Q165Abo8VnQ68IFzfiEQiErrQvV9kDrS1t4krsJ51lPm5LhWUvpWv2I4VmhMM3LcdyX/NEpFd85+PpNG9rnK6Ugg',
            'Pager_input' => 1,
        );

        $pattern = '/<tr>\s*<td>(\w{3,4})<\/td>(<td>\S*<\/td>){5}\s*<\/tr>/i';
        $classroom = array();
        for ($page = 1; $page <= 4; $page++) {
            $curl_data['__EVENTARGUMENT'] = $page;
            $content = $this->getWebPage($base_url, $curl_data);
            $matches = array();
            preg_match_all($pattern, $content, $matches);
            $classroom = array_merge($classroom, $matches[0]);
        }

        return $classroom;
    }

    public function getWebPage($url, $curl_data) {
        $options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page 
            CURLOPT_HEADER => false, // don't return headers 
            CURLOPT_FOLLOWLOCATION => true, // follow redirects 
            CURLOPT_ENCODING => "", // handle all encodings 
            CURLOPT_USERAGENT => "spider", // who am i 
            CURLOPT_AUTOREFERER => true, // set referer on redirect 
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect 
            CURLOPT_TIMEOUT => 120, // timeout on response 
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects 
            CURLOPT_POST => 1, // i am sending post data 
            CURLOPT_POSTFIELDS => $curl_data, // this are my post vars 
            CURLOPT_SSL_VERIFYHOST => 0, // don't verify ssl 
            CURLOPT_SSL_VERIFYPEER => false, // 
            CURLOPT_VERBOSE => 1                // 
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        //  $header['errno']   = $err; 
        //  $header['errmsg']  = $errmsg; 
        //  $header['content'] = $content; 
        return $content;
    }

}
