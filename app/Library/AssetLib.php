<?php
/**
 * Created by PhpStorm.
 * User: Tarinder
 * Date: 11/9/2017
 * Time: 3:25 PM
 */

namespace App\Library;

use Illuminate\Support\Facades\Config;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class AssetLib
{

    //put your code here
    public static function library(...$param)
    {

        foreach ($param as $single) {
            // print_r($single);
            AssetLib::js($single);
            AssetLib::css($single);
            // self::loadDependencies($single);
        }
    }

    public static function js(...$param)
    {
        foreach ($param as $file) {
            global $js_arr;
            if (!is_array($js_arr)) {
                $js_arr = array();
            }
            array_push($js_arr, $file);
        }
        return;
    }

    public static function css(...$param)
    {
        foreach ($param as $file) {
            global $css_arr;
            if (!is_array($css_arr)) {
                $css_arr = array();
            }
            array_push($css_arr, $file);
        }
        return;
    }

    public static function echoJsFiles($js_files)
    {
        global $js_arr;
        self::loadDependenciesJs($js_files);
        $to_load_files = [];
        //CSS_VER
        // $ver = Config::get('constants.JS_VER');
        $ver = Config::get('assetlib.JS_VER');
        if (!$ver) {
            $ver = time();
        }
        // $ver = time();
        foreach ($js_files as $key => $file) {
            $flag = FALSE;
            if (isset($file['required']) && $file['required']) {
                $flag = true;
            }
            if (is_array($js_arr) && in_array($key, $js_arr)) {
                $flag = true;
            }

            if ($flag && isset($file['path'])) {
                $file['name'] = $key;
                array_push($to_load_files, $file);
            }
        }

        if (isset($_GET['debugjs']) || true) {
            foreach ($to_load_files as $file) {
                $path = asset($file['path']);
                $path .= ('?ver=' . $ver);
                echo '<script src="' . $path . '" type="text/javascript" ></script>';
            }
        } else {
            $path = self::minifyJS($to_load_files);
            echo '<script src="' . $path . '" type="text/javascript" ></script>';
        }
        echo '<!-- Files from AssetLib JS @ ' . date('Y-m-d H:i:s') . ' -->';
        //echo '<link href="" rel="stylesheet" type="text/css" />';
    }

    public static function echoCssFiles($css_files)
    {
        global $css_arr;
        self::loadDependenciesCss($css_files);
        // print_r($css_arr);
        $ver = Config::get('assetlib.CSS_VER');
        if (!$ver) {
            $ver = time();
        }
        // $ver = time();
        $to_load_files = [];
        foreach ($css_files as $key => $file) {
            $flag = FALSE;
            if (isset($file['required']) && $file['required']) {
                $flag = true;
            }
            if (is_array($css_arr) && in_array($key, $css_arr)) {
                $flag = true;
            }

            if ($flag && isset($file['path'])) {
                $file['name'] = $key;
                array_push($to_load_files, $file);
            }

        }
        if (isset($_GET['debugcss']) || true) {
            foreach ($to_load_files as $file) {
                $path = asset($file['path']);
                $path .= ('?ver=' . $ver);
                echo '<link href="' . $path . '" rel="stylesheet" type="text/css" />';
            }
        } else {
            $path = self::minifyCSS($to_load_files);
            echo '<link href="' . $path . '" rel="stylesheet" type="text/css" />';
        }

        echo '<!-- Files from AssetLib CSS @ ' . date('Y-m-d H:i:s') . ' -->';
        //echo '<link href="" rel="stylesheet" type="text/css" />';
    }

    public static function minifyCSS($files)
    {
        $minutes = 10;
        $minifier = new CSS();
        $cachefilename = '';
        foreach ($files as $file) {
            // $minifier->add($file['path']);
            $cachefilename .= $file['name'];
        }
        $cachefilename = strtolower(md5($cachefilename)) . '.css';
        $minifiedPath = self::getPath() . $cachefilename;
        $fullminifiedpath = getcwd() . $minifiedPath;
        if (file_exists($fullminifiedpath)) {
            $time = filemtime($fullminifiedpath);
            // echo date('Y-m-d H:i:s', $time);
            // echo date('Y-m-d H:i:s', time());
            if ($time + ($minutes * 60) < time()) {

            } else {
                echo '<!-- Files from AssetLib CSS CACHE @ ' . date('Y-m-d H:i:s', $time) . ' -->';
                return $minifiedPath . '?ver=' . $time;
            }
        }

        foreach ($files as $file) {
            $minifier->add($file['path']);
        }

        $minifier->minify($fullminifiedpath);

        return $minifiedPath . '?ver=' . time();
    }

    public static function minifyJS($files)
    {
        $minutes = 10;
        $minifier = new JS();
        $cachefilename = '';
        foreach ($files as $file) {
            // $minifier->add($file['path']);
            $cachefilename .= $file['name'];
        }
        $cachefilename = strtolower(md5($cachefilename)) . '.js';
        $minifiedPath = self::getPath() . $cachefilename;
        $fullminifiedpath = getcwd() . $minifiedPath;
        if (file_exists($fullminifiedpath)) {
            $time = filemtime($fullminifiedpath);
            // echo date('Y-m-d H:i:s', $time);
            // echo date('Y-m-d H:i:s', time());
            if ($time + ($minutes * 60) < time()) {

            } else {
                echo '<!-- Files from AssetLib JS CACHE @ ' . date('Y-m-d H:i:s', $time) . ' -->';
                return $minifiedPath . '?ver=' . $time;
            }
        }

        foreach ($files as $file) {
            $minifier->add($file['path']);
        }

        $minifier->minify($fullminifiedpath);
        // $minifier->gzip($fullminifiedpath.'min.js', 1);

        return $minifiedPath . '?ver=' . time();
    }

    public static function getPath()
    {
        $path = getcwd();
        $cachepath = '/assetlibcache/';
        if (!file_exists($path . $cachepath)) {
            mkdir($path . $cachepath);
        }
        return $cachepath;
    }

    private static function loadDependenciesJs($js_files)
    {
        global $js_arr;

        foreach ($js_files as $key => $file) {
            $flag = FALSE;
            if (isset($file['required']) && $file['required']) {
                $flag = true;
            }
            if (is_array($js_arr) && in_array($key, $js_arr)) {
                $flag = true;
            }

            if ($flag && isset($file['path']) && isset($file['depends']) && is_array($file['depends'])) {
                foreach ($file['depends'] as $depend) {
                    array_push($js_arr, $depend);
                }
            }
        }

        // print_r($js_collect);
    }

    private static function loadDependenciesCss($css_files)
    {
        global $css_arr;

        foreach ($css_files as $key => $file) {
            $flag = FALSE;
            if (isset($file['required']) && $file['required']) {
                $flag = true;
            }
            if (is_array($css_arr) && in_array($key, $css_arr)) {
                $flag = true;
            }

            if ($flag && isset($file['path']) && isset($file['depends']) && is_array($file['depends'])) {
                foreach ($file['depends'] as $depend) {
                    array_push($css_arr, $depend);
                }
            }
        }

        // print_r($js_collect);
    }

}