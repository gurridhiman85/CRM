<?php
/**
 * Created by PhpStorm.
 * User: Tarinder
 * Date: 1/11/2018
 * Time: 4:43 PM
 */

namespace App\Library;


use Illuminate\Support\Facades\View;

class AjaxSideLayout extends Ajax
{
    const CALLBACK_SIDE_LAYOUT = 'loadSideLayout';

    public function loadSideLayout($view, $data)
    {
        $view = View::make($view, $data);
        $content = $view->render();
        $sdata = [
            'content' => $content
        ];
        if (isset($data['title'])) {
            $sdata['title'] = $data['title'];
        }
        if (isset($data['size'])) {
            $sdata['size'] = $data['size'];
        }
        $view = View::make('layouts.side-popup-layout', $sdata);
        $html = $view->render();
        $this->appendParam('html', $html);
        $this->jscallback(self::CALLBACK_SIDE_LAYOUT);
        return $this;
    }
}