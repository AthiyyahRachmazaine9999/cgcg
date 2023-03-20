<?php

use App\Models\UI\IconModel;
use App\Models\UI\MenuModel;
use App\Models\Role\Role_menu;
use App\Models\ConfigModel;
use Illuminate\Support\Facades\URL;

if (!function_exists('getMenu')) {
    function getMenu($id)
    {
        // dd($id);
        $s1 = request()->segment(1);
        $s2 = request()->segment(2);
        $s3 = request()->segment(3);
        $gets = MenuModel::select('ui_menu.*')->where([
            ['position', '1'],
            ['r.id_user', $id],
        ])
            ->join('role_menus as r', 'ui_menu.id', '=', 'r.id_menu')
            ->orderBy('ui_menu.id', 'asc')
            ->get();
        // dd($gets);
        $menu = '';
        // echo url()->current();
        foreach ($gets as $key => $get) {

            $submenu = MenuModel::select('ui_menu.*')->where([
                ['position', '2'], ['parent_id', $get->id], ['r.id_user', $id],
            ])->join('role_menus as r', 'ui_menu.id', '=', 'r.id_menu')
                ->orderBy('ui_menu.id', 'asc')
                ->get();

            // dd($submenu);
            // if (!isset($submenu)) {
            //     $menu .= '<li class="nav-item nav-item-submenu">';
            //     $menu .= '<a href="' . $get->link . '" class="nav-link">';
            // } else {
            //     $menu .= '<li class="nav-item">';
            //     $menu .= '<a href="#" class="nav-link">';
            // }
            if (checkChild($get->id) == 0) {
                $sp = $s1 == $get->link ? 'active' : '';
                $menu .= '<li class="nav-item">';
                $menu .= '<a href="' . URL::to('') . '/' . $get->link . '" class="nav-link ' . $sp . '">';
                $menu .= '<i class="' . getIcon($get->icon_id) . '"></i> <span>' . $get->title . '</span>';
                $menu .= '</a>';
                $menu .= '</li>';
            } else {
                $sp = $s1 == $get->link ? 'nav-item-expanded nav-item-open' : '';
                $menu .= '<li class="nav-item nav-item-submenu ' . $sp . '">';
                $menu .= '<a href="' . $get->link . '" class="nav-link">';
                $menu .= '<i class="' . getIcon($get->icon_id) . '"></i> <span>' . $get->title . '</span>';
                $menu .= '</a>';

                $menu .= '<ul class="nav nav-group-sub" data-submenu-title="' . $get->title . '">';

                foreach ($submenu as $key => $sub) {
                    if (checkChild($sub->id) > 0) {
                        $sp2 = $s1 . "/" . $s2 == $sub->link ? 'nav-item-expanded nav-item-open' : '';
                        $child = MenuModel::where([
                            ['position', '3'], ['parent_id', $sub->id], ['r.id_user', $id]
                        ])
                            ->join('role_menus as r', 'ui_menu.id', '=', 'r.id_menu')
                            ->orderBy('ui_menu.id', 'asc')->get();
                        $menu .= '<li class="nav-item nav-item-submenu ' . $sp2 . '">';
                        $menu .= '<a href="#" class="nav-link"><i class="' . getIcon($sub->icon_id) . '"></i>' . $sub->title . '</a>';
                        $menu .= '<ul class="nav nav-group-sub">';
                        foreach ($child as $key => $chd) {
                            $menu .= '<li class="nav-item"><a href="' . URL::to('') . '/' . $chd->link . '" class="nav-link"><i class="' . getIcon($chd->icon_id) . '"></i>' . $chd->title . '</a></li>';
                        }
                        $menu .= '</ul>';
                        $menu .= '</li>';
                    } else {

                        $sp2 = $s1 . "/" . $s2 == $sub->link ? 'active' : '';
                        $menu .= '<li class="nav-item"><a href="' . URL::to('') . '/' . $sub->link . '" class="nav-link ' . $sp2 . '"><i class="' . getIcon($sub->icon_id) . '"></i>' . $sub->title . '</a></li>';
                    }
                }

                $menu .= '</li>';
                $menu .= '</ul>';
                $menu .= '</li>';
            }
        }
        return $menu;
    }
}

if (!function_exists('getIcon')) {
    function getIcon($id)
    {
        $gets = IconModel::where('id', $id)->first();
        return $gets->title;
    }
}

if (!function_exists('checkChild')) {
    function checkChild($id)
    {
        $gets = MenuModel::where('parent_id', $id)->count();
        return $gets;
    }
}

if (!function_exists('checkParent')) {
    function checkParent($id)
    {
        $gets = MenuModel::where('id', $id)->first();
        return $gets->parent_id;
    }
}

if (!function_exists('getMenuName')) {
    function getMenuName($where)
    {
        $gets = MenuModel::where('title', $where)->first();
        return $gets->id;
    }
}


if (!function_exists('MenuAllow')) {
    function MenuAllow($id, $idmenu)
    {
        $gets = Role_menu::where([
            ['id_user', '=', $id],
            ['id_menu', '=', $idmenu],
        ])->first();
        return $gets == null ? "no" : "yes";
    }
}

if (!function_exists('getConfig')) {
    function getConfig($string)
    {
        $gets = ConfigModel::where('config_name', $string)->first();
        return $gets->config_value;
    }
}

if (!function_exists('getRomawi')) {
    function getRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }
}

if (!function_exists('penyebut')) {
    function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = penyebut($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai / 10) . " Puluh" . penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai / 100) . " Ratus" . penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai / 1000) . " Ribu" . penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai / 1000000) . " Juta" . penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = penyebut($nilai / 1000000000) . " Milyar" . penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = penyebut($nilai / 1000000000000) . " Trilyun" . penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }
}

if (!function_exists('terbilang')) {
    function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim(penyebut($nilai) . " Rupiah");
        } else {
            $hasil = trim(penyebut($nilai) . " Rupiah");
        }
        return $hasil;
    }
}

if (!function_exists('GetPPN')) {
    function GetPPN($invoice, $created)
    {
        $new_invoice = date('Y-m-d', strtotime($invoice));
        $new_created = date('Y-m-d', strtotime($created));
        if ($new_invoice >= '2022-04-01' or $new_created >= '2022-04-01') {
            $ppn = getConfig('ppn');
        } else {
            $ppn = 10;
        }
        return $ppn;
    }
}

if (!function_exists('integerToRoman')) {
    function integerToRoman($integer)
    {
        $integer = intval($integer);
        $result  = '';
        $lookup  = array(
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1
        );
        foreach ($lookup as $roman => $value) {
            $matches = intval($integer / $value);
            $result .= str_repeat($roman, $matches);
            $integer = $integer % $value;
        }
        return $result;
    }

    if (!function_exists('CheckLongLat')) {
        function CheckLongLat($long, $lat)
        {
            $lat  = substr($lat, 0, 6);
            $long = substr($long, 0, 6);

            if ($lat == "-6.142" && $long == "106.82") {
                $condition = "static";
                $name      = "PT.Mitra Era Global, Jl. P. Jayakarta no.8 - Komplek Artha Center Blok G no. 16, Pinangsia, Kec. Taman Sari, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11110";
            } elseif ($lat == "-6.136" && $long == "106.83") {
                $condition = "static";
                $name      = "maleser.com, Jl. Mangga Dua Square No.C22, RW.6, Ancol, Pademangan, Jakarta, 10730";
            } else {
                $condition = "normal";
                $name      = "normal";
            }
            $data = array(
                'condition' => $condition,
                'name'      => $name,
            );
            return json_encode($data);
        }
    }
}
